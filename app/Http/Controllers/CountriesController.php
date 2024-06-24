<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CountryPopulationLevel;
use App\Models\CountryRegion;
use App\Models\PopulationLevel;
use App\Libraries\CountriesDispatcherBusiness;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CountriesController extends Controller
{
  /**
   * Retrieve independent countries list.
   *
   * @return JsonResponse|View
   */
  public function independentCountriesList(Request $request)
  {
    if ($request->ajax()) {

      Log::channel('app')->info('Get independent countries list');
      $countriesDispatcherBusiness = new CountriesDispatcherBusiness();
      $dispatcherDto = $countriesDispatcherBusiness->listindependentCountries();

      $result = [
        'success' => false,
        'data' => [],
        'message' => ''
      ];
      if ($dispatcherDto->isSuccess()) {
        $data = $dispatcherDto->getData();
        $data = collect($data)->map(function ($item, $key) {
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
        if (isset($data)) {
          $result = [
            'success' => true,
            'data' => $data
          ];
          return response()->json($result);
        }
      } else {
        $result['message'] = $dispatcherDto->getMessage();
      }
      return response()->json($result);
    }
    return view('countries.index');
  }
}
