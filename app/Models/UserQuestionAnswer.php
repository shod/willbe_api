<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQuestionAnswer extends Model
{
    use HasFactory;

    public const QWESTION_STATUS_OPEN = 'open';
    public const QWESTION_STATUS_PROGRESS = 'progress';
    public const QWESTION_STATUS_CLOSE = 'close';
    public const QWESTION_STATUSES = [
        0 => self::QWESTION_STATUS_OPEN,
        1 => self::QWESTION_STATUS_PROGRESS,
        2 => self::QWESTION_STATUS_CLOSE,
    ];

    protected $fillable = [
        'user_id',
        'question_id',
        'point',
    ];
}
