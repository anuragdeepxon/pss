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
        return [
            'id' => (int) $employer->id,
        ];
    }
}