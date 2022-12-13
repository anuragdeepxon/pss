<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::resource('employers', App\Http\Controllers\API\EmployersAPIController::class)
    ->except(['create', 'edit']);

Route::resource('employer/employers', App\Http\Controllers\API\Employer\EmployersAPIController::class)
    ->except(['create', 'edit'])
    ->names([
        'index' => 'employer.employers.index',
        'store' => 'employer.employers.store',
        'show' => 'employer.employers.show',
        'update' => 'employer.employers.update',
        'destroy' => 'employer.employers.destroy'
    ]);