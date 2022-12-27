<?php

namespace App\Repositories\Employer;

use App\Models\Employer\EmployerDetail;
use App\Models\Employer\Employer;
use App\Repositories\BaseRepository;
use App\Transformers\EmployerTransformer;
use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Notification\Entities\Notification;

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
        return Employer::class;
    }

    public function signup($request)
    {
        $data = [];

        DB::beginTransaction();
        try {
            $input = $request->all();
            $users = $this->create($input);
            $employerDetails = EmployerDetail::createDetails($request,$users);
            // $token = $users->createToken('API Token')->accessToken;
            // $data['userToken'] = $token;
            // $data = (new EmployerTransformer)->transform($users);
            // $data['classType'] = get_class($users);
            $mailTemplate = view('template.welcome',compact('users'))->render();
            
            $sendMail = [
                'description' => $mailTemplate,
                'title' => "Welcome in ".config('app.name') ." family",
                'user' => $users,
                'send_by' => 1
            ];

            // Send email to user
            Notification::createNotification($sendMail);

            DB::commit();

            return [
                'data' => $users,
                'message' => $this->model->message['signup'],
                'statusCode' => 200
            ];
        } catch (Exception $e) {
            $users = [];
            DB::rollBack();

            return [
                'data' => [],
                'message' => $e->getMessage(),
                'statusCode' => 500
            ];

        }
    }
}
