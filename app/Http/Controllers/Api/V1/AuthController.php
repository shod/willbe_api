<?php

namespace App\Http\Controllers\Api\V1;

use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\UserInfoRepositoryInterface;

use App\Cache;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;

use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\User;
use App\Models\PasswordReset;

use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

use Laravel\Sanctum\PersonalAccessToken;

use App\Exceptions\GeneralJsonException;
use Illuminate\Auth\Events\PasswordReset as EventsPasswordReset;

class AuthController extends Controller
{
    private UserRepositoryInterface $userRepository;
    private UserInfoRepositoryInterface $userInfoRepository;

    public function __construct(UserRepositoryInterface $userRepository, UserInfoRepositoryInterface $userInfoRepository)
    {
        $this->userRepository = $userRepository;
        $this->userInfoRepository = $userInfoRepository;
    }

    /**
     * Store a new user.
     *
     * @param  App\Http\Requests\AuthRequest  $request
     * @return Response
     */
    public function register(AuthRequest $request)
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

            $plainPassword = $request->input('password');

            $userDetails = [
                'name' => $name,
                'role' => $role,
                'email' => $request->input('email'),
                'password' => app('hash')->make($plainPassword),
                'full_name' => $request->input('full_name'),
                'gender' => $request->input('gender'),
                'birth_date' => $request->input('birth_date'),
                'phone' => $request->input('phone'),
            ];

            $user = $this->userRepository->createUser($userDetails);

            $userInfoDetails = array_merge($userDetails, [
                'user_key' => $user->getUserKey(),
                'slug' => md5($user->email),
            ]);

            $this->userInfoRepository->createUserInfo($userInfoDetails);

            return new UserResource($user);
        } catch (\Exception $e) {
            //return error message            
            throw new GeneralJsonException('User Registration Failed!' . '. Details=' . $e->getMessage(), 409);
            //return response()->json(['message' => 'User Registration Failed!', 'details' => $e->getMessage()], 409);
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

        $access_token = $user->createToken('role:' . $user->role, ['*']);

        return $access_token;
    }

    /** Check 2fa verification*/
    public function get_check_2fa(Request $request)
    {
        $user = $request->user();

        $user_info = $this->userInfoRepository->getInfoBykey($user->getUserKey());
        $code = Cache::get_2fa_code($user->id);
        return response()->json(['message' => 'Code was sended', 'success' => true], 200);
    }

    /** Check 2fa verification*/
    public function post_check_2fa(Request $request)
    {
        $user = $request->user();
        $code = $request->get('code');
        //$this->update_token_abilities($user, '["*"]');

        $user_info = $this->userInfoRepository->getInfoBykey($user->getUserKey());

        if ($code == 3113) {
            return response()->json(['message' => 'Authorized!', 'success' => true], 200);
        } else {
            throw new GeneralJsonException('User Authorisation Failed!', 409);
        }
    }

    /**
     * Delete token
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }

    /** 
     * Update token abilities 
     */
    private function update_token_abilities(User $user, string $abilities)
    {
        $p_access_token = PersonalAccessToken::find($user->currentAccessToken()->id);
        $p_access_token->abilities = $abilities;
        $p_access_token->save();
    }

    /**
     * Forgot a password
     * @param ForgotPasswordRequest $request
     * @throws ValidationException
     */
    public function forgot_password(Request $request)
    {
        /** @var User $user */
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw new GeneralJsonException('No Record Found. Incorrect Email Address Provided', 404);
        }

        //Generate random Token
        $reset_pssword_token = random_int(1000, 9999);

        if (!$user_pass_reset = PasswordReset::where('email', $user->email)->first()) {
            PasswordReset::create([
                'email' => $user->email,
                'token' => $reset_pssword_token,
            ]);
        } else {
            $user_pass_reset->update(['token' => $reset_pssword_token]);
        }

        //TODO: Need to send email notification        

        return response()->json(['message' => 'A code has been Senе to your Email Address.', 'success' => true], 200);
    }


    /**
     * Perform a password reset request
     * @param ResetPasswordRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function reset_password(ResetPasswordRequest $request)
    {
        $user = User::where('email', $request->email)
            ->first();

        if (!$user) {
            throw new GeneralJsonException('No Record Found. Incorrect Email Address Provided', 404);
        }

        $resetRequest = PasswordReset::where('email', $user->email)->first();

        if (!$resetRequest || $resetRequest->token != $request->token) {
            throw new GeneralJsonException('An Error Occured. Please Try again. Token mismatch', 400);
        }

        $plainPassword = $request->input('password');

        //Update User password
        $user->fill([
            'password' => app('hash')->make($plainPassword),
        ]);
        $user->save();

        // Delete all previous tokens
        $user->tokens()->delete();

        $resetRequest->delete();

        //Get Token for Authenticated User
        $access_token = $user->createToken('role:' . $user->role, ['*']);

        return new AuthResource($access_token);
    }
}
