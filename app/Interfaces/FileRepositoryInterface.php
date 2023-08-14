<?php

namespace App\Interfaces;

use Illuminate\Http\Request;
use App\Models\User;

interface FileRepositoryInterface
{
  public function upload_avatar(Request $request);
  public function upload_test(Request $request);
  public function getFileInfo(string $type, int $object_id);
  // public function deleteUser($userId);
  // public function createUser(array $userDetails);
  // public function updateUser($userId, array $newDetails);
}
