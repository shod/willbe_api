<?php

namespace App\Http\Controllers\API\V1;

use App\Exceptions\GeneralJsonException;
use App\Interfaces\UserInfoRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserInfoResource;

use App\Http\Requests\UserInfo\StoreUserInfoRequest;
use App\Models\User;
use App\Models\UserInfo;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserInfoController extends Controller
{
    private UserInfoRepositoryInterface $userInfoRepository;
    private UserRepositoryInterface $UserRepository;

    public function __construct(UserInfoRepositoryInterface $userInfoRepository, UserRepositoryInterface $userRepository)
    {
        $this->userInfoRepository = $userInfoRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $UserInfoDetails = [
            'user_key' => $user->getUserKey(),
            'full_name' => $request->full_name,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
        ];

        $user = $this->userInfoRepository->createUserInfo($UserInfoDetails);
        return new UserInfoResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($uuid): UserInfoResource
    {
        // @var App\Model\User $user        
        $user = User::whereUuid($uuid)->first();

        $user_data = $this->userRepository->getUserById($user->id);
        $user_info = $user_data->user_info();

        $coach = $user->coach()->first();
        $user_info->coach = [];

        if ($coach) {
            $user_info->coach = ['uuid' => $coach->uuid, 'full_name' => $coach->name];
        }

        return new UserInfoResource($user_info);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uuid)
    {
        $user_info_id = 0;
        /**
         * Check uuid
         */
        $result = Str::of($uuid)->isUuid();

        if (!$result) {
            throw new GeneralJsonException('Not valid uuid', 409);
        }

        $user = User::whereUuid($uuid)->first();
        $user_info_id = $user->user_info()->id;

        $newDetails = [
            'full_name' => $request->full_name,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'phone' => $request->phone,
        ];

        $newDetails = array_filter($newDetails, function ($value) {
            return $value !== null;
        });

        $res = $this->userInfoRepository->updateUserInfo($user_info_id, $newDetails);

        if ($res === true) {
            return new UserInfoResource($this->userInfoRepository->getInfoById($user_info_id));
        } else {
            return response()->json([
                'message' => $res,
                'success' => false
            ], 404);
        }
    }
}
