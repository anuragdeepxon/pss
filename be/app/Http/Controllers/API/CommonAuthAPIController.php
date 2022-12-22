<?php

namespace App\Http\Controllers\API;

use App\Repositories\CommonAuthRepository;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Commonauth\ChangePassword;
use App\Http\Requests\Commonauth\ForgetPasswordOtpSendRequest;
use App\Http\Requests\Commonauth\LoginCommonAuthRequest;
use App\Http\Requests\Commonauth\Logout;
use App\Http\Requests\Commonauth\OtpVerifyRequest;

/**
 * Class CommonAuthController
 */

class CommonAuthAPIController extends AppBaseController
{
    private CommonAuthRepository $commonAuthRepository;

     
    public function __construct(CommonAuthRepository $commonAuthRepo)
    {
        $this->commonAuthRepository = $commonAuthRepo;    
    }

    public function response($data)
    {
        $resData = !empty($data['data']) ? $data['data'] : [];
        $message = !empty($data['message']) ? $data['message'] : '';
        $code = !empty($data['statusCode']) ? $data['statusCode'] : 404;
        return $this->sendResponseWithStatus($resData,$message,$code);
    }

    /**
     * @OA\Post(
     *      path="/login",
     *      summary="Login",
     *      tags={"Common Auth"},
     *      description="Login Employers/Candidates",
     *      @OA\RequestBody(
     *        required=true,
     *        description="Pass user credentials",
     *        @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *                required={"email","password","user_type"},
     *                @OA\Property(property="email", type="email", format="email", example="user1@mail.com"),
     *                @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *                @OA\Property(property="user_type", type="integer", format="integer", description="3=>candidate,2=>employer"),
     *            )
     *        ),
     *        @OA\JsonContent(
     *                required={"email","password","user_type"},
     *                @OA\Property(property="email", type="email", format="email", example="user1@mail.com"),
     *                @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *                @OA\Property(property="user_type", type="integer", format="integer", description="3=>candidate,2=>employer"),
     *        ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/Employer"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function login(LoginCommonAuthRequest $request)
    {
        $loginUser = $this->commonAuthRepository->login($request);
        return $this->response($loginUser);
    }
 /**
     * @OA\Post(
     *      path="/forget-password-otp-send",
     *      summary="forget password otp send first",
     *      tags={"Common Auth"},
     *      description="forget Employers/candidates password",
     *      @OA\RequestBody(
     *        required=true,
     *        description="forget Employers/candidates password",
     *        @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *                required={"email","user_type"},
     *                @OA\Property(property="email", type="email", format="email", example="user1@mail.com"),
     *                @OA\Property(property="user_type", type="integer", format="integer", description="3=>candidate,2=>employer"),
     *            )
     *        ),
     *        @OA\JsonContent(
     *          required={"email","user_type"},
     *          @OA\Property(property="email", type="email", format="email", example="user1@mail.com"),
     *          @OA\Property(property="user_type", type="integer", format="integer", description="3=>candidate,2=>employer"),
     *        ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/Candidate"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function forgetPasswordOtpSend(ForgetPasswordOtpSendRequest $request)
    {
        $data = $this->commonAuthRepository->forgetPassword($request);   
        return $this->response($data);

    }

     /**
     * @OA\Post(
     *      path="/otp-verify",
     *      summary="OTP verify",
     *      tags={"Common Auth"},
     *      description="otp verify",
     *      @OA\RequestBody(
     *        required=true,
     *        description="otp verify",
     *        @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *                required={"email","otp","user_type"},
     *                @OA\Property(property="email", type="text", format="text", example="test@text.com"),
     *                @OA\Property(property="otp", type="text", format="text", example="1234"),  
     *                @OA\Property(property="user_type", type="integer", format="integer", description="3=>candidate,2=>employer"),       
     *            )
     *        ),
     *        @OA\JsonContent(
     *                required={"email","otp","user_type"},
     *                @OA\Property(property="email", type="text", format="text", example="test@text.com"),
     *                @OA\Property(property="otp", type="text", format="text", example="1234"),  
     *                @OA\Property(property="user_type", type="integer", format="integer", description="3=>candidate,2=>employer"),       
     *        ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *               @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/Candidate"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function otpVerify(OtpVerifyRequest $request) 
    {
        $data = $this->commonAuthRepository->otpVerfiy($request);
        return $this->response($data);

    }

     /**
     * @OA\Post(
     *      path="/set-new-password",
     *      summary="password change",
     *      tags={"Common Auth"},
     *      description="password change",
     *      @OA\RequestBody(
     *        required=true,
     *        description="password change",
     *        @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *                required={"email","password","confirm_password","user_type"},
     *                @OA\Property(property="email", type="email", format="text", example="text@gmail.com"),
     *                @OA\Property(property="password", type="text", format="text", example="Admin@1234"),
     *                @OA\Property(property="confirm_password", type="text", format="text", example="Admin@1234"),         
     *                @OA\Property(property="user_type", type="integer", format="integer", description="3=>candidate,2=>employer"),       
     *            )
     *        ),
     *        @OA\JsonContent(
     *           required={"email","password","confirm_password","user_type"},
     *           @OA\Property(property="email", type="email", format="text", example="text@gmail.com"),
     *           @OA\Property(property="password", type="text", format="text", example="Admin@1234"),
     *           @OA\Property(property="confirm_password", type="text", format="text", example="Admin@1234"),         
     *           @OA\Property(property="user_type", type="integer", format="integer", description="3=>candidate,2=>employer"),       
     *        ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/Candidate"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function setNewPassword(ChangePassword $request)
    {
        $data =  $this->commonAuthRepository->setNewPassword($request);
        return $this->response($data);

    }


     /**
     * @OA\Post(
     *      path="/logout",
     *      summary="logout",
     *      tags={"Common Auth"},
     *      security={
     *           {"bearerAuth": {}}
     *       },
     *      description="logout Employers/Candidates",
     *      @OA\RequestBody(
     *        required=true,
     *        description="Pass user credentials",
     *        @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *                required={"user_type"},
     *                @OA\Property(property="user_type", type="integer", format="integer", description="3=>candidate,2=>employer"),
     *            )
     *        ),
     *        @OA\JsonContent(
     *                required={"user_type"},
     *                @OA\Property(property="user_type", type="integer", format="integer", description="3=>candidate,2=>employer"),
     *        ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @OA\Property(
     *                  property="data",
     *                  ref="#/components/schemas/Employer"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function logout(Logout $request)
    {
        $data =  $this->commonAuthRepository->logout($request);
        return $this->response($data);
    }
}
