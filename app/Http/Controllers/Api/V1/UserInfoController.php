<?php

namespace App\Http\Controllers\API\V1;

use App\Exceptions\GeneralJsonException;
use App\Interfaces\UserInfoRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\FileRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserInfoResource;

use App\Http\Requests\UserInfo\StoreUserInfoRequest;
use App\Models\User;
use App\Models\File;
use App\Http\Library\UserHelpers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserInfoController extends Controller
{
    private UserInfoRepositoryInterface $userInfoRepository;
    private UserRepositoryInterface $userRepository;
    private FileRepositoryInterface $fileRepositoryInterface;

    public function __construct(
        UserInfoRepositoryInterface $userInfoRepository,
        UserRepositoryInterface $userRepository,
        FileRepositoryInterface $fileRepositoryInterface
    ) {
        $this->userInfoRepository = $userInfoRepository;
        $this->userRepository = $userRepository;
        $this->fileRepositoryInterface = $fileRepositoryInterface;
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

        $user_info = $user->user_info();

        $UserInfoDetails = [
            'user_key' => $user->getUserKey(),
            'full_name' => $request->full_name,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
        ];

        if (!$user_info) {
            $user_info = $this->userInfoRepository->createUserInfo($UserInfoDetails);
        } else {
            $res = $this->userInfoRepository->updateUserInfo($user_info->id, $UserInfoDetails);
            if ($res) {
                $user_info = $user->user_info();
            }
        }

        return new UserInfoResource($user_info);
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
        if (!$user) {
            throw new GeneralJsonException('User is not found.', 409);
        }

        $user_data = $this->userRepository->getUserById($user->id);
        $user_info = $user_data->user_info();
        $user_info['email'] = $user['email'];

        // TODO: Сделать получение аватара через метод репозитория        
        $files = $this->fileRepositoryInterface->getFileInfo(File::FILE_AVATAR, $user->id);

        $user_info['avatar'] = [];
        if (!$files->isEmpty()) {
            $user_info['avatar'] = $files[0]->getInfo();
        } else {
            $default_avatar = UserHelpers::getDefaultAvatar();
            $user_info['avatar'] = $default_avatar->getInfo();
        }


        $coach = $user->coach()->first();
        $user_info->coach = [];

        if ($coach) {
            //$user_info->coach = ['uuid' => $coach->uuid, 'full_name' => $coach->name];
            $coach_data = $this->userRepository->getUserById($coach->id);
            $user_info->coach = $coach_data->user_info();
            $user_info->coach['email'] = $coach['email'];
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
    public function update(Request $request)
    {
        $user_info_id = 0;
        $user_key = null;

        $user = $request->user();
        $user_info = $user->user_info();
        $user_key = $user->getUserKey();
        $user->setEmailVerification();

        if ($user_info === null) {
            return response()->json([
                'message' => 'User info not found!',
                'success' => false
            ], 404);
        }

        $user_info_id = $user_info->id;

        if ($request->email) {
            $newDetails = [
                'email' => $request->email,
            ];

            // TODO: Add this DB::trunsaction
            $res = $this->userRepository->updateUser($user->id, $newDetails);

            if ($res === true) {
                $user = $this->userRepository->getUserById($user->id);
                $user_key = $user->getUserKey();
                //Update user_key fror relation with User
                $newDetails = [
                    'user_key' => $user_key,
                ];
                $this->userInfoRepository->updateUserInfo($user_info_id, $newDetails);
            } else {
                return response()->json([
                    'message' => $res,
                    'success' => false
                ], 404);
            }
        }

        $newDetails = [
            'full_name' => $request->full_name,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'phone' => $request->phone,
            'user_key' => $user_key,
        ];

        // Delete null properties
        $newDetails = array_filter($newDetails, function ($value) {
            return $value !== null;
        });

        if (key_exists('phone', $newDetails)) {
            $user_info->is_phone_verified = 0;
            $user_info->save();
        }

        $res = $this->userInfoRepository->updateUserInfo($user_info_id, $newDetails);

        if ($res === true) {
            $user_info = $this->userInfoRepository->getInfoById($user_info_id);
            $user_info['email'] = $user->email;
            return new UserInfoResource($user_info);
        } else {
            return response()->json([
                'message' => $res,
                'success' => false
            ], 404);
        }
    }
}
