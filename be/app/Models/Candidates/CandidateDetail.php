<?php

namespace App\Models\Candidates;

use Illuminate\Database\Eloquent\Model;
 use Illuminate\Database\Eloquent\SoftDeletes;;
/**
 * @OA\Schema(
 *      schema="CandidateDetail",
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
 */class CandidateDetail extends Model
{
    use SoftDeletes;    
     
    public $table = 'candidate_details';

    public $fillable = [
        'address',
        'regulatory_college',
        'regulatory_college_no',
        'experience',
        'resume'
    ];

    protected $casts = [

    ];

    public static $rules = [
        
    ];

    
}
