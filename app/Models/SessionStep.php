<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\UserStep;

class SessionStep extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'session_id',
        'name',
        'num'
    ];

    public function userstepinfo()
    {
        return $this->hasMany(UserStep::class);
    }
}
