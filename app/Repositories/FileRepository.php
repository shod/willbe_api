<?php

namespace App\Repositories;

use App\Interfaces\FileRepositoryInterface;
use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

use Illuminate\Support\Facades\Log;


class FileRepository implements FileRepositoryInterface
{

  public function upload_avatar(Request $request)
  {
    $res = false;

    $file = $request->file('file');
    $size = $file->getSize();
    $user_uuid = $request->header('X-User-Uuid');
    $date = Carbon::parse(time())->format('Ym');

    $filename = $user_uuid . '-' . time() . '.png';
    $path = 'avatar/' . $date . '/';
    $file_path = $path . $filename;

    $res = Storage::disk('public')->put($file_path, file_get_contents($file));

    if ($res) {

      $user = User::whereUuid($user_uuid)->first();
      if (!$user) {
        throw new \Exception('User not exists');
      }
      $user_info = $user->user_info();

      //Find current avatar
      $file = File::query()->where(['type' => File::FILE_AVATAR, 'object_id' => $user->id])->first();

      if ($file) {
        $file_for_delete = $file->path . $file->name;

        $res = Storage::disk('public')->delete($file_for_delete);

        $file->update(['name' => $filename, 'path' => $path, 'size' => $size, 'updated_at' => time()]);
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

  public function upload_test(Request $request)
  {
    $res = false;
    $type = 'test';
    $file = $request->file('file');

    if (!$file) {
      throw new \Exception('File not found' . $rt);
      return 'No file';
    }
    $size = $file->getSize();
    $usertest_id = $request->header('X-USERTEST-ID');
    $date = Carbon::parse(time())->format('Ym');

    $filename = $file->getFilename(); // Generate a unique, random name...
    $extension = $file->extension(); // Determine the file's extension based on the file's MIME type...
    $filename = $filename . '.' . $extension;

    $path = $type . '/' . $date . '/';
    $file_path = $path . $filename;

    $res = Storage::disk('public')->put($file_path, file_get_contents($file));

    if ($res) {

      $file = File::create([
        'type' => $type,
        'name' => $filename,
        'path' => $path,
        'size' => $size,
        'object_id' => $usertest_id
      ]);

      return $file;
    }
    return $res;
  }

  public function destroyFile($file)
  {
    Storage::delete([$file]);
  }
}
