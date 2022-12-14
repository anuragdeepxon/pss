<?php

use App\Http\Controllers\API\Employer\EmployersAPIController;
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


Route::prefix('employers')->name('employers/')->group(static function() {
    Route::post('signup',[EmployersAPIController::class,'signupEmployer']);
    Route::post('login',[EmployersAPIController::class,'login']);
});
    

Route::middleware(['auth:candidates-api'])->group(function () {
        
    Route::middleware(['candidate'])->group(function () {
    });


});
Route::middleware(['auth:employers-api'])->group(function () {
    Route::middleware(['employer'])->group(function () {
        
    });
});
