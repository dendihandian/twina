@extends('layouts.app')

@section('style')
    @include('graph.style')
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header card-header-info">{{ __('Menu') }}</div>
                <div class="card-body">
                    <p><a href="{{ route('public.topics.index') }}">{{ __('Manage Public Topics') }}</a></p>
                    <p><a href="{{ route('topics.index') }}">{{ __('Manage Your Topics') }}</a></p>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header card-header-info">{{ __('Your selected topic') }}</div>
                <div class="card-body">
                    @include('graph.svg')
                </div>
                <div class="card-footer text-right">
                    {{ $topic['text'] ?? '-' }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    @include('graph.script')
@endsection
