<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models;

class Session extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'program_id',
        'name',
        'description',
        'num',
    ];

    public function user_session()
    {
        return $this->hasMany(UserSession::class);
    }

    public function steps()
    {
        return $this->hasMany(SessionStep::class);
    }
}
