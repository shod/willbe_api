<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\BitwiseFlagTrait;

class UserStep extends Model
{
    use HasFactory, BitwiseFlagTrait;

    const STATUS_TODO       = 0;
    const STATUS_PGROGRESS  = 1 << 0;
    const STATUS_DONE       = 1 << 1;

    const arr_status_value = [
        self::STATUS_DONE       => 'done',
        self::STATUS_PGROGRESS  => 'progress',
        self::STATUS_TODO       => 'todo'
    ];
}