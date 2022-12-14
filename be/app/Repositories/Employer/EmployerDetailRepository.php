<?php

namespace App\Repositories\Employer;

use App\Models\Employer\EmployerDetail;
use App\Repositories\BaseRepository;

class EmployerDetailRepository extends BaseRepository
{
    protected $fieldSearchable = [
        
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return EmployerDetail::class;
    }
}
