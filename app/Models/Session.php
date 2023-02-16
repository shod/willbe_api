<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\SessionUserStatus;

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

    public function userSessionInfo()
    {
        //return $this->hasMany(UserProgram::class);
    }

    public function steps()
    {
        return $this->hasMany(SessionStep::class);
    }

    public function getStatusByUser(int $iser_id)
    {
        return SessionUserStatus::TODO;
    }
}
