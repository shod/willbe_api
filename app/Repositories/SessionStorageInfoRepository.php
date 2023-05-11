<?php

namespace App\Repositories;

use App\Interfaces\SessionStorageInfoRepositoryInterface;
use App\Models\Session;
use App\Models\SessionStorageInfo;
use App\Models\File;

class SessionStorageInfoRepository implements SessionStorageInfoRepositoryInterface
{
  public function getInfo(Session $session, array $options = array())
  {
    $res = SessionStorageInfo::query()
      ->where(['session_id' => $session->id, 'storage' => $options['storage']])
      ->get();

    foreach ($res as $key => $value) {
      if ($value['type'] == 'file') {
        $file = File::query()->where(['type' => File::FILE_STORAGE_INFO, 'object_id' => $value->id])->first();
        $res[$key]['file'] = $file->getInfo();
      }
    }
    return $res;
  }
}
