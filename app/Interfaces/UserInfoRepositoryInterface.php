<?php

namespace App\Interfaces;

interface UserInfoRepositoryInterface
{
  public function getInfoBykey(string $userKey);
  public function getInfoById(int $id);
  public function createUserInfo(array $userInfoDetails);
  public function updateUserInfo(int $id, array $newDetails);
  //public function deleteUserInfo($userId);
}
