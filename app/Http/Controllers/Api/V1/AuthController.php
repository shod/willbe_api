<?php

namespace App\Http\Controllers\Api\V1;

use App\Interfaces\AuthRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\UserInfoRepositoryInterface;
use App\Interfaces\SmsRepositoryInterface;
use App\Interfaces\MailRepositoryInterface;

use App\Cache;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;

use App\Http\Resources\BaseJsonResource;
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
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset as EventsPasswordReset;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    private AuthRepositoryInterface $authRepository;
    private UserRepositoryInterface $userRepository;
    private UserInfoRepositoryInterface $userInfoRepository;
    private SmsRepositoryInterface $smsRepository;
    private MailRepositoryInterface $mailRepository;

    public function __construct(
        AuthRepositoryInterface $authRepository,
        UserRepositoryInterface $userRepository,
        UserInfoRepositoryInterface $userInfoRepository,
        SmsRepositoryInterface $smsRepository,
        MailRepositoryInterface $mailRepository
    ) {
        $this->authRepository = $authRepository;
        $this->userRepository = $userRepository;
        $this->userInfoRepository = $userInfoRepository;
        $this->smsRepository = $smsRepository;
        $this->mailRepository = $mailRepository;
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
            $userDetails['name'] = $request->input('name');
            $userDetails['email'] = $request->input('email');
            $userDetails['role'] = $request->input('role');
            $userDetails['password'] = $request->input('password');
            $userDetails['full_name'] = $request->input('full_name');
            $userDetails['gender'] = $request->input('gender');
            $userDetails['phone'] = $request->input('phone');

            $user = $this->authRepository->registerByEmail($userDetails);
            return new UserResource($user);
        } catch (\Exception $e) {
            //return error message            
            throw new GeneralJsonException('User Registration Failed!' . '. Details=' . $e->getMessage(), 409);
        }
    }

    public function login(LoginRequest $request)
    {

        /*Minutes*/
        //Auth::factory()->setTTL(60*24*180);
        try {

            if ($request->input('email')) {
                $user = $this->loginEmail($request);
            } /*elseif ($request->input('fb_token')) {
                return $this->loginFb($request);
            } elseif ($request->input('apple_token')) {
                return $this->loginApple($request);
            } elseif ($request->input('device_id')) {
                return $this->loginDeviceId($request);
            } */ else {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            //$access_token = $user->createToken('role:' . $user->role, ['*']);
            if (!$user) {
                abort(404, 'Not authorized (not logged in)');
            }

            return new AuthResource($user);
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
    public function loginEmail(LoginRequest $request): User
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        return $user;
    }

    /** Check 2fa verification*/
    public function get_check_2fa(Request $request)
    {
        $user = $request->user();

        $user_info = $this->userInfoRepository->getInfoBykey($user->getUserKey());
        $code = Cache::get_2fa_code($user->id);
        $res = $this->smsRepository::send_code($user, $code);
        return response()->json(['message' => 'Code was sended', 'success' => true], 200);
    }

    /** Check 2fa verification*/
    public function post_check_2fa(Request $request)
    {
        $user = $request->user();
        $code = $request->get('code');

        $user_info = $this->userInfoRepository->getInfoBykey($user->getUserKey());
        $cache_code = Cache::get_2fa_code($user->id);

        if ($code == $cache_code) {
            /**
             * Add abilities to all endpoint access
             */
            $this->token_abilities_add($user, [User::AUTH_IS2FA]);
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
    private function token_abilities_add(User $user, array $abilities)
    {
        $p_access_token = PersonalAccessToken::find($user->currentAccessToken()->id);
        $new_abilities = array_merge($abilities, $p_access_token->abilities);
        $p_access_token->abilities = $new_abilities;
        $p_access_token->save();
    }

    /** 
     * Update token abilities 
     */
    private function token_abilities_update(User $user, array $abilities)
    {
        $p_access_token = PersonalAccessToken::find($user->currentAccessToken()->id);
        $p_access_token->abilities = $abilities;
        $p_access_token->save();
    }

    /** 
     * Updating the token expire date 
     */
    private function token_update_expires(User $user, int $minute)
    {
        $p_access_token = PersonalAccessToken::find($user->currentAccessToken()->id);
        $p_access_token->expires_at = Carbon::now()->addMinutes($minute);
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
        $reset_pssword_token = md5(time());

        if (!$user_pass_reset = PasswordReset::where('email', $user->email)->first()) {
            PasswordReset::create([
                'email' => $user->email,
                'token' => $reset_pssword_token,
            ]);
        } else {
            $user_pass_reset->update(['token' => $reset_pssword_token]);
        }

        $reset_pssword_link = $request->redirect . "/?token=" . $reset_pssword_token;
        $result = $this->mailRepository->resetPassword($user, $reset_pssword_link);

        return response()->json(['message' => 'A code has been sended to your Email Address.', 'success' => true], 200);
    }


    /**
     * Perform a password reset request
     * @param ResetPasswordRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function reset_password(ResetPasswordRequest $request)
    {

        $resetRequest = PasswordReset::where('token', $request->token)->first();

        $user = User::where('email', $resetRequest->email)
            ->first();

        if (!$user) {
            throw new GeneralJsonException('No Record Found. Incorrect Email Address Provided', 404);
        }

        // if (!$resetRequest || $resetRequest->token != $request->token) {
        //     throw new GeneralJsonException('An Error Occured. Please Try again. Token mismatch', 400);
        // }

        $plainPassword = $request->input('password');

        //Update User password
        $user->fill([
            'password' => app('hash')->make($plainPassword),
        ]);
        $user->save();

        // Delete all previous tokens
        $user->tokens()->delete();

        $resetRequest->delete();

        return new AuthResource($user);
    }

    /**
     * Validation and refresh tokien
     */
    public function validate_token(Request $request)
    {
        $user = $request->user();
        $token = $user->currentAccessToken();

        if (!$token) {
            return response()->json(['message' => 'This token is not valid', 'success' => false], 403);
        }

        if (!$user->tokenCan(User::AUTH_IS2FA)) {
            return response()->json(['message' => 'Need to verification', 'is_need2fa' => true, 'success' => true], 200);
        }

        $p_access_token = PersonalAccessToken::find($user->currentAccessToken()->id);

        $created = new Carbon($p_access_token->created_at);
        $expires_at = ($p_access_token->expires_at == null) ? Carbon::now() : $p_access_token->expires_at;

        $difference = ($created->diff($expires_at)->days < 1)
            ? 0
            : $created->diffForHumans($expires_at);

        /** Less than 1 days */
        if ($difference < 1) {
            $this->token_update_expires($user, 120);
            return response()->json(['message' => 'This token is valid', 'success' => true], 200);
        }

        /** Delete current token */
        $token->delete();

        throw new GeneralJsonException('Not valid token', 409);
    }

    public function check_token(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            throw new GeneralJsonException('Token is not set', 409);
        }

        $user = User::query()->where('remember_token', $request->token)->first();
        if ($user) {
            return new BaseJsonResource(["message" => 'Token is valid']);
        } else {
            throw new GeneralJsonException('Token is not valid', 409);
        }
    }
}
