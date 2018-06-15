<?php
namespace Tests;

use App\Defines\User\Role;
use App\Repositories\Eloquent\Models\User;
use App\Repositories\Eloquent\Models\UserInformation;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use stdClass;

class ApiUsingTokenTestCase extends TestCase
{ 
    protected $system_user;
    protected $admin_user;
    protected $user;

    protected $headersWithToken = [
        'system_user' => [],
        'admin_user' => [],
        'user' => [],
    ];

    protected $headersWithoutToken = [
        'system_user' => [],
        'admin_user' => [],
        'user' => [],
    ];

    protected $scopes = [
        'system_user' => [],
        'admin_user' => [],
        'user' => [],
    ];

    // テストユーザのパスワード
    private $user_password = 'secret';

    public function setUp()
    {
        parent::setUp();

        // Create Password Grant Client
        $this->artisan('passport:client', ['--password' => null, '--no-interaction' => true]);

        // Password Grant Clientを取得
        $client = DB::table('oauth_clients')
            ->where('password_client', 1)
            ->first();

        // 権限別のユーザをセット
        $this->setUsers();

        // システムユーザ, リクエストのヘッダーを設定
        $response = $this->getAuthResponse($client, $this->system_user);
        $this->headersWithToken['system_user'] = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$response['access_token'],
        ];
        $this->headersWithoutToken['system_user'] = [
            'Accept' => 'application/json',
        ];

        // 管理ユーザ, リクエストのヘッダーを設定
        $response = $this->getAuthResponse($client, $this->admin_user);
        $this->headersWithToken['admin_user'] = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$response['access_token'],
        ];
        $this->headersWithoutToken['admin_user'] = [
            'Accept' => 'application/json',
        ];

        // ユーザ, リクエストのヘッダーを設定
        $response = $this->getAuthResponse($client, $this->user);
        $this->headersWithToken['user'] = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$response['access_token'],
        ];
        $this->headersWithoutToken['user'] = [
            'Accept' => 'application/json',
        ];
    }

    protected function setUsers(): void
    {
        // システムユーザ作成
        factory(User::class, 1)->create([
            'role' => Role::SYSTEM(),
            'email' => 'system_user@gmail.com',
        ])->each(function ($user) {
            factory(UserInformation::class, 1)->create([
                'user_id' => $user->id,
                'name' => 'system_user',
            ]);

            $this->system_user = $this->getUser($user->id);
        });

        // 管理ユーザ作成
        factory(User::class, 1)->create([
            'role' => Role::ADMIN(),
            'email' => 'admin_user@gmail.com',
        ])->each(function ($user) {
            factory(UserInformation::class, 1)->create([
                'user_id' => $user->id,
                'name' => 'admin_user',
            ]);
            $this->admin_user = $this->getUser($user->id);
        });

        // ユーザ作成
        factory(User::class, 1)->create([
            'role' => Role::USER(),
            'email' => 'user@gmail.com',
        ])->each(function ($user) {
            factory(UserInformation::class, 1)->create([
                'user_id' => $user->id,
                'name' => 'user',
            ]);
            $this->user = $this->getUser($user->id);
        });
    }

    protected function getUser(int $user_id): User
    {
        return User::with('user_information')
            ->find($user_id);
    }

    protected function getAuthResponse(stdClass $client, User $user): array
    {
        return $this->post('/oauth/token', [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $user->email,
            'password' => $this->user_password,
            'scope' => ''
        ])->json();
    }
}