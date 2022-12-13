<?php

namespace App\Repositories\Employer;

use App\Models\Employer\Employers;
use App\Repositories\BaseRepository;

class EmployersRepository extends BaseRepository
{
    protected $fieldSearchable = [
        
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Employers::class;
    }
}
