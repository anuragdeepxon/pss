<?php

namespace App\Repositories;

use App\Models\Candidates\Candidate;
use App\Models\CommonAuth;
use App\Models\Employer\Employer;
use App\Models\User;
use Illuminate\Http\Request;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgetPassword;
use App\Models\ForgetPasswordOtp;
use Carbon\Carbon;
use Exception;

class CommonAuthRepository extends BaseRepository
{
    protected $fieldSearchable = [];

    public $model_type;

    const ADMIN_USER = 1; 
    const EMPLOYER_USER = 2;
    const CANDIDATE_USER = 3;

    public function __construct(Request $request)
    {
        $this->model_type = $request->user_type;
        if ( !empty($this->model_type) ) {
            $this->makeModel();
        }
    }

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    
    public function model(): string
    {
        if (!empty($this->model_type)) {
            if (self::CANDIDATE_USER == $this->model_type) {
                return Candidate::class;
            } else if (self::EMPLOYER_USER == $this->model_type) {
                return Employer::class;
            } else if (self::ADMIN_USER == $this->model_type) {
                return User::class;
            }
        }
        return '';
    }

    public function login($request)
    {
        try {
            $findUser = $this->model->where(['email' => $request->email])->first();
            $guard = $this->model->sessionGuard;
            $provider = $this->model->provider;
            $result = [];
            
            if (!$findUser) {
                return ['data' => $result, 'message' => $this->model->message['not_exist'], 'statusCode' => 401];
            } elseif (!Hash::check($request->password, $findUser->password)) {
                return ['data' => $result, 'message' => $this->model->message['wrong_password'], 'statusCode' => 401];
            }


            if ($findUser) {
                $userAttempt = Auth::guard($guard)->attempt(['email' => $request->email, 'password' => $request->password]);

                // config(['auth.guards.api.provider' => 'user']);

                if ($userAttempt) {

                    $loginuser = Auth::guard($guard)->user();
                    $token = Auth::guard($guard)->user()->createToken('API TOKEN')->accessToken;

                    $loginuser['token'] = $token;

                    return [
                        'data' => $this->model->tranform($loginuser),
                        'message' => $this->model->message['login'],
                        'statusCode' => 200
                    ];
                }
            } else {
                return ['data' => $result, 'message' => 'something went wrong', 'statusCode' => 500];
            }
        } catch (Exception $e) {
            return ['data' => $result, 'message' => $e->getTrace(), 'statusCode' => 500];
        }
    }
    public function logout($request)
    {
        try {
            $guard = $this->model->guard;
            $user = Auth::guard($guard)->user()->token();
            $user->revoke();
            return ['data'=>$user,'message'=>$this->model->message['logout'],'statusCode'=>200];
        } catch (\Exception $e) {
            return ['data'=>[],'message'=>$e->getMessage(),'statusCode'=>500];
        }
    }

    /**
     * Send Otp on user eneterd email
     *
     * @param [type] $request
     * @return void
     */
    public function forgetPassword($request)
    {
        try {
            $uniqueCode = $this->generateUniqueCode();

            $user = $this->model->where(['email' => $request->email])->first();
            if ($user) {

                $expireTime = Carbon::now()->addMinutes(5);

                if ($user->hasOtp) {
                    $data = [
                        'otp' => $uniqueCode,
                        'otp_expire_date_time' => $expireTime
                    ];

                    $otpData = $user->hasOtp->update($data);
                    $otpData = $user->hasOtp;
                } else {

                    $data = [
                        'otp' => $uniqueCode,
                        'model_id' => $user->id,
                        'model_type'  => $user::class,
                        'otp_expire_date_time' => $expireTime
                    ];

                    $otpData = ForgetPasswordOtp::create($data);
                }

                // Send email to user
                Mail::to($user->email)->send(new ForgetPassword($otpData));

                $data = [
                    'email' => $user->email
                ];

                return [
                    'message' => __('You will received an otp on your email.'), 
                    'statusCode' => 200, 
                    'data' => $data
                ];
            } else {
                return [
                     'message' => __('This Email does not exist'),
                     'statusCode' => 401, 
                     'data' => ''
                ];
            }
        } catch (Exception $e) {
            return ['data' => [], 'message' => $e->getMessage(), 'statusCode' => 500];
        }
    }
    /**
     * verify the otp 
     *
     * @param [type] $request
     * @return void
     */
    public function otpVerfiy($request)
    {
        try {
            $user = $this->model->where(['email' => $request->email])->first();
            if ($user) {
                $userOtp = $user->hasOtp;
                $checkOtpTime = $userOtp->otp_expire_date_time;
                $currentTime = Carbon::now();
                if ($currentTime > $checkOtpTime) {
                    return array('message' => 'Otp have expired', 'statusCode' => 200, 'data' => []);
                } else {
                    if ($request->otp == $userOtp->otp) {
                        $userOtp->update(['is_verify' => 1, 'verify_at' => Carbon::now()]);
                        return [
                            'message' => 'Otp verified succesfully',
                            'statusCode' => 200,
                            'data' =>     [
                                'is_verified_otp' => true,
                                'email' => $user->email
                            ]
                        ];
                    } else {
                        return [
                            'message' => 'You have entered wrong otp',
                            'statusCode' => 200,
                            'data' =>     [
                                'is_verified_otp' => false,
                                'email' => $user->email
                            ]
                        ];
                    }
                }
            }
        } catch (Exception $e) {
            return array('message' => $e->getMessage(), 'statusCode' => 500, 'data' => []);
        }
    }

    /**
     * Set New Password
     */
    public function setNewPassword($request)
    {
        try {

            $user = $this->model->where(['email' => $request->email])->first();
            if ($user) {
                $userOtp = $user->hasOtp;
                $verify_time_expire = (new Carbon($userOtp->verify_at))->addMinutes(5); // If you have verfiy your otp but do not forget the password so In forget password screen is valid for next 5 minutes after that it give expire error
                $current_time = Carbon::now();
                if ( !empty($userOtp) && !empty($userOtp->is_verify) && $current_time < $verify_time_expire) {

                    $password = Hash::make($request->password);

                    $user->update(['password' => $password]);
                    $userOtp->delete();
                    return [
                        'message' => 'Password Change Successfully',
                        'statusCode' => 200,
                    ];
                } else {
                    return [
                        'message' => 'Your otp verfication time have been expired .please again verify otp',
                        'statusCode' => 200
                    ];
                }
            } else {

                return [
                    'message' => 'This Email does not exist',
                    'statusCode' => 401
                ];
            }
        } catch (Exception $e) {
            return [
                'message' => $e->getMessage(),
                'statusCode' => 500
            ];
        }
    }
}
