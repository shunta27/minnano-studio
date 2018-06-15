<?php
namespace Tests;

use App\Repositories\Eloquent\Models\Studio;
use App\Repositories\Eloquent\Models\Comment;
use Tests\ApiUsingTokenTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApiTestCase extends ApiUsingTokenTestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    protected $studiosHasComments = [];

    public function setUp()
    {
        parent::setUp();

        // コメントありスタジオ
        factory(Studio::class, 1)
            ->create()
            ->each(function (Studio $studio) {
                factory(Comment::class, 2)->create([
                    'user_id' => $this->system_user->id,
                    'studio_id' => $studio->id,
                    'body' => str_random('100'),
                ]);
                factory(Comment::class, 2)->create([
                    'user_id' => $this->admin_user->id,
                    'studio_id' => $studio->id,
                    'body' => str_random('100'),
                ]);
                factory(Comment::class, 6)->create([
                    'user_id' => $this->user->id,
                    'studio_id' => $studio->id,
                    'body' => str_random('100'),
                ]);

                $this->studiosHasComments[] = $studio;
            });

        // コメントなしスタジオ
        factory(Studio::class, 10)
            ->create();
    }
}