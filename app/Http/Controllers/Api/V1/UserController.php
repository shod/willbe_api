<?php

namespace App\Http\Controllers\API\V1;

use App\Exceptions\GeneralJsonException;
use App\Interfaces\UserRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;

class UserController extends Controller
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function show(Request $request): UserResource
    {
        $result = Str::of($request->route('uuid'))->isUuid();

        if (!$result) {
            throw new GeneralJsonException('Not valid uuid', 409);
        }

        $user = User::where('uuid', $request->route('uuid'))->first();

        if (!$user) {
            throw new GeneralJsonException('User is not found.', 409);
        }

        return new UserResource($this->userRepository->getUserById($user->id));
    }
}
