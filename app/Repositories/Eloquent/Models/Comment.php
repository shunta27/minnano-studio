<?php

namespace App\Repositories\Eloquent\Models;

use App\Repositories\Eloquent\Models\User;
use App\Repositories\Eloquent\Models\Studio;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Comment extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];

    protected $table = 'comments';

    protected $guarded = [];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    // relations
    //
    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // query scope
    //
    public function scopeWithStudioAndUser(Builder $query): Builder
    {
        return $query->with(['studio', 'user']);
    }

    public function scopeWhereInStudioId(Builder $query, array $option): Builder
    {
        if (array_key_exists('studio_id', $option))
        {
            $query->where('studio_id', $option['studio_id']);
        }

        return $query;
    }
}
