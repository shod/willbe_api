<?php

namespace App\Http\Controllers\Api\V1;

use App\Interfaces\UserTestRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Models\UserTest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources;
use App\Http\Resources\UserTestResource;
use App\Http\Resources\UserTestResourceCollection;
use App\Http\Requests\UserUuidRequest;
use App\Exceptions\GeneralJsonException;
use Illuminate\Support\Facades\Log;

class UserTestController extends Controller
{
    private UserTestRepositoryInterface $userTestRepository;

    public function __construct(UserTestRepositoryInterface $userTestRepository)
    {
        $this->userTestRepository = $userTestRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_uuid = $request->header('X-UUID');
        $user = User::whereUuid($user_uuid)->first();
        if (!$user) {
            throw new GeneralJsonException('User is not found.', 409);
        }

        $test = $this->userTestRepository->getUserTests($user->id);

        return new UserTestResourceCollection($test);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_uuid = $request->get('uuid');
        $user = User::whereUuid($user_uuid)->first();

        if (!$user) {
            throw new GeneralJsonException('User is not found.', 409);
        }

        $details = [
            'user_id' => $user->id,
            'test_id' => $request->get('test_id'),
            'labname' => $request->get('labname'),
            'status' => $request->get('status'),
        ];

        $test = $this->userTestRepository->createUserTest($details);
        return new UserTestResource($test);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TestUser  $testUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserTest $userTest)
    {
        $newDetails = [
            'status' => $request->get('status'),
            'labname' => $request->get('labname'),
            'test_id' => $request->get('testid')
        ];

        // Delete null properties
        $newDetails = array_filter($newDetails, function ($value) {
            return $value !== null;
        });

        $res = $this->userTestRepository->updateUserTest($userTest->id, $newDetails);
        $test = null;

        if ($res) {
            $test = $this->userTestRepository->getUserTest($userTest->id);
        }
        return new UserTestResource($test);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TestUser  $testUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserTest $userTest)
    {
        $this->userTestRepository->deleteUserTest($userTest->id);
        //TODO: сделать удаление файлов
        return new Resources\BaseJsonResource(new Request());
    }
}
