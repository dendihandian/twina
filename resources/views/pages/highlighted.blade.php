@extends('layouts.app')

@section('content')
    {{-- <div class="container row pt-4 pl-5">
        <div class="col-6 bg-success">
        </div>
    </div> --}}
    <div class="container-fluid row mx-0 px-0">
        <div class="col-12 col-lg-8 px-0 pt-4 pl-4">
            @include('components.graph-container')
        </div>
        <div class="col-12 col-lg-4 px-0 pt-4 pl-4">
            <h2>Miserables</h2>
            <hr class="w-75 text-left ml-0">
            <p>Tweets count: <span class="font-weight-bold">266</span></p>
            <p>Peoples count: <span class="font-weight-bold">266</span></p>
            <p>Verified peoples count: <span class="font-weight-bold">266</span></p>
            <p>Last fetched count: <span class="font-weight-bold">266</span></p>
            <p>Search result type: <span class="badge badge-pill badge-primary">Recent</span></p>
            <p>First tweet datetime: <span class="font-weight-bold">2020-12-18 12:12:12</span></p>
            <p>Last tweet datetime: <span class="font-weight-bold">2020-12-18 12:12:12</span></p>
            <hr class="w-75 text-left ml-0">
            <p>Nodes count: <span class="font-weight-bold">255</span></p>
            <p>Edges count: <span class="font-weight-bold">255</span></p>
        </div>
    </div>
@endsection