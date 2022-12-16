<?php

namespace App\Repositories\Candidates;

use App\Models\Candidates\Candidate;
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
        return Candidate::class;
    }
}
