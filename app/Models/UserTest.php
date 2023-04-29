<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'test_id',
        'labname',
        'status',
    ];

    /**
     * Get the user associated with the Consultation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function test()
    {
        return $this->hasOne(Test::class, 'id', 'test_id');
    }

    public function program()
    {
        return $this->hasOne(Program::class, 'id', 'program_id');
    }
}
