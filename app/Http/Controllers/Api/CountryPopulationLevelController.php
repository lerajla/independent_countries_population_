<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\PopulationLevelRequest;
use App\Models\CountryPopulationLevel;
use App\Models\CountryRegion;
use App\Models\PopulationLevel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CountryPopulationLevelController extends Controller
{
  /**
  * List all country population levels
  *
  * @return JsonResponse
  */

  public function listAllCountryPopulationLevels():JsonResponse {

    Log::channel('api')->info('API: list all country population levels');

    $countryPopulationLevels = CountryPopulationLevel::with('region', 'populationLevel')->get();

    return response()->json($countryPopulationLevels);

  }

  /**
  * Retrieve regions by population level.
  *
  * @return JsonResponse
  */

  public function listRegionsByPopulationLevel(Request $request, $level):JsonResponse {
    Log::channel('api')->info('API: List regions by population level');

    $result = [
      'success' => false,
      'data' => [],
      'message' => ''
    ];

    $request->merge(['level' => $request->route('level')]);

    $validator = Validator::make($request->all(), [
      'level' => 'required|exists:population_levels,name',
    ]);

    if($validator->fails()){
      $result['message'] = ucfirst($validator->errors()->first());
      return response()->json($result);
    }

    $populationLevel = PopulationLevel::whereName($level)->first()->id;
    $data = CountryPopulationLevel::ofPopulationLevel($populationLevel)->with('region', 'populationLevel')->get();

    return response()->json($data);

  }

  /**
  * Retrieve population level of a region.
  *
  * @return JsonResponse
  */

  public function getRegionPopulationLevel(Request $request, $region):JsonResponse {

    Log::channel('api')->info('API: Retrieve population level of a region.');

    $result = [
      'success' => false,
      'data' => [],
      'message' => ''
    ];

    $request->merge(['region' => $request->route('region')]);

    $validator = Validator::make($request->all(), [
      'region' => 'required|exists:country_regions,name',
    ]);

    if($validator->fails()){
      $result['message'] = ucfirst($validator->errors()->first());
      return response()->json($result);
    }

    $region = CountryRegion::whereName($region)->first()->id;
    $data = CountryPopulationLevel::ofRegion($region)->with('populationLevel')->get();

    return response()->json($data);

  }

}
