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
            <div class="card">
                <div class="card-body">
                    @if ($graphAvailable = (isset($topic['graph']) && !empty($topic['graph'])))
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
            <div class="card h-100">
                <div class="card-header">Information</div>
                <div class="card-body">
                    @php
                        $generateText = !$graphAvailable ? 'Generate graph' : 'Re-generate graph'; 
                    @endphp
                    <form action="{{ route($publicPath . 'topics.analysis.store', ['topic' => $topicId]) }}" method="POST">
                        @csrf
                        <button 
                            @if ($onAnalyze = (isset($topic['on_analyze']) && !empty($topic['on_analyze'])))
                            disabled
                            @endif
                            type="submit" class="btn btn-primary form-control">{{ __(!$onAnalyze ? $generateText : 'processing...') }}</button>
                    </form>
                    {{-- <form action="{{ route($publicPath . 'topics.analysis.complement_graph', ['topic' => $topicId]) }}" method="POST" class="mt-2">
                        @csrf
                        <button 
                            @if ($onAnalyze || $onComplement = (isset($topic['on_complement_graph']) && !empty($topic['on_complement_graph'])))
                                disabled
                            @endif
                            type="submit" class="btn btn-success form-control">{{ __((!$onAnalyze && !$onComplement) ? 'Complement graph' : 'processing...') }}</button>
                    </form> --}}
                    <form action="{{ route($publicPath . 'topics.selected.store', ['topic' => $topicId]) }}" method="POST" class="mt-2">
                        @csrf
                        <button type="submit" class="btn btn-warning form-control">{{ __('Set as selected topic') }}</button>
                    </form>
                    <a href="{{ route($publicPath . 'topics.index', ['topic' => $topicId]) }}" class="btn btn-info form-control mt-2 text-white">
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