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
    public function index(Request $request)
    {


        $user_uuid = $request->get('user_uuid');

        if ($user_uuid = $request->get('user_uuid')) {
            if (Str::isUuid($user_uuid)) {
                $user_id = User::whereUuid($user_uuid)->first()->id;
                $tests = $this->userTestRepository->getUserTests($user_id);
                return new UserTestResourceCollection($tests);
            }
        } else {
            $tests = $this->testRepository->getTests();
            return new TestResourceCollection($tests);
        }
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