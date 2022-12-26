<?php

namespace App\Repositories\Candidates;

use App\Models\Candidates\Candidate;
use App\Models\Candidates\CandidateDetail;
use App\Models\Candidates\CandidateRequirement;
use App\Repositories\BaseRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CandidatesRepository extends BaseRepository
{
    protected $fieldSearchable = [];

    public $guard;

    public function __construct()
    {
        $this->makeModel();
        $this->guard = $this->model->guard;
    }

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Candidate::class;
    }

    public function setParms($request)
    {
        $input = $request->all();

        // Save data in candidate requirements table 
        $candidateRequirements = [
            'is_travel_allowance' => $request->is_travel_allowance,
            'is_meal_allowance' => $request->is_meal_allowance,
            'is_accommodation_allowance' => $request->is_accommodation_allowance,
            'travel_allowance_amount' => $request->travel_allowance_amount,
            'meal_allowance_amount' => $request->meal_allowance_amount,
            'accommodation_allowance_amount' => $request->accommodation_allowance_amount,
            'rate_type' => $request->rate_type,
            'rate_amount' => $request->rate_amount,
            'notes' => $request->notes,
            'shift_type' => $request->shift_type
        ];

        // Save data in candidate details table 
        $candidateDetails = [
            'address' => $request->address,
            'regulatory_college' => $request->regulatory_college,
            'regulatory_college_no' => $request->regulatory_college_no,
            'experience' => $request->experience,
        ];

        // save Data in candidate table
        $candidate = [
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'is_agree_term' => $request->is_agree_term,
            'is_agree_privacy' => $request->is_agree_privacy
        ];

        $path = public_path() . '/uploads/images/';

        if ($request->resume != '') {

            $file = $request->resume;
            $filename = $file->getClientOriginalName();
            $file->move($path, $filename);

            $candidateDetails['resume'] = $filename;
        }

        return [
            'candidate' => $candidate,
            'candidateDetails'=> $candidateDetails,
            'candidateRequirements' => $candidateRequirements
        ];
    }

    public function signup($request)
    {
        $users = [];
        DB::beginTransaction();
        try {
            $params = $this->setParms($request);
            // Save data in candidate requirements table 
            $candidateRequirements = $params['candidateRequirements'];

            // Save data in candidate details table 
            $candidateDetails = $params['candidateDetails'];

            // save Data in candidate table
            $candidate = $params['candidate'];

            
            $users = $this->create($candidate);

            if ($users) {
                // $token = $users->createToken('API Token')->accessToken;
                // $users['userToken'] = $token;

                $candidateDetails['candidate_id'] = $users->id;

                $candidateRequirements['candidate_id'] = $users->id;

                CandidateRequirement::create($candidateRequirements);

                CandidateDetail::create($candidateDetails);

                DB::commit();

                return $this->sendResponse($users, $this->model->message['signup'], 200);
            } else {
                DB::rollBack();
                return $this->sendResponse($users, 'User not signup succesfully', 200);
            }
        } catch (Exception $e) {

            DB::rollBack();
            return $this->sendResponse($users, $e->getMessage(), 500);
        }
    }

    public function editProfile($request)
    {
        DB::beginTransaction();
        try {
            $params = $this->setParms($request);
            // Save data in candidate requirements table 
            $candidateRequirements = $params['candidateRequirements'];

            // Save data in candidate details table 
            $candidateDetails = $params['candidateDetails'];

            // save Data in candidate table
            $candidateData = $params['candidate'];

            
            $candidate = Auth::guard($this->guard)->user();

            if ($candidate) {
               
                $candidate->update($candidateData);
                $candidate->candidateRequirement->update($candidateRequirements);
                $candidate->candidateDetail->update($candidateDetails);

                DB::commit();

                return $this->sendResponse($candidate, $this->model->message['signup'], 200);
            } else {
                DB::rollBack();
                return $this->sendResponse($candidate, 'Candidate not update succesfully', 200);
            }
        } catch (Exception $e) {

            DB::rollBack();
            return $this->sendResponse($candidate, $e->getMessage(), 500);
        }
    }
}
