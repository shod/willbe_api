<?php

namespace App\Http\Library;

use App\Models\File;

class UserHelpers
{
  public static function getDefaultAvatar(): File
  {
    $default_avatar = File::make(['name' => 'avatar.jpg', 'path' => "avatar/"]);
    return $default_avatar;
  }
}
