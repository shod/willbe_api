<?php

namespace App\Http\Library;

use App\Models\File;
use App\Models\UserQuestionAnswer;

class UserHelpers
{
  public static function getDefaultAvatar(): File
  {
    $default_avatar = File::make(['name' => 'avatar.jpg', 'path' => "avatar/"]);
    return $default_avatar;
  }

  public static function getStatuses(int $userId): array
  {
    //Get questionnare_consultation_status
    $qid = config("questionnaire.cosultation_id");
    $questionniare_info = UserQuestionAnswer::where(['user_id' => $userId, 'question_id' => $qid])->first();
    $res_data = ['questionnare_consultation_status' => UserQuestionAnswer::QWESTION_STATUSES[$questionniare_info->point]];
    return $res_data;
  }
}
