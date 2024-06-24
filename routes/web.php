<?php

use App\Http\Controllers\CountriesController;
use App\Http\Controllers\Admin\CountriesController as AdminCountriesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');


Route::middleware('guest')->group(function () {
  Route::get('independent-countries-list', [CountriesController::class, 'independentCountriesList'])->name('independentCountriesList');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->as('admin.countries.')->group(function () {
    Route::get('get-population-data', [AdminCountriesController::class, 'countriesPopulationData'])->name('populationData');
    Route::get('get-region-data/{region}', [AdminCountriesController::class, 'countriesRegionData'])->name('regionData');
});

require __DIR__.'/auth.php';
