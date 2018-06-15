<?php

namespace App\Http\Controllers;

use App\Http\Resources\Resource;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserForgotPasswordRequest;
use App\Http\Requests\UserResetPasswordRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(
        AuthService $authService
    )
    {
        $this->authService = $authService;
    }

    public function login(UserLoginRequest $request): Resource
    {
        return new Resource(
            $this->authService->login($request->toArray())
        );
    }

    public function register(UserRegisterRequest $request): Resource
    {
        return new Resource(
            $this->authService->register($request->toArray())
        );
    }

    public function user(Request $request): Resource
    {
        $user = Auth::user();

        return new Resource(
            $this->authService->getUser($user->id)
        );
    }

    public function logout(Request $request): Resource
    {
        $this->authService->logout();

        return new Resource([]);
    }

    public function forgotPassword(UserForgotPasswordRequest $request): Resource
    {
        $this->authService->forgotPassword($request->toArray());

        return new Resource([]);
    }

    public function getResetPasswordToken(UserForgotPasswordRequest $request): Resource
    {
        return new Resource(
            $this->authService->getResetPasswordToken($request->toArray())
        );
    }

    public function resetPassword(UserResetPasswordRequest $request): Resource
    {
        $this->authService->resetLoginPassword($request->toArray());

        return new Resource([]);
    }
}