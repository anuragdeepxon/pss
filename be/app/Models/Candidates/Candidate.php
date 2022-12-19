<?php

namespace App\Models\Candidates;

use App\Models\ForgetPasswordOtp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

/**
 * @OA\Schema(
 *      schema="Candidate",
 *      required={},
 *      @OA\Property(
 *          property="created_at",
 *          description="",
 *          readOnly=true,
 *          nullable=true,
 *          type="string",
 *          format="date-time"
 *      ),
 *      @OA\Property(
 *          property="updated_at",
 *          description="",
 *          readOnly=true,
 *          nullable=true,
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */class Candidate extends Authenticatable
{
     use SoftDeletes,HasApiTokens;    
     
     public $table = 'candidates';
     
     public $guard = 'candidates-api';
 
     public $sessionGuard = 'candidates';
 
     public $provider = 'candidates';
 
     public $message = [
         'login' => 'candidates login successfully',
         'signup' => 'candidates Signup successfully',
         'not_exist' => 'candidates does not exist',
         'wrong_password' => 'Password Incorrect',
         'logout' => 'Logout Successfully'
     ];
 
     public $fillable = [
         'first_name',
         'last_name',
         'email',
         'password',
         'phone_no',
         'is_agree_term',
         'is_agree_privacy',
         'otp'
     ];
 
     protected $casts = [
         'email_verified_at' => 'datetime',
     ];
 
     public static $rules = [
         'email' => 'required|email|unique:candidates',
         'phone_no' => 'required',
         'password' => 'required|min:8',
         'first_name' => 'required',
         'last_name' => 'required',
         'confirm_password' => 'required|same:password',
         'is_agree_term' => 'required',
         'is_agree_privacy' => 'required',
     ];

     public function hasOtp()
     {
        return $this->hasOne(ForgetPasswordOtp::class,'model_id','id')->where('model_type',self::class);
     }

    
}
