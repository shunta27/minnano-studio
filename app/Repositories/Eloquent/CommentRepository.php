<?php

namespace App\Repositories\Eloquent;

use App\Repositories\CommentRepositoryInterface;
use App\Repositories\Eloquent\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use stdClass;

class CommentRepository implements CommentRepositoryInterface
{
    protected $comment;
    
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function all(array $option = []): Collection
    {
        return $this->comment
            ->withStudioAndUser()
            ->whereInStudioId($option)
            ->get();
    }

    public function paginate(array $option = []): LengthAwarePaginator
    {
        return $this->comment
            ->withStudioAndUser()
            ->whereInStudioId($option)
            ->paginate(10);
    }

    public function get(int $id, array $option = []): Model
    {
        return $this->comment
            ->withStudioAndUser()
            ->whereInStudioId($option)
            ->findOrFail($id);
    }

    public function create(stdClass $object): Model
    {
        $comment = $this->comment->newInstance([
            'user_id' => $object->user_id,
            'studio_id' => $object->studio_id,
            'body' => $object->body,
        ]);

        $comment->save();

        return $this->get($comment->id);
    }

    public function update(int $id, stdClass $object, array $option = []): Model
    {
        $comment = $this->get($id, $option);

        $comment->body = $object->body;

        $comment->save();

        return $this->get($comment->id);
    }

    public function delete(int $id, array $option = []): bool
    {
        $comment = $this->get($id, $option);

        return $comment->delete();
    }
}
