<?php

namespace App\Transformers;

use App\Candidate;
use App\Models\Candidates\Candidate as CandidatesCandidate;
use League\Fractal\TransformerAbstract;

class CandidateTransformer extends TransformerAbstract
{
    /**
     * @param \App\Candidate $candidate
     * @return array
     */
    public function transform(CandidatesCandidate $candidate): array
    {
        $common = [
            'first_name' => $candidate->first_name,
            'last_name' => $candidate->last_name,
            'email' => $candidate->email,
            'phone_no' => $candidate->phone_no
        ];

        if ( !empty($candidate->token)) {
            $common['token'] = $candidate->token;
        }

        return $common;
    }
}