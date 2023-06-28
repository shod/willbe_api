<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Interfaces\SessionRepositoryInterface;
use App\Interfaces\PageTextRepositoryInterface;

use App\Http\Requests\SessionStoreRequest;
use App\Http\Requests\SessionUpdateRequest;
use App\Models\Session;
use App\Models\User;
use App\Models\Program;
use App\Http\Controllers\Controller;
use App\Http\Resources\BaseJsonResource;
use App\Http\Resources\SessionResourceCollection;
use App\Http\Resources\SessionResource;
use App\Enums\SessionUserStatus;
use App\Exceptions\GeneralJsonException;
use App\Http\Requests\UserUuidRequest;

class SessionController extends Controller
{
    private SessionRepositoryInterface $sessionRepository;
    private PageTextRepositoryInterface $pageTextRepository;

    public function __construct(
        SessionRepositoryInterface $sessionRepository,
        PageTextRepositoryInterface $pageTextRepository
    ) {
        $this->sessionRepository = $sessionRepository;
        $this->pageTextRepository = $pageTextRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Program $program)
    {
        $user_uuid = $request->header('X-UUID');

        $sessions = $this->sessionRepository->getSessions($program->id);
        $resource = new SessionResourceCollection($sessions);

        $user = User::whereUuid($user_uuid)->first();
        if (!$user) {
            throw new GeneralJsonException('User not found!', 409);
        }
        /**
         * Add status field to each Session
         */
        $resource->map(function ($session) use ($request, $user) {
            $session->status = SessionUserStatus::TODO;
            /* TODO: сделать через user_uuid*/

            $user_id = $user->id;

            $res =  $session->user_session()->where('user_id', $user_id)->pluck('status')->first();

            if ($res != null) {
                $session->status = $res->value;
            }

            /* Get session Texts */
            if ($request->user()->role == 'coach') {
                $session->description = $this->pageTextRepository->getText('sessions', $session->id, $request->user()->role, 'description');
            }
        });
        return $resource;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSessionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SessionStoreRequest $request)
    {
        $num = $request->get('num');

        if (empty($num)) {
            $num = Session::where('program_id', $request->get('program_id'))->pluck('num')->max() + 1;
        }

        $details = [
            'program_id' => $request->get('program_id'),
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'num' => $num,
        ];

        $session = $this->sessionRepository->createSession($details);
        return new SessionResource($session);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Session  $session
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Session $session)
    {
        $user_uuid = $request->header('X-UUID');

        $status = SessionUserStatus::TODO;
        $user_uuid = $request->get('user_uuid');

        if ($user_uuid) {
            $user = User::whereUuid($user_uuid)->first();
            $res = $session->user_session()->where('user_id', $user->id)->pluck('status')->first();
            $status = $res->value;
        }

        return (new SessionResource($session))->additional(['status' => $status]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSessionRequest  $request
     * @param  \App\Models\Session  $session
     * @return \Illuminate\Http\Response
     */
    public function update(SessionUpdateRequest $request)
    {
        $session = Session::findOrFail($request->get('id'));

        $details['name'] = $request->get('name');
        $details['description'] = $request->get('description');

        $session = $this->sessionRepository->updateSession($session->id, $details);
        return new SessionResource($session);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Session  $session
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Session $session)
    {
        $this->sessionRepository->deleteSession($session->id);
        return new BaseJsonResource(new Request());
    }
}
