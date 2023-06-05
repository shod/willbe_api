<?php

namespace App\Repositories;

use App\Interfaces\AuthRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\UserInfoRepositoryInterface;

use App\Models\User;
use Illuminate\Support\Str;

class AuthRepository implements AuthRepositoryInterface
{
  private UserRepositoryInterface $userRepository;
  private UserInfoRepositoryInterface $userInfoRepository;

  public function __construct(
    UserRepositoryInterface $userRepository,
    UserInfoRepositoryInterface $userInfoRepository,
  ) {
    $this->userRepository = $userRepository;
    $this->userInfoRepository = $userInfoRepository;
  }

  public function registerByEmail(array $userDetails): User
  {
    $name = $userDetails['name'];
    if (!$name) {
      $name = explode('@', $userDetails['email'])[0];
    }

    $role = $userDetails['role'];
    if (!$role) {
      $role = User::ROLE_CLIENT;
    }

    $plainPassword = $userDetails['password'];

    $userDetails = [
      'name' => $name,
      'role' => $role,
      'email' => $userDetails['email'],
      'password' => app('hash')->make($plainPassword),
      'uuid' => Str::uuid(),
    ];

    $user = $this->userRepository->createUser($userDetails);

    $full_name = isset($userDetails['full_name']) ? $userDetails['full_name'] : $name;
    $gender = isset($userDetails['gender']) ? $userDetails['gender'] : 'female';
    $birth_date = isset($userDetails['birth_date']) ? $userDetails['birth_date'] : date('Y-m-d');
    $phone = isset($userDetails['phone']) ? $userDetails['phone'] : 01122334455;

    $userInfoDetails = [
      'user_key' =>  $user->getUserKey(),
      'full_name' => $full_name,
      'gender' => $gender,
      'birth_date' => $birth_date,
      'phone' => (int) $phone,
    ];

    $this->userInfoRepository->createUserInfo($userInfoDetails);

    return $user;
  }
}
