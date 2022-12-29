<?php

namespace App\Http\Controllers\Api\V1;

use App\Interfaces\UserRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\LoginRequest;

use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

use Illuminate\Http\Request;
use App\Models\User;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    private UserRepositoryInterface $userRepository;

    /**
     * Store a new user.
     *
     * @param  App\Http\Requests\AuthRequest  $request
     * @return Response
     */
    public function register(AuthRequest $request): UserResource
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

    /** Registration by email    
     * 
     *
     * @param  App\Http\Requests\AuthRequest  $request
     * @return Illuminate\Http\Response
     */
    public function registerEmail(AuthRequest $request): UserResource
    {
        try {
            $name = $request->input('name');
            if (!$name) {
                $name = explode('@', $request->input('email'))[0];
            }

            $role = $request->input('role');
            if (!$role) {
                $role = 3;
            }

            $user = new User;
            $user->name = $name;
            $user->email = $request->input('email');
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);
            $user->role = $role;

            $user->save();
            //return successful response            
            return new UserResource($user);
        } catch (\Exception $e) {
            //return error message            
            return response()->json(['message' => 'User Registration Failed!', 'details' => $e->getMessage()], 409);
        }
    }

    public function login(LoginRequest $request)
    {

        /*Minutes*/
        //Auth::factory()->setTTL(60*24*180);
        try {

            if ($request->input('email')) {
                $access_token = $this->loginEmail($request);
            } /*elseif ($request->input('fb_token')) {
                return $this->loginFb($request);
            } elseif ($request->input('apple_token')) {
                return $this->loginApple($request);
            } elseif ($request->input('device_id')) {
                return $this->loginDeviceId($request);
            } */ else {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            if (!empty($access_token)) {
                return new AuthResource($access_token);
            } else {
                abort(404, 'Not authorized (not logged in)');
            }
        } catch (\Exception $e) {
            //return error message
            //return new AuthResource($access_token);                        
            return response()->json(['message' => 'Unauthorized! Message:' . $e->getMessage()], 409);
        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */
    public function loginEmail(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $access_token = $user->createToken('role:' . $user->role);

        return $access_token;
    }

    /**
     * Delete token
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }
}
