@php
    $publicPath = isset($isPub) && !empty($isPub) ? 'public.' : '';
@endphp

@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="row mx-1 d-flex justify-content-between mb-4">
                <a href="{{ route($publicPath . 'topics.index') }}" class="btn btn-info mb-4">{{ __('Back to list') }}</a>
                <div class="d-flex justify-content-center">
                <form action="{{ route($publicPath . 'topics.tweets.mine', ['topic' => $topicId]) }}" method="POST">
                    @csrf
                    <button class="btn btn-default" type="submit">{{ __('Start Mining') }}</button>
                </form>
                <form action="{{ route($publicPath . 'topics.tweets.analyze', ['topic' => $topicId]) }}" method="POST">
                    @csrf
                    <button class="btn btn-primary" type="submit">{{ __('Analyze the tweets') }}</button>
                </form>
                </div>
            </div>
            <div class="card" id="tweets">
                <div class="card-header card-header-primary d-flex align-items-center justify-content-between">
                    <span><strong>{{ ($topic['text'] ?? '') . '\'s' }}</strong>&nbsp;{{  __('tweets') }}</span>
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="mx-1">
                            <span class="badge badge-pill badge-info">{{ __('Count') . " : " . count($tweets) }}</span>
                        </div>
                        @if (count($tweets))
                            <div class="mx-1">
                                <a href="{{ route($publicPath . 'topics.graph.index', ['topic' => $topicId]) }}">
                                    <span class="badge badge-pill badge-success" title="{{ __('See graph') }}"><i class="fas fa-project-diagram"></i></span>
                                </a>
                            </div>
                        @endif
                        @if (!empty($tweetsAnalysis))
                        <div class="mx-1">
                            <a href="#tweets-analysis">
                                <span class="badge badge-pill badge-info" title="{{ __('See analysis') }}">
                                    <i class="fas fa-chart-pie"></i>
                                </span>
                            </a>

                        </div>

                        @endif

                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead class="">
                            <tr>
                                <th class="text-center text-secondary border-top-0" scope="col">ID</th>
                                <th class="text-center text-secondary border-top-0" scope="col">People</th>
                                <th class="text-center text-secondary border-top-0" scope="col">Text</th>
                            </tr>
                        </thead>
                        <tbody class="table-striped">
                            @foreach ($tweets as $tweet)
                                <tr>
                                    <th scope="row">
                                        @if ($tweet['user'])
                                            <a href="{{ 'https://twitter.com/' . $tweet['user']['screen_name'] . '/status/' . $tweet['id'] }}" target="_blank">
                                                {{ $tweet['id'] ?? '-' }}
                                            </a>
                                        @else
                                            {{ $tweet['id'] ?? '-' }}
                                        @endif
                                    </th>
                                    <td scope="row">
                                        @if ($tweet['user'])
                                            <a href="{{ 'https://twitter.com/' . $tweet['user']['screen_name'] }}" target="_blank">
                                                {{ $tweet['user']['screen_name'] ?? '-' }}
                                            </a>
                                        @endif
                                    </td>
                                    <td scope="row">{{ $tweet['text'] ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row d-flex justify-content-center">
        <div class="col-md-10">
            @include('topics.tweets.analysis.analysis-card')
        </div>
    </div>
</div>
@endsection

@section('script')
    @include('charts.script')
@endsection