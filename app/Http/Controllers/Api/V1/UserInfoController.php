<?php

namespace App\Http\Controllers\API\V1;

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
            'slug' => md5($user->email),
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
    public function show($id): UserInfoResource
    {
        // @var App\Model\User $user        
        $user = $this->userRepository->getUserById($id);
        $user_info = $user->user_info();

        return new UserInfoResource($user_info);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /**
         * Check isKeyValid
         */
        //$user_info = UserInfo::find($id)->isKeyValid()

        $newDetails = [
            'full_name' => $request->full_name,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
        ];

        $res = $this->userInfoRepository->updateUserInfo($id, $newDetails);

        if ($res === true) {
            return new UserInfoResource($this->userInfoRepository->getInfoById($id));
        } else {
            return response()->json([
                'message' => $res,
                'success' => false
            ], 404);
        }
    }
}
