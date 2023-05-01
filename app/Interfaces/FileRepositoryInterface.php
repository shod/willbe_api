<?php

namespace App\Interfaces;

use Illuminate\Http\Request;
use App\Models\User;

interface FileRepositoryInterface
{
  public function upload_avatar(Request $request);
  // public function deleteUser($userId);
  // public function createUser(array $userDetails);
  // public function updateUser($userId, array $newDetails);
}
