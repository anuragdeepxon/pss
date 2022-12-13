<?php

namespace App\Models\Employer;

use Illuminate\Database\Eloquent\Model;
 use Illuminate\Database\Eloquent\SoftDeletes;;
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
 */class Employers extends Model
{
     use SoftDeletes;    public $table = 'pss_employers';

    public $fillable = [
        
    ];

    protected $casts = [
        
    ];

    public static $rules = [
        
    ];

    
}
