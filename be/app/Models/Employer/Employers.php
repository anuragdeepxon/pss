<?php

namespace App\Models\Employer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens as PassportHasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
;
/**
 * @OA\Schema(
 *      schema="Employers",
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
 */class Employers extends Authenticatable
{
    use SoftDeletes,PassportHasApiTokens;    
     
    public $guard = 'employers-api';

    public $message = [
        'login' => 'Employer login successfully',
        'signup' => 'Employer Signup successfully',
        'not_exist' => 'Employer does not exist',
        'wrong_password' => 'Password Incorrect'
    ];

    public $fillable = [
        'name',
        'email',
        'password',
        'phone_no',
        'is_agree_term',
        'is_agree_privacy'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static $rules = [
        'email' => 'required|email|unique:employers',
        'phone_no' => 'required',
        'password' => 'required|min:8',
        'name'   => 'required',
        'confirm_password' => 'required|same:password',
        'is_agree_term' => 'required',
        'is_agree_privacy' => 'required',
    ];

    
}
