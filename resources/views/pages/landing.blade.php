@extends('layouts.app')

@section('style')
    @include('graph.style')
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-0">
                <div class="card-body">
                    @include('graph.svg')
                </div>
                <div class="card-footer text-right">
                    @if (isset($topic['text']) && !empty($topic['text']))
                        @php
                            $topicUrl = "https://twitter.com/search?q=" . urlencode($topic['text'])
                        @endphp
                        <a target="_blank" href="{{ $topicUrl }}">{{ $topic['text'] }}</a>
                    @else
                        <span>-</span>
                    @endif
                </div>
            </div>
        </div>
        @guest
        <div class="col-md-4 d-flex align-items-center">
            @include('ui.login-card')
        </div>
        @endguest
    </div>
</div>
@endsection

@section('script')
    @include('graph.script')
@endsection

