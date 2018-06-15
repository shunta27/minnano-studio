<?php

namespace App\Policies;

use App\Policies\Policy;
use App\Repositories\Eloquent\Models\User;
use App\Repositories\Eloquent\Models\Comment;

class CommentPolicy extends Policy
{
    public function index(User $user)
    {
    }

    public function show(User $user, Comment $comment)
    {
    }

    public function store(User $user)
    {
    }

    public function update(User $user, Comment $comment)
    {
        return $user->id == $comment->user_id;
    }

    public function destroy(User $user, Comment $comment)
    {
        return $user->id == $comment->user_id;
    }
}
