<?php

namespace App\Services;

use App\Services\Service;
use App\Repositories\CommentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use stdClass;

class CommentService extends Service
{
    protected $commentRepository;

    public function __construct(
        CommentRepositoryInterface $commentRepository
    )
    {
        $this->commentRepository = $commentRepository;
    }

    public function getAll(array $option = []): Collection
    {
        return $this->commentRepository->all($option);
    }

    public function getCommentById(int $id, int $studio_id): Model
    {
        return $this->commentRepository->get($id, ['studio_id' => $studio_id]);
    }

    public function createComment(array $comment, int $user_id, int $studio_id): Model
    {
        $object = new stdClass();
        $object->user_id = $user_id;
        $object->studio_id = $studio_id;
        $object->body = $comment['body'];

        return $this->commentRepository->create($object);
    }

    public function updateComment(int $id, array $comment, int $studio_id): Model
    {
        $object = new stdClass();
        $object->body = $comment['body'];
        
        return $this->commentRepository->update($id, $object, ['studio_id' => $studio_id]);
    }

    public function deleteComment(int $id, int $studio_id): bool
    {
        return $this->commentRepository->delete($id, ['studio_id' => $studio_id]);
    }
}