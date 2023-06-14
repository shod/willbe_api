<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Interfaces\SessionStepRepositoryInterface;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Session;
use App\Models\SessionStep;
use App\Models\UserStep;

use App\Http\Resources;
use App\Http\Resources\SessionStepResourceCollection;
use App\Http\Resources\SessionStepResource;
use App\Http\Requests\UserUuidRequest;

class SessionStepController extends Controller
{
    private SessionStepRepositoryInterface $sessionStepRepository;

    public function __construct(SessionStepRepositoryInterface $sessionStepRepository)
    {
        $this->sessionStepRepository = $sessionStepRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Session $session, UserUuidRequest $request)
    {
        if ($user_id = $request->get('user_uuid')) {
            $user_uuid = $request->get('user_uuid');
            $user_id = User::whereUuid($user_uuid)->first()->id;
            $steps = $this->sessionStepRepository->getStepsByUser($session, $user_id);
        } else {
            $steps = $this->sessionStepRepository->getSteps($session);
        }
        return new SessionStepResourceCollection($steps);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\SessionStepRequest $request)
    {
        $num = $request->get('num');

        if (empty($num)) {
            $num = SessionStep::where('session_id', $request->get('session_id'))->pluck('num')->max() + 1;
        }

        $details = [
            'session_id' => $request->get('session_id'),
            'name' => $request->get('name'),
            'num' => $num,
        ];

        $step = $this->sessionStepRepository->createStep($details);
        return new SessionStepResource($step);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SessionStep  $sessionStep
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\SessionStepStoreRequest $request, SessionStep $sessionStep)
    {
        $details = [
            'name' => $request->get('name'),
            'num' => $request->get('num'),
        ];

        $step = $this->sessionStepRepository->updateStep($sessionStep->id, $details);
        return new SessionStepResource($step);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserStep  $userStep
     * @return \Illuminate\Http\Response
     */
    public function status_update(Request $request, UserStep $userStep)
    {

        // TODO: Сделать проверку на пользователя
        $details = [
            'status' => $request->get('status'),
        ];

        $step = $this->sessionStepRepository->updateUserStep($userStep, $details);
        return new SessionStepResource($step);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SessionStep  $sessionStep
     * @return \Illuminate\Http\Response
     */
    public function destroy(SessionStep $sessionStep)
    {
        $this->sessionStepRepository->deleteStep($sessionStep->id);
        return new Resources\BaseJsonResource(new Request());
    }
}
