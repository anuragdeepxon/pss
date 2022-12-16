<?php

namespace App\Transformers;

use App\Models\Employer\Employer;
use League\Fractal\TransformerAbstract;

class EmployerTransformer extends TransformerAbstract
{
    /**
     * @param \App\Employer $employer
     * @return array
     */
    public function transform(Employer $employer): array
    {
        $common = [
            'company_name' => $employer->employerDetail->company_name,
            'name'   => $employer->name,
            'position' => $employer->employerDetail->position,
            'email' => $employer->email,
            'address' => $employer->employerDetail->address,
            'phone_no' => $employer->phone_no,
        ];

        if ( !empty($employer->token)) {
            $common['token'] = $employer->token;
        }

        return $common;
    }
}
