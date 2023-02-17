<?php

namespace App\Http\Controllers\API\V1;

use App\Interfaces\UserRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function show(Request $request): UserResource
    {
        $userId = $request->route('id');

        return new UserResource($this->userRepository->getUserById($userId));
    }
}
