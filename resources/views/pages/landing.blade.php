@extends('layouts.app')

@section('content')
    <div class="landing-page-wrapper">
        <div class="jumbotron bg-light row d-flex align-items-center">
            <div class="col-12 col-md-6 text-center text-md-left">
                <h1>{{ __('Visualize Twitter Topics with Graph.') }}</h1>
                <hr class="my-4">
                <p class="lead">Lorem ipsum dolor sit amet consectetur adipisicing elit. Natus soluta labore velit esse itaque accusantium, eaque beatae corporis ullam, sapiente doloremque nemo vel architecto quidem iure ut fuga minima quia.</p>
                <button class="mt-4 btn btn-primary bg-teal border-0">{{ __('See the highlighted topic') }}</button>
            </div>
            <div class="d-none col-12 col-md-6 d-md-flex justify-content-center">
                <img class="rounded-lg img-fluid" src="https://dummyimage.com/600x400" alt="landing-page-image">
            </div>
        </div>
    </div>
@endsection
