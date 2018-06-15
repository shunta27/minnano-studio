<?php

namespace Tests\Feature;

use App\Facades\ApiAuth;
use Tests\ApiTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use stdClass;

class AuthApiTest extends ApiTestCase
{
    public function testAuthFlow()
    {
        $headers = [
            'Accept' => 'application/json;',
        ];

        $testAccount = [
            'name' => 'test_account_user',
            'email' => 'test_account_user@gmail.com',
            'password' => 'secret',
        ];

        // register
        $this->post(
            '/api/outh/register',
            [
                'name' => $testAccount['name'],
                'email' => $testAccount['email'],
                'password' => $testAccount['password'],
                'password_confirmation' => $testAccount['password'],
            ],
            $headers
        )
        ->assertStatus(200);

        // mocking...
        ApiAuth::shouldReceive('passwordGrantAuthentication')
            ->andReturn((function (array $user) {
                $client = DB::table('oauth_clients')
                    ->where('password_client', 1)
                    ->first();
                return $this->post('/oauth/token', [
                    'grant_type' => 'password',
                    'client_id' => $client->id,
                    'client_secret' => $client->secret,
                    'username' => $user['email'],
                    'password' => $user['password'],
                    'scope' => ''
                ])
                ->json();
            })($testAccount));
        ApiAuth::shouldReceive('getPasswordGrantClient')->passthru();
        ApiAuth::shouldReceive('revokedOauthRefreshTokens')->passthru();
        ApiAuth::shouldReceive('revokedOauthAccessToken')->passthru();

        // login
        $response = $this->post(
            '/api/outh/login',
            [
                'email' => $testAccount['email'],
                'password' => $testAccount['password'],
            ],
            $headers
        )
        ->assertStatus(200)
        ->json();

        $headers['Authorization'] = 'Bearer '.$response['data']['access_token'];

        // user
        $this->get(
            '/api/outh/user',
            $headers
        )
        ->assertStatus(200);

        // logout
        $this->post(
            '/api/outh/logout',
            [],
            $headers
        )
        ->assertStatus(200);

        // user logout後のデータ取得できないテスト
        // TODO:
        // $this->get(
        //     '/api/outh/user',
        //     $headers
        // )
        // ->assertStatus(401);
    }

    public function testForgotPasswordFlow()
    {
        $headers = [
            'Accept' => 'application/json',
        ];

        $testAccount = [
            'name' => 'shunta',
            'email' => 'shunta27ichikawa@gmail.com',
            'password' => 'secret',
        ];

        // register
        $this->post(
            '/api/outh/register',
            [
                'name' => $testAccount['name'],
                'email' => $testAccount['email'],
                'password' => $testAccount['password'],
                'password_confirmation' => $testAccount['password'],
            ],
            $headers
        )
        ->assertStatus(200);

        // mocking...
        ApiAuth::shouldReceive('passwordGrantAuthentication')
            ->andReturn((function (array $user) {
                $client = DB::table('oauth_clients')
                    ->where('password_client', 1)
                    ->first();
                return $this->post('/oauth/token', [
                    'grant_type' => 'password',
                    'client_id' => $client->id,
                    'client_secret' => $client->secret,
                    'username' => $user['email'],
                    'password' => $user['password'],
                    'scope' => ''
                ])
                ->json();
            })($testAccount));
        ApiAuth::shouldReceive('getPasswordGrantClient')->passthru();
        ApiAuth::shouldReceive('revokedOauthRefreshTokens')->passthru();
        ApiAuth::shouldReceive('revokedOauthAccessToken')->passthru();

        // login
        $this->post(
            '/api/outh/login',
            [
                'email' => $testAccount['email'],
                'password' => $testAccount['password'],
            ],
            $headers
        )
        ->assertStatus(200);

        // forgot password
        $this->post(
            '/api/outh/forgot_password',
            [
                'email' => $testAccount['email'],
            ],
            $headers
        )
        ->assertStatus(200);

        // 存在しないメールアドレスで
        $this->post(
            '/api/outh/forgot_password',
            [
                'email' => 'hogehoge@gmail.com',
            ],
            $headers
        )
        ->assertStatus(401);
    }

    public function testChangePasswordFlow()
    {
        $headers = [
            'Accept' => 'application/json',
        ];

        $testAccount = [
            'name' => 'test_account_user_3',
            'email' => 'test_account_user_3@test.com',
            'password' => 'secret',
        ];

        // register
        $this->post(
            '/api/outh/register',
            [
                'name' => $testAccount['name'],
                'email' => $testAccount['email'],
                'password' => $testAccount['password'],
                'password_confirmation' => $testAccount['password'],
            ],
            $headers
        )
        ->assertStatus(200);

        // get reset password token
        $response = $this->post(
            '/api/outh/token',
            [
                'email' => $testAccount['email'], 
            ],
            $headers
        )
        ->assertStatus(200)
        ->json();

        $resetPasswordToken = $response['data']['token'];

        // 新パスワード
        $testAccount['password'] = 'changesecret';

        // reset password
        $this->post(
            '/api/outh/reset_password',
            [
                'token' => $resetPasswordToken,
                'email' => $testAccount['email'],
                'password' => $testAccount['password'],
                'password_confirmation' => $testAccount['password'],
            ],
            $headers
        )
        ->assertStatus(200);

        // mocking...
        ApiAuth::shouldReceive('passwordGrantAuthentication')
            ->andReturn((function (array $user) {
                $client = DB::table('oauth_clients')
                    ->where('password_client', 1)
                    ->first();
                return $this->post('/oauth/token', [
                    'grant_type' => 'password',
                    'client_id' => $client->id,
                    'client_secret' => $client->secret,
                    'username' => $user['email'],
                    'password' => $user['password'],
                    'scope' => ''
                ])
                ->json();
            })($testAccount));
        ApiAuth::shouldReceive('getPasswordGrantClient')->passthru();
        ApiAuth::shouldReceive('revokedOauthRefreshTokens')->passthru();
        ApiAuth::shouldReceive('revokedOauthAccessToken')->passthru();

        // login
        $response = $this->post(
            '/api/outh/login',
            [
                'email' => $testAccount['email'],
                'password' => $testAccount['password'],
            ],
            $headers
        )
        ->assertStatus(200)
        ->json();

        $headers['Authorization'] = 'Bearer '.$response['data']['access_token'];

        // user
        $this->get(
            '/api/outh/user',
            $headers
        )
        ->assertStatus(200);

        // logout
        $this->post(
            '/api/outh/logout',
            [],
            $headers
        )
        ->assertStatus(200);
    }
}