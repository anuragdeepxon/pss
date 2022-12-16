<?php

namespace App\Http\Requests\API\Employer;

use App\Models\Employer\Employer;
use InfyOm\Generator\Request\APIRequest;

class UpdateEmployersAPIRequest extends APIRequest
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
        $rules = Employer::$rules;
        
        return $rules;
    }
}
