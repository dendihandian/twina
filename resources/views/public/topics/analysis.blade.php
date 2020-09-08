@extends('layouts.app')

@section('style')
    @include('graph.style')
@endsection

@section('content')
<div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body">
                        @include('graph.svg')
                    </div>
                    <div class="card-footer text-right">
                        {{ $topic['text'] ?? '-' }}
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-header">Information</div>
                    <div class="card-body">
                        <form action="{{ route('public.topics.analysis.store', ['topic' => $topicId]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary form-control">Analyze</button>
                        </form>
                        <a href="{{ route('public.topics.index', ['topic' => $topicId]) }}" class="btn btn-info form-control mt-2 text-white">{{ __('Back to list') }}</a>
                    </div>
                </div>
            </div>
        </div>
</div>
@endsection

@section('script')
    <script>
        var graph = {!! json_encode($graph ?? [], JSON_HEX_TAG) !!};
        console.log('graph', graph);
    </script>
    @include('graph.script')
@endsection