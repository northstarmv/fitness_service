<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ExclusiveGymSchedulesClients extends Model
{
    protected $guarded = [];

    public function user():HasOne
    {
        return $this->hasOne(User::class, 'id', 'client_id');
    }

    public function gymSchedule():HasOne
    {
        return $this->hasOne(ExclusiveGymSchedules::class, 'id', 'schedule_id');
    }
}
