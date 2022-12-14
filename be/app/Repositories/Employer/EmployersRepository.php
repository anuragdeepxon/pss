<?php

namespace App\Repositories\Employer;

use App\Models\Employer\EmployerDetail;
use App\Models\Employer\Employers;
use App\Repositories\BaseRepository;
use Exception;
use Illuminate\Support\Facades\DB;

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

    public function signup($request)
    {
        DB::beginTransaction();
        try {
            $input = $request->all();
            $users = $this->create($input);
            $employerDetails = EmployerDetail::createDetails($request,$users);
            if ($users) {
                $token = $users->createToken('API Token')->accessToken;
                $users['userToken'] = $token;
                $users['classType'] = get_class($users);
                $user['employerDetails'] = $employerDetails;
                DB::commit();
                return $this->sendResponse($users, $this->model->message['signup'], 200);
            } else {
                DB::rollBack();
                return $this->sendResponse($users, 'User not signup succesfully', 500);
            }
        } catch (Exception $e) {
            $users = [];
            DB::rollBack();
            return $this->sendResponse($users, $e->getMessage(), 500);
        }
    }
}
