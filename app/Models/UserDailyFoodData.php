<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class UserDailyFoodData extends Model
{
    protected $guarded = [];
    protected $connection = 'mysql';
    protected $casts = [
        'food_data' => 'json',
        'macro_profile' => 'json',
    ];
}
