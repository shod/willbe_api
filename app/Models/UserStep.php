<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\SessionStep;
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

    protected $fillable = [
        'user_id',
        'session_step_id',
        'status_bit',
    ];

    public function session_step(): BelongsTo
    {
        return $this->belongsTo(SessionStep::class);
    }
}
