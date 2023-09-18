<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin Builder
 */
class UserWorkoutPlans extends Model
{
    protected $guarded = [];
    protected $connection = 'mysql';
    protected $casts = [
        'workout_plan' => 'json',
        'feedback' => 'json',
        'completed' => 'boolean',
        'finished' => 'boolean',
    ];

    public function client():HasOne
    {
        return $this->hasOne(User::class, 'id','user_id');
    }

    public function trainer():HasOne
    {
        return $this->hasOne(User::class, 'id','trainer_id');
    }
}
