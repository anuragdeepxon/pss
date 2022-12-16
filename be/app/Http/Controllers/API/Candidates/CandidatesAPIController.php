<?php

namespace App\Http\Controllers\API\Candidates;

use App\Http\Requests\API\Candidates\UpdateCandidatesAPIRequest;
use App\Models\Candidates\Candidate;
use App\Repositories\Candidates\CandidatesRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\Candidates\CreateCandidatesAPIRequest;
use App\Transformers\CandidateTransformer;

/**
 * Class CandidatesController
 */
class CandidatesAPIController extends AppBaseController
{
    private CandidatesRepository $candidatesRepository;
    private CandidateTransformer $candidateTransformer;

    

    public function __construct(CandidatesRepository $candidatesRepo,CandidateTransformer $candidateTransform)
    {
        $this->candidatesRepository = $candidatesRepo;
        $this->candidateTransformer   = $candidateTransform;
    }

    /**
     * @OA\Get(
     *      path="/candidates",
     *      summary="getCandidatesList",
     *      tags={"Candidates"},
     *      description="Get all Candidates",
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
     *                  @OA\Items(ref="#/components/schemas/Candidates")
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
        $candidates = $this->candidatesRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($candidates->toArray(), 'Candidates retrieved successfully');
    }

    /**
     * @OA\Post(
     *      path="/candidates",
     *      summary="createCandidates",
     *      tags={"Candidates"},
     *      description="Create Candidates",
     *      @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(ref="#/components/schemas/Candidates")
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
     *                  ref="#/components/schemas/Candidates"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateCandidatesAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        $candidates = $this->candidatesRepository->create($input);

        return $this->sendResponse($candidates->toArray(), 'Candidates saved successfully');
    }

    /**
     * @OA\Get(
     *      path="/candidates/{id}",
     *      summary="getCandidatesItem",
     *      tags={"Candidates"},
     *      description="Get Candidates",
     *      @OA\Parameter(
     *          name="id",
     *          description="id of Candidates",
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
     *                  ref="#/components/schemas/Candidates"
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
        /** @var Candidate $candidates */
        $candidates = $this->candidatesRepository->find($id);

        if (empty($candidates)) {
            return $this->sendError('Candidates not found');
        }

        return $this->sendResponse($candidates->toArray(), 'Candidates retrieved successfully');
    }

    /**
     * @OA\Put(
     *      path="/candidates/{id}",
     *      summary="updateCandidates",
     *      tags={"Candidates"},
     *      description="Update Candidates",
     *      @OA\Parameter(
     *          name="id",
     *          description="id of Candidates",
     *           @OA\Schema(
     *             type="integer"
     *          ),
     *          required=true,
     *          in="path"
     *      ),
     *      @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(ref="#/components/schemas/Candidates")
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
     *                  ref="#/components/schemas/Candidates"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateCandidatesAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var Candidate $candidates */
        $candidates = $this->candidatesRepository->find($id);

        if (empty($candidates)) {
            return $this->sendError('Candidates not found');
        }

        $candidates = $this->candidatesRepository->update($input, $id);

        return $this->sendResponse($candidates->toArray(), 'Candidates updated successfully');
    }

    /**
     * @OA\Delete(
     *      path="/candidates/{id}",
     *      summary="deleteCandidates",
     *      tags={"Candidates"},
     *      description="Delete Candidates",
     *      @OA\Parameter(
     *          name="id",
     *          description="id of Candidates",
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
        /** @var Candidate $candidates */
        $candidates = $this->candidatesRepository->find($id);

        if (empty($candidates)) {
            return $this->sendError('Candidates not found');
        }

        $candidates->delete();

        return $this->sendSuccess('Candidates deleted successfully');
    }
    /**
     * @OA\Post(
     *      path="/candidates/signup",
     *      summary="createcandidates",
     *      tags={"Auth"},
     *      description="Create candidates",
     *      @OA\RequestBody(
     *        required=true,
     *        description="Pass user credentials",
     *        @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *                required={"email","password","first_name","last_name","confirm_password","phone_no","is_agree_term","is_agree_privacy"},
     *                @OA\Property(property="first_name", type="string", example="test"),
     *                 @OA\Property(property="last_name", type="string", example="kumar"),
     *                @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *                @OA\Property(property="confirm_password", type="string", format="password", example="PassWord12345"),
     *                @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *                @OA\Property(property="phone_no", type="string", example="378378373"),
     *                @OA\Property(property="is_agree_term", type="checbox", format="checbox", example="0|1"),
     *                @OA\Property(property="is_agree_privacy", type="checbox", format="checbox", example="0|1"),
     *               )
     *        ),
     *        @OA\JsonContent(
     *                required={"email","password","first_name","last_name","confirm_password","phone_no","is_agree_term","is_agree_privacy"},
     *                @OA\Property(property="first_name", type="string", example="test"),
     *                 @OA\Property(property="last_name", type="string", example="kumar"),
     *                @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *                @OA\Property(property="confirm_password", type="string", format="password", example="PassWord12345"),
     *                @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
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
    public function signup(CreateCandidatesAPIRequest $request): JsonResponse
    {
        return $this->candidatesRepository->signup($request);
    }

    /**
     * @OA\Post(
     *      path="/candidates/login",
     *      summary="Login",
     *      tags={"Auth"},
     *      description="Login candidates",
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
        $loginUser = $this->candidatesRepository->login($request);
        
        $data = $this->candidateTransformer->transform($loginUser['data']);

        return $this->sendResponseWithStatus($data,$loginUser['message'],$loginUser['statusCode']);
    }

    /**
     * @OA\POST(
     *      path="/candidates/logout",
     *      summary="Logout the current user",
     *      tags={"Auth"},
     *      security={
     *           {"bearerAuth": {}}
     *       },
     *      description="Logout the current user",
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
    public function logout(Request $request)
    {
        return $this->candidatesRepository->logout($request);
    }
}
