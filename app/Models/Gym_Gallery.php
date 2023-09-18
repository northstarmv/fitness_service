<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gym_Gallery extends Model
{
    protected $connection = 'accounts';
    protected $primaryKey = 'id';
    protected $guarded = [];


}
