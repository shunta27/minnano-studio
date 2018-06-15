<?php

namespace App\Components;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use stdClass;
use RuntimeException;

class ApiAuthComponet
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    protected $defaultConfig = [
        'headers' => [
            'Accept' => 'application/json;'
        ], 
    ];

    public function __construct(array $config = [])
    {
        $this->defaultConfig = array_merge($this->defaultConfig, $config);

        $this->client = new Client($this->defaultConfig);
    }

    public function passwordGrantAuthentication(stdClass $object, array $scope = []): array
    {
        $passwordGrantClient = $this->getPasswordGrantClient();

        try
        {
            $response = $this->client->request(
                'POST',
                $this->prepareUrlForRequest('/oauth/token'),
                [
                    'form_params' => [
                        'grant_type' => 'password',
                        'client_id' => $passwordGrantClient->id,
                        'client_secret' => $passwordGrantClient->secret,
                        'username' =>  $object->email,
                        'password' => $object->password,
                        'scope' => $scope,
                    ],
                ]
            );
        }
        catch (TransferException $e)
        {
            // TODO: 例外処理正しくハンドリングする
            throw $e;
        }

        $response = json_decode($response->getBody(), true);

        if (json_last_error() !== JSON_ERROR_NONE)
        {
            throw new RuntimeException(
              'Error trying to decode response: '.
              json_last_error_msg()
            );
        }

        return $response;
    }

    protected function getPasswordGrantClient(): stdClass
    {
        $client = DB::table('oauth_clients')
            ->where('password_client', 1)
            ->first();
        
        if (!$client)
        {
            throw new RuntimeException('Password Grant Client does not exist');
        }

        return $client;
    }

    protected function prepareUrlForRequest(string $uri): string
    {
        if (Str::startsWith($uri, '/'))
        {
            $uri = substr($uri, 1);
        }

        if (!Str::startsWith($uri, 'http'))
        {
            $uri = config('app.url').'/'.$uri;
        }

        return trim($uri, '/');
    }

    public function revokedOauthRefreshTokens(string $accessTokenId): bool
    {
        return DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessTokenId)
            ->update([
                'revoked' => true,
            ]);
    }

    public function revokedOauthAccessToken(string $accessTokenId, int $user_id): bool
    {
        $passwordGrantClient = $this->getPasswordGrantClient();

        return DB::table('oauth_access_tokens')
            ->where('id', $accessTokenId)
            ->where('user_id', $user_id)
            ->where('client_id', $passwordGrantClient->id)
            ->update([
                'revoked' => true
            ]);
    }
}