@php
    $publicPath = isset($isPub) && !empty($isPub) ? 'public.' : '';
@endphp

@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="row mx-1 d-flex justify-content-between">
                <a href="{{ route($publicPath . 'topics.index') }}" class="btn btn-info mb-4">{{ __('Back to list') }}</a>
            </div>
            <div class="card">
                <div class="card-header card-header-primary d-flex align-items-center justify-content-between">
                    <span><strong>{{ $topic['text'] . '\'s' }}</strong>&nbsp;{{  __('tweets') }}</span>
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="mx-1">
                            <span class="badge badge-pill badge-info">{{ __('Count') . " : " . count($tweets) }}</span>
                        </div>
                        @if (count($tweets))
                            <div class="mx-1">
                                <a href="{{ route('topics.graph.index', ['topic' => $topicId]) }}">
                                    <span class="badge badge-pill badge-success" title="{{ __('See graph') }}"><i class="fas fa-project-diagram"></i></span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead class="">
                            <tr>
                                <th class="text-center border-top-0" scope="col">ID</th>
                                <th class="text-center border-top-0" scope="col">People</th>
                                <th class="text-center border-top-0" scope="col">Text</th>
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
</div>
@endsection
