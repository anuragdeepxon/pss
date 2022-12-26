<?php

namespace App\Models\Employer;

use App\Transformers\EmployerTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @OA\Schema(
 *      schema="Employer",
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
 */class Employer extends Authenticatable implements Auditable
{
    use SoftDeletes,HasApiTokens,AuditingAuditable;    
     
    public $guard = 'employers-api';

    public $sessionGuard = 'employers';

    public $provider = 'employers';

    public $message = [
        'login' => 'Employer login successfully',
        'signup' => 'Employer Signup successfully',
        'not_exist' => 'Employer does not exist',
        'wrong_password' => 'Password Incorrect',
        'logout' => 'Logout Successfully'
    ];

    public $appends = [
        'full_name'
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
        'phone_no' => 'required|unique:employers',
        'password' => 'required|min:8',
        'name'   => 'required',
        'confirm_password' => 'required|same:password',
        'is_agree_term' => 'required',
        'is_agree_privacy' => 'required',
    ];

    public function employerDetail()
    {
       return $this->hasOne(EmployerDetail::class,'employer_id','id');
    }

    public function tranform($request)
    {
       return (new EmployerTransformer)->transform($request);
    }

    public function getFullNameAttribute()
    {
        return $this->name;
    }
    
}
