<?php

namespace App\Http\Requests\Commonauth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use InfyOm\Generator\Utils\ResponseUtil;
class ChangePassword extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->messages();
        $statusCode = 401;
        $data = response()->json([
            'status' =>$statusCode,
            'message' => $errors
        ],$statusCode);

        throw new HttpResponseException($data);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_type' => 'required',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ];
    }


}
