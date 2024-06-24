@extends('layouts.app')

@section('content')

<div id="countries_population_levels-row" class="row align-items-center vh-100 text-center">
    <div id="col_countries-population-table" class="col-6">
      <table id="countries_population-table" class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th>Region</th>
            <th>Number of countries</th>
            <th>Average population</th>
            <th>Population level</th>
          </tr>
        </thead>
        <tbody id="countries_population-table_body"></tbody>
      </table>
    </div>
    <div id="col_countries-population-highcharts" class="col-6">
    </div>
</div>

@include('admin.countries.regions_modal')
@endsection

@section('pageScripts')
<script src="{{ asset('js/admin/countriesManager.js') }}" type="text/javascript"></script>

<script type="text/javascript">
  const routeGetCountriesPopulationData = '{{ route('admin.countries.populationData') }}';
  const routeGetRegionDetails = '{{ route('admin.countries.regionData', 'regionName') }}';

  $(document).ready(function() {
    countriesManager.init();
  });
</script>
@endsection
