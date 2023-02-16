<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\BitwiseFlagTrait;

class UserProgram extends Model
{
    use HasFactory;
    use BitwiseFlagTrait;

    const STATUS_NOTACTIVE     = 0;
    const STATUS_PURCHASED     = 1 << 0;
    const STATUS_ACTIVE        = 1 << 1;

    const ARR_STATUS_VALUE = [
        self::STATUS_ACTIVE      => 'active',
        self::STATUS_PURCHASED   => 'purchased',
        self::STATUS_NOTACTIVE   => 'not_active',
    ];

    protected $fillable = [
        'user_id',
        'program_id',
        'status_bit',
    ];
}
