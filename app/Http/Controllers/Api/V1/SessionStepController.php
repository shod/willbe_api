<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Interfaces\SessionStepRepositoryInterface;
use App\Http\Requests\SessionStepRequest;
use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\SessionStep;

use App\Http\Resources\SessionStepResourceCollection;
use App\Http\Resources\SessionStepResource;

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
    public function index(Session $session, SessionStepRequest $request)
    {
        if ($user_id = $request->get('user_id')) {
            $steps = $this->sessionStepRepository->getStepsByUser($session, $user_id);
        } else {
            $steps = $this->sessionStepRepository->getSteps($session);
        }
        return new SessionStepResourceCollection($steps);
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
     * @param  \App\Models\SessionStep  $sessionStep
     * @return \Illuminate\Http\Response
     */
    public function show(SessionStep $sessionStep)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SessionStep  $sessionStep
     * @return \Illuminate\Http\Response
     */
    public function edit(SessionStep $sessionStep)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SessionStep  $sessionStep
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SessionStep $sessionStep)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SessionStep  $sessionStep
     * @return \Illuminate\Http\Response
     */
    public function destroy(SessionStep $sessionStep)
    {
        //
    }
}
