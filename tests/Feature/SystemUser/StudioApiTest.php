<?php

namespace Tests\SystemUser\Feature;

use Tests\ApiTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StudioApiTest extends ApiTestCase
{
    public function testApiStudioWithToken()
    {
        $headers = $this->headersWithToken['system_user'];

        // index
        $response = $this->get(
                '/api/studios',
                $headers
            )
            ->assertStatus(200)
            ->json();
        
        if ($response['next_page_url'])
        {
            $this->get(
                $response['next_page_url'],
                $headers
            )
            ->assertStatus(200);
        }

        // show
        if ($response['data'])
        {
            foreach($response['data'] as $studio)
            {
                $this->get(
                    sprintf('/api/studios/%s', $studio['id']),
                    $headers
                )
                ->assertStatus(200);
                // 一件のみで終わり
                break;
            }
        }

        // store
        $response = $this->post(
                '/api/studios',
                [
                    'name' => 'テストスタジオ',
                    'url' => 'https://www.google.co.jp/',
                    'tel' => '09091973796',
                    'zip' => '1360074',
                    'prefecture' => '東京',
                    'city_1' => '江東区東砂',
                    'city_2' => null,
                    'studio_count' => 1,
                    'open_dt' => '10:00:00',
                    'end_dt' => '24:00:00',
                    'cheapest_price' => 1000,
                    'is_web_reservation' => false,
                ],
                $headers
            )
            ->assertStatus(200)
            ->json();

        $studio = $response['data'];
        $studio['name'] = 'スタジオ名変更';

        // update
        $this->put(
            sprintf('/api/studios/%s', $studio['id']),
            $studio,
            $headers
        )
        ->assertStatus(200);
        // TODO: 変更したプロパティチェックする

        // store(422)
        $this->post(
            '/api/studios',
            [],
            $headers
        )->assertStatus(422);

        // update(422)
        $this->put(
            sprintf('/api/studios/%s', $studio['id']),
            [],
            $headers
        )->assertStatus(422);

        // destroy
        $this->delete(
            sprintf('/api/studios/%s', $studio['id']),
            [],
            $headers
        )
        ->assertStatus(200);
    }

    public function testApiStudioWithoutToken()
    {
        $headers = $this->headersWithoutToken['system_user'];

        // index
        $response = $this->get(
            '/api/studios',
            $headers
        )
        ->assertStatus(200)
        ->json();
            
        if ($response['next_page_url'])
        {
            $this->get(
                $response['next_page_url'],
                $headers
            )
            ->assertStatus(200);
        }

        if ($response['data'])
        {
            foreach($response['data'] as $studio)
            {
                // show
                $this->get(
                    sprintf('/api/studios/%s', $studio['id']),
                    $headers
                )
                ->assertStatus(200);

                // update
                $this->put(
                    sprintf('/api/studios/%s', $studio['id']),
                    [],
                    $headers
                )
                ->assertStatus(401);

                // destroy
                $this->delete(
                    sprintf('/api/studios/%s', $studio['id']),
                    [],
                    $headers
                )
                ->assertStatus(401);

                break;
            }
        }

        // store
        $this->post(
            '/api/studios',
            [
                'name' => 'テストスタジオ',
                'url' => 'https://www.google.co.jp/',
                'tel' => '09091973796',
                'zip' => '1360074',
                'prefecture' => '東京',
                'city_1' => '江東区東砂',
                'city_2' => null,
                'studio_count' => 1,
                'open_dt' => '10:00:00',
                'end_dt' => '24:00:00',
                'cheapest_price' => 1000,
                'is_web_reservation' => false,
            ],
            $headers
        )
        ->assertStatus(401);
    }
}