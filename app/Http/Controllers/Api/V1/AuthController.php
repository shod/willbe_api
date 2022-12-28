<?php

namespace App\Http\Controllers\Api\V1;

use App\UserRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function register(Request $request)
    {

        try {

            if ($request->input('email')) {
                return $this->registerEmail($request);
            }
            /*elseif ($request->input('fb_token')) {
                return $this->registerFb($request);
            } elseif ($request->input('apple_token')) {
                return $this->registerApple($request);
            } elseif ($request->input('device_id')) {
                return $this->registerDeviceId($request);
            } */ else {
                return response()->json(['message' => 'User Registration Failed! No registration method'], 409);
            }
        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'User Registration Failed! Message:' . $e->getMessage()], 409);
        }
    }

    /** Registration by email */
    public function registerEmail(Request $request)
    {
        //validate incoming request 
        $this->validate($request, [
            'name' => 'string',
            'email' => '|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        try {
            $name = $request->input('name');
            if (!$name) {
                $name = explode('@', $request->input('email'))[0];
            }

            $user = new User;
            $user->name = $name;
            $user->email = $request->input('email');
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);

            $user->save();

            //return successful response
            return response()->json(['user' => $user, 'message' => 'CREATED'], 201);
        } catch (\Exception $e) {
            //return error message
            dd($e->getMessage());
            return response()->json(['message' => 'User Registration Failed!'], 409);
        }
    }

    public function login(Request $request)
    {

        /*Minutes*/
        //Auth::factory()->setTTL(60*24*180);
        try {

            if ($request->input('email')) {
                return $this->loginEmail($request);
            } /*elseif ($request->input('fb_token')) {
                return $this->loginFb($request);
            } elseif ($request->input('apple_token')) {
                return $this->loginApple($request);
            } elseif ($request->input('device_id')) {
                return $this->loginDeviceId($request);
            } */ else {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'Unauthorized! Message:' . $e->getMessage()], 409);
        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */
    public function loginEmail(Request $request)
    {

        //validate incoming request 
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        //$credentials = $request->only(['email', 'password']);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $user->createToken($request->device_name)->plainTextToken;


        //$token = auth()->setTTL(7200)->attempt($credentials);
        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }
}
