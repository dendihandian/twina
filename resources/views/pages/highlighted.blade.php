@extends('layouts.app')

@section('content')
    {{-- <div class="container row pt-4 pl-5">
        <div class="col-6 bg-success">
        </div>
    </div> --}}
    <div class="container-fluid row mx-0 px-0">
        <div class="col-12 col-lg-8 px-4">
            @include('components.graph-container')
        </div>
        <div class="col-12 col-lg-4 px-4">
            <h2>Miserables</h2>
            <hr class="w-75 text-left ml-0">
            @include('components.graph-feature', ['label' => __('Search result type'), 'value' => 'recent' ])
            @include('components.graph-feature', ['label' => __('Tweets count'), 'value' => 255 ])
            @include('components.graph-feature', ['label' => __('Last fetch count'), 'value' => 255 ])
            <hr class="w-75 text-left ml-0">
            @include('components.graph-feature', ['label' => __('People / Nodes count'), 'value' => 255 ])
            @include('components.graph-feature', ['label' => __('Links / Edges count'), 'value' => 255 ])
        </div>
    </div>
@endsection