<?php

namespace App\Repositories;

use Exception;
use Illuminate\Container\Container as Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use InfyOm\Generator\Utils\ResponseUtil;

abstract class BaseRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->makeModel();
    }

    /**
     * Get searchable fields array
     */
    abstract public function getFieldsSearchable(): array;

    /**
     * Configure the Model
     */
    abstract public function model(): string;

    /**
     * Make Model instance
     *
     * @throws \Exception
     *
     * @return Model
     */
    public function makeModel()
    {
        $model = app($this->model());

        if (!$model instanceof Model) {
            throw new \Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    /**
     * Paginate records for scaffold.
     */
    public function paginate(int $perPage, array $columns = ['*']): LengthAwarePaginator
    {
        $query = $this->allQuery();

        return $query->paginate($perPage, $columns);
    }

    /**
     * Build a query for retrieving all records.
     */
    public function allQuery(array $search = [], int $skip = null, int $limit = null): Builder
    {
        $query = $this->model->newQuery();

        if (count($search)) {
            foreach ($search as $key => $value) {
                if (in_array($key, $this->getFieldsSearchable())) {
                    $query->where($key, $value);
                }
            }
        }

        if (!is_null($skip)) {
            $query->skip($skip);
        }

        if (!is_null($limit)) {
            $query->limit($limit);
        }

        return $query;
    }

    /**
     * Retrieve all records with given filter criteria
     */
    public function all(array $search = [], int $skip = null, int $limit = null, array $columns = ['*']): Collection
    {
        $query = $this->allQuery($search, $skip, $limit);

        return $query->get($columns);
    }

    /**
     * Create model record
     */
    public function create(array $input): Model
    {
        if (array_key_exists("password", $input)) {
            $input['password'] = Hash::make($input['password']);
        }

        $model = $this->model->newInstance($input);

        $model->save();

        return $model;
    }

    /**
     * Find model record for given id
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model|null
     */
    public function find(int $id, array $columns = ['*'])
    {
        $query = $this->model->newQuery();

        return $query->find($id, $columns);
    }

    /**
     * Update model record for given id
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model
     */
    public function update(array $input, int $id)
    {
        $query = $this->model->newQuery();

        $model = $query->findOrFail($id);

        $model->fill($input);

        $model->save();

        return $model;
    }

    /**
     * @throws \Exception
     *
     * @return bool|mixed|null
     */
    public function delete(int $id)
    {
        $query = $this->model->newQuery();

        $model = $query->findOrFail($id);

        return $model->delete();
    }

    public function sendResponse($result, $message, $statusCode)
    {
        $result['statusCode'] = $statusCode;
        return response()->json(ResponseUtil::makeResponse($message, $result));
    }

    public function login($request)
    {

        $user = $this->model->where(['email' => $request->email])->first();
        $guard = $this->model->guard;
        $result = [];
        if (!$user) {
            return $this->sendResponse($result, $this->model->message['not_exist'], 401);
        } elseif (!Hash::check($request->password, $user->password)) {
            return $this->sendResponse($result, $this->model->message['wrong_password'], 401);
        }

        if ($user) {
            $user = Auth::guard("$guard")->attempt(['email' => $request->email, 'password' => $request->password]);

            if ($user) {

                $loginuser = Auth::guard("$guard")->user();

                $getToken = $loginuser->createToken('API Token')->accessToken;

                $loginuser['userToken'] = $getToken;

                return $this->sendResponse($loginuser, $this->model->message['login'], 200);
            }
        } else {
            return $this->sendResponse($result, 'something went wrong', 500);
        }
    }

    public function signup($request)
    {
        $input = $request->all();
        $users = $this->create($input);

        if ($users) {
            $token = $users->createToken('API Token')->accessToken;
            $users['userToken'] = $token;
            $users['classType'] = get_class($users);
            return $this->sendResponse($users, $this->model->message['signup'], 200);
        } else {
            return $this->sendResponse($users, 'User not signup succesfully', 500);
        }
    }
}
