<?php 

namespace Tests\SystemUser\Feature;

use Tests\ApiTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentApiTest extends ApiTestCase
{
    public function testApiStudioWithToken()
    {
        $headers = $this->headersWithToken['system_user'];

        $studio = $this->studiosHasComments[0];

        // index
        $response = $this->get(
                sprintf('/api/studios/%s/comments', $studio['id']),
                $headers
            )
            ->assertStatus(200)
            ->json();
        
        // show
        if ($response['data'])
        {
            foreach($response['data'] as $comment)
            {
                $this->get(
                    sprintf('/api/studios/%s/comments/%s', $studio['id'], $comment['id']),
                    $headers
                )
                ->assertStatus(200);
                // 一件のみで終わり
                break;
            }
        }

        // store
        $response = $this->post(
                sprintf('/api/studios/%s/comments', $studio['id']),
                [
                    'body' => str_random('200'),
                ],
                $headers
            )
            ->assertStatus(200)
            ->json();

        $comment = $response['data'];
        $comment['body'] = str_random('1000');

        // update
        $this->put(
            sprintf('/api/studios/%s/comments/%s', $studio['id'], $comment['id']),
            $comment,
            $headers
        )
        ->assertStatus(200);
        // TODO: 変更したプロパティチェックする

        // store(422)
        $this->post(
            sprintf('/api/studios/%s/comments', $studio['id']),
            [
                'body' => null,
            ],
            $headers
        )
        ->assertStatus(422);

        // update(422)
        $this->put(
            sprintf('/api/studios/%s/comments/%s', $studio['id'], $comment['id']),
            [],
            $headers
        )
        ->assertStatus(422);

        // destroy
        $this->delete(
            sprintf('/api/studios/%s/comments/%s', $studio['id'], $comment['id']),
            [],
            $headers
        )
        ->assertStatus(200);
    }

    public function testApiStudioWithoutToken()
    {
        $headers = $this->headersWithoutToken['system_user'];

        $studio = $this->studiosHasComments[0];

        // index
        $response = $this->get(
                sprintf('/api/studios/%s/comments', $studio['id']),
                $headers
            )
            ->assertStatus(200)
            ->json();
        
        // show
        if ($response['data'])
        {
            foreach($response['data'] as $comment)
            {
                $this->get(
                    sprintf('/api/studios/%s/comments/%s', $studio['id'], $comment['id']),
                    $headers
                )
                ->assertStatus(200);

                // update
                $this->put(
                    sprintf('/api/studios/%s/comments/%s', $studio['id'], $comment['id']),
                    [
                        'body' => str_random(100),
                    ],
                    $headers
                )
                ->assertStatus(401);

                // destroy
                $this->delete(
                    sprintf('/api/studios/%s/comments/%s', $studio['id'], $comment['id']),
                    [],
                    $headers
                )
                ->assertStatus(401);

                // 一件のみで終わり
                break;
            }
        }

        // store
        $response = $this->post(
            sprintf('/api/studios/%s/comments', $studio['id']),
            [
                'body' => str_random('200'),
            ],
            $headers
        )
        ->assertStatus(401);
    }
}