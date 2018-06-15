<?php

namespace App\Components;

use GuzzleHttp\Client;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Expression;
use RuntimeException;

class GoogleMapComponet
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $api_key;

    protected $endpoint = '/maps/api/geocode/json';
    
    protected $defaultConfig = [
        'headers' => [
            'Accept' => 'application/json;'
        ], 
        'base_uri' => 'https://maps.googleapis.com',
    ];

    public function __construct(array $config = [])
    {
        $this->defaultConfig = array_merge($this->defaultConfig, $config);

        $this->client = new Client($this->defaultConfig);

        $api_key = config('app.external.google.google_map.api_key');

        if (!$api_key)
        {
            throw new RuntimeException('It is not set `google map api key`.');
        }
        
        $this->api_key = $api_key;
    }

    public function getAddressToLocation(string $prefecture, string $city = ''): ?Expression
    {
        $location = $this->getLocation(
            $prefecture,
            $city
        );

        if (empty($location))
        {
            return null;
        }

        $point = $this->getLLToPoint($location);

        return $this->getSqlAsPoint($point);
    }

    public function getLocation(string $prefecture, string $city = ''): array
    {
        $address = $prefecture . $city;
        
        $response = $this->getResponse($address);

        if ($response->getStatusCode() != 200)
        {
            $response = $this->getResponse($prefecture);
        }

        $response = json_decode($response->getBody(), true);

        if (json_last_error() !== JSON_ERROR_NONE)
        {
            throw new RuntimeException(
              'Error trying to decode response: '.
              json_last_error_msg()
            );
        }

        return $response['results'][0]['geometry']['location'] ?? [];
    }

    protected function getResponse(string $address)
    {
        return $this->client->request(
            'GET',
            $this->endpoint,
            [
                'query' => [
                    'key' => $this->api_key,
                    'address' => $address,
            ],
        ]);
    }

    public function getLLToPoint(array $location): Point
    {
        return (new Point($location['lat'], $location['lng']));
    }

    public function getSqlAsPoint(Point $point): Expression
    {
        return DB::raw("(GeomFromText('{$point->toWKT()}'))");
    }
}