<?php

namespace App\Repositories;

use App\Interfaces\UserInfoRepositoryInterface;
use App\Models\UserInfo;

class UserInfoRepository implements UserInfoRepositoryInterface
{
  public function createUserInfo(array $UserInfoDetails)
  {
    return UserInfo::create($UserInfoDetails);
  }

  public function getInfoBykey(string $userKey)
  {
    return UserInfo::where('user_key', $userKey)->firstOrFail();
  }

  public function getInfoById(int $userId)
  {
    return UserInfo::findOrFail($userId);
  }

  public function updateUserInfo(int $id, array $newDetails)
  {
    try {
      UserInfo::findOrFail($id)
        ->update($newDetails);
      return true;
    } catch (\Exception $e) {
      return $e;
    }
  }
}
