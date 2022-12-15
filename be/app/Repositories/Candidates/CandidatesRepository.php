<?php

namespace App\Repositories\Candidates;

use App\Models\Candidates\Candidates;
use App\Repositories\BaseRepository;

class CandidatesRepository extends BaseRepository
{
    protected $fieldSearchable = [
        
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Candidates::class;
    }
}
