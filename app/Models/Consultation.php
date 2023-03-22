<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\BitwiseFlagTrait;

class Consultation extends Model
{
    use HasFactory, BitwiseFlagTrait;

    const STATUS_WAIT       = 0;
    const STATUS_PAYED      = 1 << 0;

    const ARR_STATUS_VALUE = [
        self::STATUS_WAIT       => 'wait',
        self::STATUS_PAYED      => 'payed',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'coach_id',
        'user_id',
        'description',
        'meet_time',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function coach()
    {
        //return $this->hasBel(UserSession::class);
    }
}
