<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Interfaces\SessionStepRepositoryInterface;
use App\Interfaces\SessionRepositoryInterface;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Session;
use App\Models\SessionStep;
use App\Models\UserStep;

use App\Http\Resources;
use App\Http\Resources\SessionStepResourceCollection;
use App\Http\Resources\SessionStepResource;
use App\Exceptions\GeneralJsonException;

use App\Enums\SessionUserStatus;

class SessionStepController extends Controller
{
    private SessionStepRepositoryInterface $sessionStepRepository;
    private SessionRepositoryInterface $sessionRepository;

    public function __construct(
        SessionStepRepositoryInterface $sessionStepRepository,
        SessionRepositoryInterface $sessionRepository
    ) {
        $this->sessionStepRepository = $sessionStepRepository;
        $this->sessionRepository = $sessionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Session $session, Request $request)
    {
        $user_uuid = $request->header('X-UUID');
        $user = User::whereUuid($user_uuid)->first();
        if (!$user) {
            throw new GeneralJsonException('User is not found.', 409);
        }

        $steps = $this->sessionStepRepository->getStepsByUser($session, $user->id);

        //$steps = $this->sessionStepRepository->getSteps($session);
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
     * TODO: сделать проверку Request user_uuid
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserStep  $userStep
     * @return \Illuminate\Http\Response
     */
    public function status_update(Request $request, int $stepId)
    {
        $user_uuid = $request->get('uuid');

        $user = User::whereUuid($user_uuid)->first();

        $userStep = UserStep::firstOrCreate([
            'user_id' => $user->id,
            'session_step_id' => $stepId,
        ], [
            'user_id' => $user->id,
            'session_step_id' => $stepId,
            'status_bit'    => 0
        ]);

        $details = [
            'status' => $request->get('status'),
        ];

        $step = $this->sessionStepRepository->updateUserStep($userStep, $details);

        $sessionId = SessionStep::find($stepId)->session_id;
        $user_session_status = $this->sessionRepository->updateSessionStatus($sessionId, $user->id);

        if ($user_session_status == SessionUserStatus::DONE->value) {
            $this->sessionRepository->sessionNextOpen($sessionId, $user->id);
        }


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
