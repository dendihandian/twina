@php
    $publicPath = isset($isPub) && !empty($isPub) ? 'public.' : '';
@endphp

@extends('layouts.app')

@section('style')
    @include('graph.style')
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="card mt-0">
                <div class="card-body">
                    @if ($graphAvailable = (isset($graph) && !empty($graph)))
                        @include('graph.svg')
                    @else
                        {{ __('No graph available. Please generate it first.') }}
                    @endif
                </div>
                <div class="card-footer text-right">
                    {{ $topic['text'] ?? '-' }}
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header card-header-info">{{ __('Actions') }}</div>
                <div class="card-body">
                    @php
                        $generateText = !$graphAvailable ? 'Generate graph' : 'Regenerate graph'; 
                    @endphp
                    <form action="{{ route($publicPath . 'topics.graph.generate', ['topic' => $topicId]) }}" method="POST">
                        @csrf
                        <button 
                            @if ($onGenerate = (isset($topic['on_generate']) && !empty($topic['on_generate'])))
                            disabled
                            @endif
                            type="submit" class="btn btn-primary btn-round w-100">{{ __(!$onGenerate ? $generateText : 'processing...') }}</button>
                    </form>
                    {{-- <form action="{{ route($publicPath . 'topics.graph.normalize', ['topic' => $topicId]) }}" method="POST" class="mt-2">
                        @csrf
                        <button
                            @if ($onGenerate || $onNormalize = (isset($topic['on_normalize_graph']) && !empty($topic['on_normalize_graph'])))
                                disabled
                            @endif
                            type="submit" class="btn btn-success btn-round w-100">{{ __((!$onGenerate && !$onNormalize) ? 'Normalize graph' : 'processing...') }}</button>
                    </form> --}}
                    <form action="{{ route($publicPath . 'topics.graph.analyze', ['topic' => $topicId]) }}" method="POST" class="mt-2">
                        @csrf
                        <button
                            type="submit" class="btn btn-success btn-round w-100">{{ __('Analyze graph') }}</button>
                    </form>
                    <form action="{{ route($publicPath . 'topics.selected.store', ['topic' => $topicId]) }}" method="POST" class="mt-2">
                        @csrf
                        <button type="submit" class="btn btn-rose text-white btn-round w-100">{{ __('Set as selected topic') }}</button>
                    </form>
                    <a href="{{ route($publicPath . 'topics.index', ['topic' => $topicId]) }}" class="btn btn-info btn-round w-100 mt-2 text-white">
                        {{ __('Back to list') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    @include('graph.script')
@endsection