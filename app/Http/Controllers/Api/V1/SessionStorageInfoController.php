<?php

namespace App\Http\Controllers\Api\V1;

use App\Interfaces\SessionStorageInfoRepositoryInterface;
use App\Models\Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SessionStorageInfoResourceCollection;
use Illuminate\Support\Facades\Log;

class SessionStorageInfoController extends Controller
{
    private SessionStorageInfoRepositoryInterface $sessionStorageInfoRepository;

    public function __construct(SessionStorageInfoRepositoryInterface $sessionStorageInfoRepository)
    {
        $this->sessionStorageInfoRepository = $sessionStorageInfoRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Session $session)
    {
        $options = $request->all();
        $options['storage'] = $request->get('storage');
        $options['role'] = $request->user()->role;
        Log::info('---SessionStorageInfoController:');
        Log::info('Session:' . $session->id);
        Log::info($options);
        $infos_list = $this->sessionStorageInfoRepository->getInfo($session, $options);
        Log::info($infos_list);
        return new SessionStorageInfoResourceCollection($infos_list);
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
     * @param  \App\Models\SessionInfoStorage  $sessionInfoStorage
     * @return \Illuminate\Http\Response
     */
    public function show(SessionInfoStorage $sessionInfoStorage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SessionInfoStorage  $sessionInfoStorage
     * @return \Illuminate\Http\Response
     */
    public function edit(SessionInfoStorage $sessionInfoStorage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SessionInfoStorage  $sessionInfoStorage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SessionInfoStorage $sessionInfoStorage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SessionInfoStorage  $sessionInfoStorage
     * @return \Illuminate\Http\Response
     */
    public function destroy(SessionInfoStorage $sessionInfoStorage)
    {
        //
    }
}
