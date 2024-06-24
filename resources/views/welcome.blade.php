@extends('layouts.app')

@section('content')
<div class="row align-items-center vh-100 text-center">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          Welcome!
        </div>
        <div class="card-body">
          <h5 class="card-title">Independent countries population levels</h5>
          <p class="card-text">
            This application retrieves the data using a public api <a href="https://restcountries.com/">https://restcountries.com/.</a>
            You need to register in order to be able to access generated data.
          </p>
        </div>
      </div>
    </div>
</div>

@endsection
