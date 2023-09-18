<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin Builder
 */
class ExclusiveGymSchedules extends Model
{
    protected $guarded = [];

    protected $casts = [
        'client_ids' => 'array',
        'confirmed' => 'boolean',
    ];

    public function gym():HasOne
    {
        return $this->hasOne(User::class,'id','gym_id');
    }

    public function gymData():HasOne
    {
        return $this->hasOne(User_Gym::class,'user_id','gym_id');
    }

    public function clients():HasMany
    {
        return $this->hasMany(ExclusiveGymSchedulesClients::class,'schedule_id','id');
    }


}
