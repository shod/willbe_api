<?php

namespace App\Http\Controllers\Api\V1;

use App\Interfaces\UserQuestionAnswerRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Resources\BaseJsonResourceCollection;
use App\Http\Resources\QuestionResource;
use App\Http\Resources\QuestionResourceCollection;


use App\Models\UserQuestionAnswer;
use App\Models\User;
use App\Models\Question;
use App\Exceptions\GeneralJsonException;
use App\Http\Requests\UserInfo\StoreUserInfoRequest;
use Illuminate\Support\Str;

class UserQuestionAnswerController extends Controller
{
    private UserQuestionAnswerRepositoryInterface $userQuestionAnswerRepository;

    public function __construct(UserQuestionAnswerRepositoryInterface $userQuestionAnswerRepository)
    {
        $this->userQuestionAnswerRepository = $userQuestionAnswerRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Question $question)
    {

        if ($user_uuid = $request->get('user_uuid')) {
            if (Str::isUuid($user_uuid)) {
                $user = User::whereUuid($user_uuid)->first();
            }
        } else {
            $user = $request->user();
        }
        $data = $this->userQuestionAnswerRepository->getList($user, $question);

        return response()->json(['data' => $data, "success" => true], 200);
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
     * @param  \App\Models\UserQuestionAnswer  $userQuestionAnswer
     * @return \Illuminate\Http\Response
     */
    public function show(UserQuestionAnswer $userQuestionAnswer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserQuestionAnswer  $userQuestionAnswer
     * @return \Illuminate\Http\Response
     */
    public function edit(UserQuestionAnswer $userQuestionAnswer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserQuestionAnswer  $userQuestionAnswer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {
        $user = $request->user();
        $point = $request->get('point');

        $data = $this->userQuestionAnswerRepository->setAnswer($user, $question, $point);

        return response()->json(['data' => $data, "success" => true], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserQuestionAnswer  $userQuestionAnswer
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserQuestionAnswer $userQuestionAnswer)
    {
        //
    }
}
