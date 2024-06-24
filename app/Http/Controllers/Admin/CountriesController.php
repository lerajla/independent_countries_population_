<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CountryPopulationLevel;
use App\Models\CountryRegion;
use App\Models\PopulationLevel;
use App\Libraries\CountriesDispatcherBusiness;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CountriesController extends Controller
{

    private $apiData;

    public function __construct()
    {
      $this->apiData = [];
    }

      /**
     * Retrieve independent countries list.
     *
     * @return JsonResponse|View
     */
    public function countriesPopulationData(Request $request){

      Log::channel('app')->info('Admin: Get independent countries list');

      if($request->ajax()){
        $populationLevels = PopulationLevel::all();
        $countryRegions = CountryRegion::pluck('id', 'name')->toArray();
        $thresholdLevels = $populationLevels->pluck('threshold', 'name')->toArray();

        $checkCountryRegions = $this->checkCountryRegions($countryRegions);
        $checkCountryPopulationLevels = $this->checkCountryPopulationLevels($thresholdLevels, $populationLevels);

        $result = [
          'success' => false
        ];

        if($checkCountryRegions && $checkCountryPopulationLevels){
          $countryPopulationLevels = CountryPopulationLevel::with('region', 'populationLevel')->get();

          $dataRes = $countryPopulationLevels->map(function ($item, $key) {
            $item->population_level_name = $item->populationLevel->name;
            $item->region_name = $item->region->name;
            unset($item->country_region_id, $item->population_level_id, $item->id, $item->region, $item->populationLevel);
            return $item;
          });

          $result = [
            'success' => true,
            'data' => $dataRes,
          ];
          return response()->json($result);
        }
        return response()->json($result);
      }
      return view('admin.countries.list');
    }

      /**
     * Check country population levels.
     *
     * @return bool
     */

    private function checkCountryPopulationLevels($thresholdLevels, $populationLevels): bool{
      Log::channel('app')->info('Admin: Check country population levels');

      $countryRegions = CountryRegion::pluck('id', 'name')->toArray();
      $countryPopulationLevels = CountryPopulationLevel::all();
      $data = $this->apiData;

      if(!count($countryPopulationLevels)){
        $result = false;
        if(!count($data)){
          $data = $this->getApiData();
        }
        $lowThreshold = $thresholdLevels['low'];
        $mediumThreshold = $thresholdLevels['medium'];

        $countriesByRegion = collect($data)->groupBy('region');
        $countriesByRegion = $countriesByRegion->map(function ($item, $key) use($lowThreshold, $mediumThreshold) {
          $item->population_levels = $item->groupBy('population_level')->map->count();
          $item->avgPopulation = $item->avg('population');
          $item->population_level = $item->avgPopulation <= $lowThreshold ? 'low' : ($item->avgPopulation <= $mediumThreshold ? 'medium' : 'high');
          return $item;
        });

        $dbRows = [];
        $populationLevels = $populationLevels->pluck('id', 'name')->toArray();

        foreach ($countriesByRegion as $key => $countryByRegion) {
          $dbRows[] = [
            'count_value' => $countryByRegion->count(),
            'avg_population' => number_format($countryByRegion->avgPopulation, 2, '.', ''),
            'population_level_id' => $populationLevels[$countryByRegion->population_level],
            'country_region_id' => $countryRegions[$key],
          ];
        }

        $result = DB::table('country_population_levels')->insert($dbRows);
        return $result;
      }
      return true;
    }

      /**
     * Check country regions.
     *
     * @return bool
     */
    private function checkCountryRegions($countryRegions): bool{
      Log::channel('app')->info('Admin: Check country regions');

      if(!count($countryRegions)){
        $result = false;
        $regions = $this->getRegions();
        $res = DB::table('country_regions')->insert($regions);
        if($res && count($regions)){
          Log::channel('app')->info('Admin: Regions successfully stored');
          $result = true;
        }else{
          Log::channel('app')->error('Admin: Error storing regions');
        }
        return $result;
      }

      return true;
    }

    /**
   * Get regions
   *
   * @return array
   */
    private function getRegions(): array{
      Log::channel('app')->info('Admin: Get regions data');

      $dbRows = [];
      $data = $this->getApiData();
      if(isset($data)){
        $regions = collect($data)->pluck('region')->unique()->values()->toArray();
        foreach ($regions as $key => $region) {
          $dbRows[$key] = [
            'name' => $region
          ];
        }
      }

      return $dbRows;
    }

      /**
     * Get api data
     *
     * @return array|Collection
     */
    private function getApiData(){
      Log::channel('app')->info('Admin: Get api data');

      $countriesDispatcherBusiness = new CountriesDispatcherBusiness();
      $dispatcherDto = $countriesDispatcherBusiness->listindependentCountries();
      $data = [];
      if ($dispatcherDto->isSuccess()) {
        $data = $dispatcherDto->getData();
        $this->apiData = $data;
      }

      return $data;
    }


      /**
      * Get countries region data
      *
      * @return JsonResponse
      */
    public function countriesRegionData(Request $request, $region): JsonResponse{
      Log::channel('app')->info('Admin: Get countries region data');

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

      $countriesDispatcherBusiness = new CountriesDispatcherBusiness();
      $dispatcherDto = $countriesDispatcherBusiness->getRegionDetails($region);

      if ($dispatcherDto->isSuccess()) {
        $data = $dispatcherDto->getData();
        if(isset($data)){
          $data = collect($data)->map(function ($item, $key){
            $item->fields_to_show = [
              'name' => isset($item->name->official) ? $item->name->official : '',
              'region' => isset($item->region) ? $item->region : '',
              'subregion' => isset($item->subregion) ? $item->subregion : '',
              'flag' => isset($item->flags->svg) ? $item->flags->svg : '',
              'languages' => isset($item->languages) ? implode(',', (array)$item->languages) : '',
              'population' => isset($item->population) ? $item->population : ''
            ];
            return $item;
          });
          $result = [
            'success' => true,
            'data' => $data
          ];
          return response()->json($result);
        }
      }else{
        $result['message'] = $dispatcherDto->getMessage();
      }
      return response()->json($result);
    }
}
