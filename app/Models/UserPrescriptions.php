<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin Builder
 */
class UserPrescriptions extends Model
{
    protected $connection = 'mysql';
    protected $guarded = [];
    protected $casts = [
        'prescription_data' => 'json',
        'is_archived' => 'boolean',
    ];

    public function doctor():HasOne
    {
        return $this->hasOne(User::class,'id','doctor_id');
    }

    public function user():HasOne
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}
