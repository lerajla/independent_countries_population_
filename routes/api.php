<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CountryPopulationLevelController;

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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
  Route::get('list-all-country-population-levels', [CountryPopulationLevelController::class, 'listAllCountryPopulationLevels'])
                  ->name('listAllCountryPopulationLevels');
  Route::get('list-regions-by-population-levels/{level}', [CountryPopulationLevelController::class, 'listRegionsByPopulationLevel'])
                  ->name('listRegionsByPopulationLevel');
  Route::get('get-region-population-level/{region}', [CountryPopulationLevelController::class, 'getRegionPopulationLevel'])
                  ->name('getRegionPopulationLevel');
  Route::post('/auth/logout', [AuthController::class, 'logout']);
});
