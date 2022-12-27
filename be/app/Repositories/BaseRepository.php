<?php

namespace App\Repositories;

use App\Mail\ForgetPassword;
use App\Models\ForgetPasswordOtp;
use Carbon\Carbon;
use Exception;
use Illuminate\Container\Container as Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use InfyOm\Generator\Utils\ResponseUtil;
use Laravel\Passport\Client as OClient;
use Modules\Notification\Entities\Notification;

abstract class BaseRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->makeModel();
    }

    /**
     * Get searchable fields array
     */
    abstract public function getFieldsSearchable(): array;

    /**
     * Configure the Model
     */
    abstract public function model(): string;

    /**
     * Make Model instance
     *
     * @throws \Exception
     *
     * @return Model
     */
    public function makeModel()
    {
        $model = app($this->model());

        if (!$model instanceof Model) {
            throw new \Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    /**
     * Paginate records for scaffold.
     */
    public function paginate(int $perPage, array $columns = ['*']): LengthAwarePaginator
    {
        $query = $this->allQuery();

        return $query->paginate($perPage, $columns);
    }

    /**
     * Build a query for retrieving all records.
     */
    public function allQuery(array $search = [], int $skip = null, int $limit = null): Builder
    {
        $query = $this->model->newQuery();

        if (count($search)) {
            foreach ($search as $key => $value) {
                if (in_array($key, $this->getFieldsSearchable())) {
                    $query->where($key, $value);
                }
            }
        }

        if (!is_null($skip)) {
            $query->skip($skip);
        }

        if (!is_null($limit)) {
            $query->limit($limit);
        }

        return $query;
    }

    /**
     * Retrieve all records with given filter criteria
     */
    public function all(array $search = [], int $skip = null, int $limit = null, array $columns = ['*']): Collection
    {
        $query = $this->allQuery($search, $skip, $limit);

        return $query->get($columns);
    }

    /**
     * Create model record
     */
    public function create(array $input): Model
    {
        if (array_key_exists("password", $input)) {
            $input['password'] = Hash::make($input['password']);
        }

        $model = $this->model->newInstance($input);

        $model->save();

        return $model;
    }

    /**
     * Find model record for given id
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model|null
     */
    public function find(int $id, array $columns = ['*'])
    {
        $query = $this->model->newQuery();

        return $query->find($id, $columns);
    }

    /**
     * Update model record for given id
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model
     */
    public function update(array $input, int $id)
    {
        $query = $this->model->newQuery();

        $model = $query->findOrFail($id);

        $model->fill($input);

        $model->save();

        return $model;
    }

    /**
     * @throws \Exception
     *
     * @return bool|mixed|null
     */
    public function delete(int $id)
    {
        $query = $this->model->newQuery();

        $model = $query->findOrFail($id);

        return $model->delete();
    }

    public function sendResponse($result, $message, $statusCode)
    {
        $result['statusCode'] = $statusCode;
        return response()->json(ResponseUtil::makeResponse($message, $result));
    }

    public function getTokenAndRefreshToken(OClient $oClient, $email, $password)
    {

        $data =
            [
                'grant_type' => 'password',
                'client_id' => $oClient->id,
                'client_secret' => $oClient->secret,
                'username' => $email,
                'password' => $password,
                'scope' => '*',
            ];

        $request = Request::create('/oauth/token', 'POST', $data);

        $response = json_decode(app()->handle($request)->getContent());

        return $response;
    }


    public function signup($request)
    {
        $input = $request->all();
        $users = $this->create($input);

        if ($users) {
            // $token = $users->createToken('API Token')->accessToken;
            // $users['userToken'] = $token;
            $users['classType'] = get_class($users);
            return $this->sendResponse($users, $this->model->message['signup'], 200);
        } else {
            return $this->sendResponse($users, 'User not signup succesfully', 500);
        }
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

                    /*$oClient = Oclient::where([['password_client', '=', 1], ['provider', $provider]])->first();

                $getToken = $this->getTokenAndRefreshToken($oClient, $loginuser->email, $request->password);

                $loginuser->token_type = $getToken->token_type;

                $loginuser->expires_in = $getToken->expires_in;

                $loginuser->access_token  =  $getToken->access_token;

                $loginuser->refresh_token = $getToken->refresh_token;*/

                    return [
                        'data' => $loginuser,
                        'message' => $this->model->message['login'],
                        'statusCode' => 200
                    ];
                    // return $this->sendResponse($loginuser, $this->model->message['login'], 200);
                }
            } else {
                return ['message' => 'something went wrong', 'statusCode' => 500];
            }
        } catch (Exception $e) {
            return ['message' => $e->getMessage(), 'statusCode' => 500];
        }
    }

    public function logout(Request $request)
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
                        'otp_expire_date_time' => $expireTime,
                    ];

                    $otpData = ForgetPasswordOtp::create($data);
                }
                $mailTemplate = view('template.forget-password',compact('otpData','user'))->render();
                
                $sendMail = [
                    'description' => $mailTemplate,
                    'title' => 'Forget Password',
                    'user' => $user,
                    'send_by' => 1
                ];

                // Send email to user
                Notification::createNotification($sendMail);

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

                if ( !empty($userOtp) ) {

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
                } else {
                    return [
                        'message' => 'OTP not generated yet',
                        'statusCode' => 200,
                        'data' =>     []
                    ];
                }
            } else {
                return [
                    'message' => 'User Not Found',
                    'statusCode' => 404,
                    'data' =>     []
                ];
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

                if ( $userOtp ) {

                    if ( !$userOtp->is_verify ) {
                        return [
                            'message' => 'verify the otp first',
                            'statusCode' => 200,
                        ];
                    }

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
                        'message' => 'OTP not generated yet',
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

    public function generateUniqueCode()
    {
        do {
            $code = random_int(1000, 9999);
        } while (ForgetPasswordOtp::where("otp", "=", $code)->first());

        return $code;
    }
    
}
