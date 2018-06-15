<?php

namespace App\Services;

use App\Defines\User\Role;
use App\Defines\UserInformation\Enabled;
use App\Facades\ApiAuth;
use App\Services\Service;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\UserInformationRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Auth\AuthenticationException;
use stdClass;

class AuthService extends Service
{
    use SendsPasswordResetEmails,
        ResetsPasswords;

    protected $userRepository;
    protected $userInformationRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        UserInformationRepositoryInterface $userInformationRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->userInformationRepository = $userInformationRepository;
    }

    public function login(array $credentials, array $scope = []): array
    {
        $object = new stdClass();
        $object->email = $credentials['email'];
        $object->password = $credentials['password'];

        return ApiAuth::passwordGrantAuthentication($object, $scope);
    }

    public function register(array $credentials, Role $role = null): Model
    {
        $object = new stdClass();
        $object->email = $credentials['email'];
        $object->password = $credentials['password'];
        $object->role = Role::USER();

        if (!is_null($role))
        {
            $object->role = $role;
        }
        
        $user = $this->userRepository->create($object);

        $object->user_id = $user->id;
        $object->name = $credentials['name'];
        $object->enabled = Enabled::ACTIVE();

        $this->userInformationRepository->create($object);

        return $this->userRepository->get($user->id);
    }

    public function getUser(int $user_id): Model
    {
        return $this->userRepository->get($user_id);
    }

    public function logout(): void
    {
        if (Auth::check())
        {
            $user = Auth::user();
            $accessToken = $user->token();

            ApiAuth::revokedOauthRefreshTokens($accessToken->id);
            ApiAuth::revokedOauthAccessToken($accessToken->id, $user->id);
        }

        return;
    }

    public function forgotPassword(array $credentials): void
    {
        $response = $this->broker()->sendResetLink(
            [
                'email' => $credentials['email'],
            ]
        );

        if ($response != Password::RESET_LINK_SENT)
        {
            throw new AuthenticationException();
        }

        return;
    }

    public function getResetPasswordToken(array $credentials): array
    {
        $user = $this->userRepository->getUserByEmail(
            $credentials['email']
        );

        return [
            'token' => $this->broker()->createToken($user),
        ];
    }

    public function resetLoginPassword(array $credentials): void
    {
        $response = $this->broker()->reset(
            $credentials, function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        if ($response != Password::PASSWORD_RESET)
        {
            throw new AuthenticationException();
        }

        return;
    }

    /**
     * override
     */
    public function broker()
    {
        return Password::broker();
    }
}