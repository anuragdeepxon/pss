<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForgetPasswordOtp extends Model
{
    use HasFactory;
 
     public $fillable = [
         'otp',
         'otp_expire_date_time',
         'model_type',
         'model_id',
         'is_verify',
         'verify_at'
     ];

     public static $rules = [
         'otp' => 'required',
         'otp_expire_date_time' => 'required',
         'model_type' => 'required',
         'model_id' => 'required'
     ];
}
