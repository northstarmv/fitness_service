<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
/**
 * @mixin Builder
 */
class User_Gym extends Model
{
    protected $connection = 'accounts';
    protected $guarded = [];
    protected $primaryKey = 'user_id';
    protected $casts = [
        'gym_facilities' => 'json',
    ];

}
