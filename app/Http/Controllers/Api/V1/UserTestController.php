<?php

namespace App\Http\Controllers\Api\V1;

use App\Interfaces\UserTestRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Models\UserTest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Resources\UserTestResource;
use App\Http\Resources\UserTestResourceCollection;

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
        $user_uuid = $request->get('user_uuid');

        if ($user_uuid = $request->get('user_uuid')) {
            if (Str::isUuid($user_uuid)) {
                $user_id = User::whereUuid($user_uuid)->first()->id;
                $targets = $this->userTestRepository->getUserTests($user_id);
            }
        }

        return new UserTestResourceCollection($targets);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TestUser  $testUser
     * @return \Illuminate\Http\Response
     */
    public function show(TestUser $testUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TestUser  $testUser
     * @return \Illuminate\Http\Response
     */
    public function edit(TestUser $testUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TestUser  $testUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TestUser $testUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TestUser  $testUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(TestUser $testUser)
    {
        //
    }
}
