<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserQuestionAnswer;

class Question extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function user_question_answer()
    {
        return $this->hasMany(UserQuestionAnswer::class);
    }
}
