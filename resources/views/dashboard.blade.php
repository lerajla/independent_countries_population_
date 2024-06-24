@extends('layouts.app')

@section('content')

<div class="row justify-content-center mt-5">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Welcome</div>
            <div class="card-body">
              <div class="alert alert-success text-center">
                    <p>You are logged in!</p>
                    <a class="btn btn-secondary" href="{{ route('admin.countries.populationData') }}" role="button">Generate data</a>
              </div>
            </div>
        </div>
    </div>
</div>

@endsection
