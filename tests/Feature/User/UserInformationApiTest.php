<?php

namespace Tests\User\Feature;

use Tests\ApiTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserInformationApiTest extends ApiTestCase
{
    public function testApiUserInformationWithToken()
    {
        $headers = $this->headersWithToken['user'];

        // index
        $this->get(
            '/api/user_informations',
            $headers
        )
        ->assertStatus(403);

        // show
        $this->get(
            sprintf('/api/user_informations/%s', $this->user->user_information->id),
            $headers
        )
        ->assertStatus(200);

        // update
        $this->put(
            sprintf('/api/user_informations/%s', $this->user->user_information->id),
            [
                'name' => str_random('100'),
                'enabled' => 0,
            ],
            $headers
        )
        ->assertStatus(200);

        // update(422)
        $this->put(
            sprintf('/api/user_informations/%s', $this->user->user_information->id),
            [
                'name' => null,
                'enabled' => null,
            ],
            $headers
        )
        ->assertStatus(422);

        // update(422)
        $this->put(
            sprintf('/api/user_informations/%s', $this->user->user_information->id),
            [
                'name' => str_random('101'),
                'enabled' => str_random('101'),
            ],
            $headers
        )
        ->assertStatus(422);

        // destroy
        $this->delete(
            sprintf('/api/user_informations/%s', $this->user->user_information->id),
            [],
            $headers
        )
        ->assertStatus(200);
    }

    public function testApiUserInformationWithoutToken()
    {
        $headers = $this->headersWithoutToken['user'];

        // index
        $this->get(
            '/api/user_informations',
            $headers
        )
        ->assertStatus(401);
        
        // show
        $this->get(
            sprintf('/api/user_informations/%s', $this->user->user_information->id),
            $headers
        )
        ->assertStatus(401);

        // update
        $this->put(
            sprintf('/api/user_informations/%s', $this->user->user_information->id),
            [
                'name' => str_random('100'),
                'enabled' => 1,
            ],
            $headers
        )
        ->assertStatus(401);

        // destroy
        $this->delete(
            sprintf('/api/user_informations/%s', $this->user->user_information->id),
            [],
            $headers
        )
        ->assertStatus(401);
    }
}