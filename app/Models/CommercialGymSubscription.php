<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
/**
 * @mixin Builder
 */
class CommercialGymSubscription extends Model
{
    protected $connection = 'mysql';
    protected $guarded = [];

    public function user():HasOne
    {
        return $this->hasOne(User::class, 'id','user_id');
    }

    public function gym():HasOne
    {
        return $this->hasOne(User::class, 'id','gym_id');
    }

    public function gymData():HasOne
    {
        return $this->hasOne(User_Gym::class,'user_id','gym_id');
    }
}
