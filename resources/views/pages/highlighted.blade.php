@extends('layouts.app')

@section('content')
    <div class="container-fluid row mx-0 px-0">
        <div class="col-12 col-lg-8 px-4">
            @include('components.graph-container')
        </div>
        <div class="col-12 col-lg-4 px-4">
            <h2>{{ $topic['text'] ?? '' }}</h2>
            <hr class="w-75 text-left ml-0">
            @include('components.graph-feature', ['label' => __('Search result type'), 'value' => $topic['result_type'] ?? '-' ])
            @include('components.graph-feature', ['label' => __('Tweets count'), 'value' => $topic['tweets_count'] ?? '-' ])
            @include('components.graph-feature', ['label' => __('Last fetch count'), 'value' => $topic['last_fetch_count'] ?? '' ])
            <hr class="w-75 text-left ml-0">
            @include('components.graph-feature', ['label' => __('People / Nodes count'), 'value' => isset($topic['graph']['nodes']) ? count($topic['graph']['nodes']) : '-'])
            @include('components.graph-feature', ['label' => __('Links / Edges count'), 'value' => isset($topic['graph']['edges']) ? count($topic['graph']['edges']) : '-' ])
        </div>
    </div>
@endsection

@section('graph-data')
    @php
        if (isset($graph['edges']) && !empty($graph['edges'])) {
            $graph['links'] = $graph['edges'];
            unset($graph['edges']);
        }

        if ($graph) {
            $graph = [
                'nodes' => array_values($graph['nodes']),
                'links' => array_values($graph['links']),
            ];
        }
    @endphp
    <script>
        var graph_data = {!! json_encode($graph ?? [], JSON_HEX_TAG) !!};
        console.log('graph_data', graph_data);
    </script>
@endsection