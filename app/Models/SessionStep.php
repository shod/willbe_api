<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\UserStep;

class SessionStep extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function userstepinfo()
    {
        return $this->hasMany(UserStep::class);
    }
}
