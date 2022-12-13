<?php

namespace App\Http\Controllers\API\Employer;

use App\Http\Requests\API\Employer\CreateEmployersAPIRequest;
use App\Models\Employer\Employers;
use App\Repositories\Employer\EmployersRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\Employer\UpdateEmployersAPIRequest;

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
     *      path="/employers",
     *      summary="createEmployers",
     *      tags={"Employers"},
     *      description="Create Employers",
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
    public function store(CreateEmployersAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        $employers = $this->employersRepository->create($input);

        return $this->sendResponse($employers->toArray(), 'Employers saved successfully');
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
