<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin Builder
 */
class DietConsultations extends Model
{
    protected $guarded = [];
    protected $casts = [
        'data' => 'json',
    ];

    public function client(): HasOne
    {
        return $this->hasOne(User::class,'id','client_id');
    }

}
