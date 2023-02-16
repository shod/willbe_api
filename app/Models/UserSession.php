<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\SessionUserStatus;

class UserSession extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => SessionUserStatus::class,
    ];
}
