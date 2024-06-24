@extends('layouts.app')

@section('content')

<div class="row align-items-center vh-100 text-center">
    <div id="col_countries-table" class="col-12">
      @include('countries.table')
    </div>
</div>
@endsection

@section('pageScripts')
<script src="{{ asset('js/countriesManager.js') }}" type="text/javascript"></script>
<script type="text/javascript">
  const routeGetIndependentCountriesList = '{{ route('independentCountriesList') }}';

  $(document).ready(function() {
    countriesManager.init();
  });
</script>
@endsection
