<?php

namespace App\Http\Controllers\API\V1;

use App\Interfaces\ClientUserRepositoryInterface;
use App\Http\Controllers\Controller;

use App\Models\ClientUsers;
use App\Models\Users;
use Illuminate\Http\Request;

class ClientUserController extends Controller
{
    private ClientUserRepositoryInterface $clientUserRepository;

    public function __construct(ClientUserRepositoryInterface $clientUserRepository)
    {
        $this->clientUserRepository = $clientUserRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $data = $this->clientUserRepository->getList($user);
        dd($data);
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
     * @param  \App\Models\ClientUsers  $clientUsers
     * @return \Illuminate\Http\Response
     */
    public function show(ClientUsers $clientUsers)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ClientUsers  $clientUsers
     * @return \Illuminate\Http\Response
     */
    public function edit(ClientUsers $clientUsers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClientUsers  $clientUsers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClientUsers $clientUsers)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ClientUsers  $clientUsers
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClientUsers $clientUsers)
    {
        //
    }
}
