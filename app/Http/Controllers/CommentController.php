<?php

namespace App\Http\Controllers;

use App\Repositories\Eloquent\Models\Studio;
use App\Repositories\Eloquent\Models\Comment;
use App\Services\CommentService;
use App\Http\Resources\Resource;
use App\Http\Requests\CommentCreateRequest;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * @var \App\Services\CommentService
     */
    protected $commentService;

    public function __construct(
        CommentService $commentService
    )
    {
        $this->commentService = $commentService;
    }

    public function index(Studio $studio): Resource
    {
        return new Resource(
            $this->commentService->getAll([
                'studio_id' => $studio->id,
            ])
        );
    }

    public function show(Studio $studio, Comment $comment): Resource
    {
        return new Resource(
            $this->commentService->getCommentById(
                $comment->id,
                $studio->id
            )
        );
    }

    public function store(CommentCreateRequest $request, Studio $studio): Resource
    {
        $user = Auth::user();

        return new Resource(
            $this->commentService->createComment(
                $request->toArray(),
                $user->id,
                $studio->id
            )
        );
    }

    public function update(CommentCreateRequest $request, Studio $studio, Comment $comment): Resource
    {
        $this->authorize('update', $comment);
        
        return new Resource(
            $this->commentService->updateComment(
                $comment->id,
                $request->toArray(),
                $studio->id
            )
        );
    }

    public function destroy(Studio $studio, Comment $comment): Resource
    {
        $this->authorize('destroy', $comment);

        $this->commentService->deleteComment(
            $comment->id,
            $studio->id
        );

        return new Resource([]);
    }
}


