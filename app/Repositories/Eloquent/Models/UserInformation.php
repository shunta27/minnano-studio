<?php

namespace App\Repositories\Eloquent\Models;

use App\Repositories\Eloquent\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserInformation extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];

    protected $table = 'user_informations';

    protected $fillable = [
        'user_id',
        'name',
        'enabled',
    ];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
