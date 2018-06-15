<?php

namespace App\Http\Controllers\Auth;

use App\Defines\User\Role;
use App\Defines\UserInformation\Enabled;
use App\Services\UserService;
use App\Services\UserInformationService;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegisterRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use stdClass;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    
    /**
     * @var \App\Services\UserService
     */
    protected $userService;
    
    /**
     * @var \App\Services\UserInformationService
     */
    protected $userInformationService;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        UserService $userService,
        UserInformationService $userInformationService
    )
    {
        $this->middleware('guest');
        $this->userService = $userService;
        $this->userInformationService = $userInformationService;
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \App\Http\Requests\UserRegisterRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function register(UserRegisterRequest $request)
    {
        $credentials = $request->only('name', 'email', 'password');
        $credentials['role'] = Role::USER();
        $user = $this->userService->createUser($credentials);

        $object = new stdClass();
        $object->user_id = $user->id;
        $object->name = $credentials['name'];
        $object->enabled = Enabled::ACTIVE();
        $this->userInformationService->createUserInformation($object);

        event(new Registered($user));

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    /**
     * The user has been registered.
     *
     * @param  \App\Http\Requests\UserRegisterRequest  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(UserRegisterRequest $request, $user)
    {
    }
}
