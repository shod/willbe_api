<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserProgram;
use App\Models\Session;

class Program extends Model
{
    use HasFactory;

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'cost',
    ];

    public function userProgramInfo()
    {
        return $this->hasMany(UserProgram::class);
    }

    public function session()
    {
        return $this->hasMany(Session::class);
    }
}
