<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Interfaces\TestRepositoryInterface;
use App\Interfaces\UserTestRepositoryInterface;
use App\Models\Test;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Resources\TestResource;
use App\Http\Resources\TestResourceCollection;
use App\Http\Resources\UserTestResourceCollection;
use App\Http\Requests\UserUuidRequest;
use App\Exceptions\GeneralJsonException;

class TestController extends Controller
{
    private TestRepositoryInterface $testRepository;
    private UserTestRepositoryInterface $userTestRepository;

    public function __construct(TestRepositoryInterface $testRepository, UserTestRepositoryInterface $userTestRepository)
    {
        $this->testRepository = $testRepository;
        $this->userTestRepository = $userTestRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $user_uuid)
    {
        $user = User::whereUuid($user_uuid)->first();
        if (!$user) {
            throw new GeneralJsonException('User is not found.', 409);
        }

        $tests = $this->userTestRepository->getUserTests($user->id);
        return new UserTestResourceCollection($tests);

        //$tests = $this->testRepository->getTests();
        return new TestResourceCollection($tests);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_uuid = $request->get('user_uuid');
        $user_id = User::whereUuid($user_uuid)->first()->id;
        $details = [
            'user_id' => $user_id,
            'test_id' => $request->get('test_id'),
            'labname' => $request->get('labname'),
            'status' => $request->get('status'),
        ];

        $test = $this->userTestRepository->createUserTest($details);
        return new TestResource($test);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Test  $test
     * @return \Illuminate\Http\Response
     */
    public function show(Test $test)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Test  $test
     * @return \Illuminate\Http\Response
     */
    public function edit(Test $test)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Test  $test
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Test $test)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Test  $test
     * @return \Illuminate\Http\Response
     */
    public function destroy(Test $test)
    {
        //
    }
}
