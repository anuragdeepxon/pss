<?php

namespace App\Models\Employer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployerDetail extends Model
{
    use HasFactory,SoftDeletes;

    public $fillable = [
        'company_name',
        'position',
        'address',
        'phone_one',
        'phone_two',
        'employer_id'
    ];

    public static $rules = [
        'company_name' => 'required'
    ];

    public static function  createDetails($request,$employer)
    {
        $employerDetails = [
            'employer_id' => $employer->id,
            'position' => $request->name,
            'company_name' => $request->company_name,
            'position' => $request->position,
            'address' => $request->address,
            'phone_one' => $request->phone_one,
            'phone_two' => $request->phone_two,
        ];

        return self::create($employerDetails);
    }

}
