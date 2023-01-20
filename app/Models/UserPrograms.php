<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\BitwiseFlagTrait;

class UserPrograms extends Model
{
    use HasFactory;
    use BitwiseFlagTrait;

    const STATUS_BUYED         = 1 << 0;

    protected $fillable = [
        'user_id',
        'progrram_id',
        'status_bit',
    ];
}
