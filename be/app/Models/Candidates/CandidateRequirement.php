<?php

namespace App\Models\Candidates;

use Illuminate\Database\Eloquent\Model;
 use Illuminate\Database\Eloquent\SoftDeletes;;
/**
 * @OA\Schema(
 *      schema="CandidateRequirement",
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
 */class CandidateRequirement extends Model
{
    use SoftDeletes;    
    
    public $table = 'candidate_requirements';

    public $fillable = [
        'is_tarvel_allowance',
        'is_meal_allowance',
        'is_accommodation_allowance',
        'travel_allowance_amount',
        'meal_allowance_amount',
        'accommodation_allowance_amount',
        'rate_type',
        'rate_amount',
        'notes',
        'shift_type'
    ];

    protected $casts = [
        
    ];

    public static $rules = [
        
    ];

    
}
