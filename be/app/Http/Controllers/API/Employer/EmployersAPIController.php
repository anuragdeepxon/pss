<?php

namespace App\Http\Controllers\API\Employer;

use App\Http\Requests\API\Employer\CreateEmployersAPIRequest;
use App\Models\Employer\Employers;
use App\Repositories\Employer\EmployersRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\Employer\CreateEmployerDetailsAPIRequest;
use App\Http\Requests\API\Employer\UpdateEmployersAPIRequest;
use Laravel\Passport\Client as OClient;

/**
 * Class EmployersController
 */

class EmployersAPIController extends AppBaseController
{
    private EmployersRepository $employersRepository;

    public function __construct(EmployersRepository $employersRepo)
    {
        $this->employersRepository = $employersRepo;
    }

    /**
     * @OA\Get(
     *      path="/employers",
     *      summary="getEmployersList",
     *      tags={"Employers"},
     *      description="Get all Employers",
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
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/Employers")
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $employers = $this->employersRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($employers->toArray(), 'Employers retrieved successfully');
    }

    /**
     * @OA\Post(
     *      path="/employers/signup",
     *      summary="createEmployers",
     *      tags={"Employers"},
     *      description="Create Employers",
     *      @OA\RequestBody(
     *        required=true,
     *        description="Pass user credentials",
     *        @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *                required={"email","password","name","confirm_password","phone_no","is_agree_term","is_agree_privacy","position","address","company_name"},
     *                @OA\Property(property="company_name", type="string", example="test pvt ltd"),
     *                @OA\Property(property="name", type="string", example="test kumar"),
     *                @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *                @OA\Property(property="confirm_password", type="string", format="password", example="PassWord12345"),
     *                @OA\Property(property="position", type="string", example="378378373"),
     *                @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *                @OA\Property(property="address", type="string",  example="Mohali"),
     *                @OA\Property(property="phone_no", type="string", example="378378373"),
     *                @OA\Property(property="is_agree_term", type="checbox", format="checbox", example="0|1"),
     *                @OA\Property(property="is_agree_privacy", type="checbox", format="checbox", example="0|1"),
     *               )
     *        ),
     *        @OA\JsonContent(
     *                required={"email","password","name","confirm_password","phone_no","is_agree_term","is_agree_privacy","position","address","company_name"}, 
     *                @OA\Property(property="company_name", type="string", example="test pvt ltd"),
     *                @OA\Property(property="name", type="string", example="test kumar"),
     *                @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *                @OA\Property(property="confirm_password", type="string", format="password", example="PassWord12345"),
     *                @OA\Property(property="position", type="string", example="378378373"),
     *                @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *                @OA\Property(property="address", type="string",  example="Mohali"),
     *                @OA\Property(property="phone_no", type="string", example="378378373"),
     *                @OA\Property(property="is_agree_term", type="checbox", format="checbox", example="0|1"),
     *                @OA\Property(property="is_agree_privacy", type="checbox", format="checbox", example="0|1"),
     *         ),
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
     *                  ref="#/components/schemas/Employers"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function signupEmployer(CreateEmployersAPIRequest $request): JsonResponse
    {
       return $this->employersRepository->signup($request);
    }
    
    /**
     * @OA\Post(
     *      path="/employers/login",
     *      summary="loginEmployers",
     *      tags={"Employers"},
     *      description="Login Employers",
     *      @OA\RequestBody(
     *        required=true,
     *        description="Pass user credentials",
     *        @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *                required={"email","password"},
     *                @OA\Property(property="email", type="email", format="email", example="user1@mail.com"),
     *                @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *            )
     *        ),
     *        @OA\JsonContent(
     *          required={"email","password"},
     *          @OA\Property(property="email", type="email", format="email", example="user1@mail.com"),
     *          @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
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
     *                  ref="#/components/schemas/Employers"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function login(Request $request)
    {
       return $this->employersRepository->login($request,'employers-api');
    }
    /**
     * @OA\Get(
     *      path="/employers/{id}",
     *      summary="getEmployersItem",
     *      tags={"Employers"},
     *      description="Get Employers",
     *      @OA\Parameter(
     *          name="id",
     *          description="id of Employers",
     *           @OA\Schema(
     *             type="integer"
     *          ),
     *          required=true,
     *          in="path"
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
     *                  ref="#/components/schemas/Employers"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($id): JsonResponse
    {
        /** @var Employers $employers */
        $employers = $this->employersRepository->find($id);

        if (empty($employers)) {
            return $this->sendError('Employers not found');
        }

        return $this->sendResponse($employers->toArray(), 'Employers retrieved successfully');
    }

    /**
     * @OA\Put(
     *      path="/employers/{id}",
     *      summary="updateEmployers",
     *      tags={"Employers"},
     *      description="Update Employers",
     *      @OA\Parameter(
     *          name="id",
     *          description="id of Employers",
     *           @OA\Schema(
     *             type="integer"
     *          ),
     *          required=true,
     *          in="path"
     *      ),
     *      @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(ref="#/components/schemas/Employers")
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
     *                  ref="#/components/schemas/Employers"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateEmployersAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var Employers $employers */
        $employers = $this->employersRepository->find($id);

        if (empty($employers)) {
            return $this->sendError('Employers not found');
        }

        $employers = $this->employersRepository->update($input, $id);

        return $this->sendResponse($employers->toArray(), 'Employers updated successfully');
    }

    /**
     * @OA\Delete(
     *      path="/employers/{id}",
     *      summary="deleteEmployers",
     *      tags={"Employers"},
     *      description="Delete Employers",
     *      @OA\Parameter(
     *          name="id",
     *          description="id of Employers",
     *           @OA\Schema(
     *             type="integer"
     *          ),
     *          required=true,
     *          in="path"
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
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function destroy($id): JsonResponse
    {
        /** @var Employers $employers */
        $employers = $this->employersRepository->find($id);

        if (empty($employers)) {
            return $this->sendError('Employers not found');
        }

        $employers->delete();

        return $this->sendSuccess('Employers deleted successfully');
    }
}
