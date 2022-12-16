<?php

namespace App\Http\Requests\API\Candidates;

use App\Models\Candidates\Candidate;
use InfyOm\Generator\Request\APIRequest;

class UpdateCandidatesAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = Candidate::$rules;
        
        return $rules;
    }
}
