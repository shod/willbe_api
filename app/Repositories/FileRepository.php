<?php

namespace App\Repositories;

use App\Interfaces\FileRepositoryInterface;
use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class FileRepository implements FileRepositoryInterface
{

  public function upload_avatar(Request $request)
  {
    $res = false;

    $file = $request->file('file');
    $size = $file->getSize();
    $user_uuid = $request->get('user_uuid');
    $date = Carbon::parse(time())->format('Ym');

    $filename = $user_uuid . '.png';
    $path = 'avatar/' . $date . '/';
    $file_path = $path . $filename;

    $res = Storage::disk('public')->put($file_path, file_get_contents($file));

    if ($res) {

      $user = User::whereUuid($user_uuid)->first();
      $user_info = $user->user_info();

      //Find current avatar
      $file = File::query()->where(['type' => File::FILE_AVATAR, 'object_id' => $user->id])->first();

      if ($file) {
        $file->update(['size' => $size, 'updated_at' => time()]);
      } else {
        $url = Storage::url($path);

        $file = File::create([
          'type' => 'avatar',
          'name' => $filename,
          'path' => $path,
          'size' => $size,
          'object_id' => $user->id
        ]);
      }
      return $file;
    }
    return $res;
  }

  public function destroyFile($file)
  {
    Storage::delete([$file]);
  }
}
