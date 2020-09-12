@php
    $publicPath = isset($isPub) && !empty($isPub) ? 'public.' : '';
@endphp

@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="row d-flex justify-content-start">
                <a href="{{ route($publicPath . 'topics.index') }}" class="btn btn-primary mb-4">{{ __('Back to list') }}</a>
            </div>
            <div class="card">
                <div class="card-header">
                    <span><strong>{{ $topic['text'] . '\'s' }}</strong>&nbsp;{{  __('tweets') }}</span>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">People</th>
                                <th scope="col">Text</th>
                            </tr>
                        </thead>
                        <tbody>
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
