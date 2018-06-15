<?php

namespace App\Repositories\Eloquent\Models;

use App\Repositories\Eloquent\Models\User;
use App\Repositories\Eloquent\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;

class Studio extends Model
{
    use SoftDeletes;
    use SpatialTrait;
    
    protected $dates = ['deleted_at'];

    protected $table = 'studios';

    protected $guarded = [];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $spatialFields = [
        'location',
    ];

    // relations
    //
    
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    // query scope
    //
    public function scopeWithComments(Builder $query): Builder
    {
        return $query->with('comments');
    }

    public function scopeAsSearch(Builder $query, array $option): Builder
    {
        if (array_key_exists('q', $option))
        {
            $q = $option['q'];
            
            // TODO: 検索条件
        }

        return $query;
    }

}
