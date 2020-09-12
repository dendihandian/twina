@extends('layouts.app')

@section('style')
    @include('graph.style')
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    @include('graph.svg')
                </div>
                <div class="card-footer text-right">
                    {{ $topic['text'] }}
                </div>
            </div>
        </div>
        <div class="col-md-4 d-flex align-items-center">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>
                <div class="card-body">
                    @include('auth.forms.login')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    @include('graph.script')
@endsection

