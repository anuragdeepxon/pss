<?php

use App\Http\Controllers\API\Candidates\CandidatesAPIController;
use App\Http\Controllers\API\Employer\EmployersAPIController;
use App\Models\Candidates\Candidate;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});




Route::prefix('employers')->group(function () {
   
    Route::post('signup',[EmployersAPIController::class,'signupEmployer']);
    Route::post('login',[EmployersAPIController::class,'login']);
    Route::post('logout', [EmployersAPIController::class, 'logout']);

    Route::middleware(['auth:employers-api'])->group(function () {
        
    });
});

Route::prefix('candidates')->group(function () {
    
    Route::post('signup',[CandidatesAPIController::class,'signup']);
    Route::post('login',[CandidatesAPIController::class,'login']);
    Route::post('forget-password-otp-send',[CandidatesAPIController::class,'forgetPasswordOtpSend']);
    Route::post('otp-verify',[CandidatesAPIController::class,'otpVerify']);
    Route::post('set-new-password',[CandidatesAPIController::class,'setNewPassword']);
    
    Route::middleware(['auth:candidates-api'])->group(function () {       
        Route::post('logout', [CandidatesAPIController::class, 'logout']);
    });
    
    
});

