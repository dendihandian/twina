@php
    $publicPath = isset($isPub) && !empty($isPub) ? 'public.' : '';
@endphp

@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="row d-flex justify-content-between">
                <a href="{{ route('home') }}" class="btn btn-info text-white mb-4">{{ __('Back to home') }}</a>
                <a href="{{ route($publicPath . 'topics.create') }}" class="btn btn-primary mb-4">{{ __('Add a topic') }}</a>
            </div>
            <div class="card">
                <div class="card-header">
                    <span>{{ __('Topics') }}</span>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col" rowspan="2" class="align-middle">Topic</th>
                                <th scope="col" rowspan="2" class="align-middle">Result Type</th>
                                <th scope="col" rowspan="2" class="align-middle">Tweet Count</th>
                                <th scope="col" colspan="3" class="align-middle text-center">Last Fetch</th>
                                <th scope="col" rowspan="2" class="align-middle">Created At</th>
                                <th scope="col" rowspan="2" class="align-middle text-center">On Queue</th>
                                <th scope="col" rowspan="2" class="align-middle text-center" width="5%">Actions</th>
                            </tr>
                            <tr>
                                <th scope="col" class="align-middle text-center">Tweet</th>
                                <th scope="col" class="align-middle">Count</th>
                                <th scope="col" class="align-middle">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topics as $topicId => $topic)
                                <tr>
                                    <th scope="row" class="align-middle">{{ $topic['text'] ?? ''}}</th>
                                    <th scope="row" class="align-middle">{{ $topic['result_type'] ?? 'recent'}}</th>
                                    <td class="align-middle text-right">{{ (isset($topic['tweets_count']) && !empty($topic['tweets_count'])) ? $topic['tweets_count'] : '-' }}</td>
                                    <td class="align-middle text-center">
                                        @if (isset($topic['last_fetch_tweet']) && !empty($topic['last_fetch_tweet']))
                                            <a href="{{ 'https://twitter.com/' . $topic['last_fetch_tweet']['user']['screen_name'] . '/status/' . $topic['last_fetch_tweet']['id'] }}" target="_blank">
                                                {{ (isset($topic['last_fetch_tweet']) && !empty($topic['last_fetch_tweet'])) ? $topic['last_fetch_tweet']['id'] : '-' }}
                                            </a>
                                        @else
                                            {{ $tweet['id'] ?? '-' }}
                                        @endif
                                    </td>
                                    <td class="align-middle text-right">{{ (isset($topic['last_fetch_count']) && !empty($topic['last_fetch_count'])) ? $topic['last_fetch_count'] : '-' }}</td>
                                    <td class="align-middle">{{ (isset($topic['last_fetch_date']) && !empty($topic['last_fetch_date'])) ? $topic['last_fetch_date'] : '-' }}</td>
                                    <td class="align-middle">{{ (isset($topic['created_at']) && !empty($topic['created_at'])) ? $topic['created_at'] : '-' }}</td>
                                    <td class="align-middle text-center">{{ (isset($topic['on_queue']) && !empty($topic['on_queue'])) ? 'Yes' : 'No' }}</td>
                                    <td class="d-flex align-items-center justify-content-center">
                                        @if (!(isset($topic['on_queue']) && !empty($topic['on_queue'])))
                                            <div class="p-1">
                                                <form id="{{ 'formMining' . $topicId }}" action="{{ route($publicPath . 'topics.mining', ['topic' => $topicId]) }}" method="POST">
                                                    @csrf
                                                    <a
                                                    onclick="document.getElementById('{{ 'formMining' . $topicId }}').submit();"
                                                    class="text-dark" type="submit"><i class="fas fa-hammer" title="{{ __('Start mining') }}"></i></a>
                                                </form>
                                            </div>
                                        @endif
                                        @if (isset($topic['tweets']) && !empty($topic['tweets']))
                                            <div class="p-1">
                                                <a class="text-primary" href="{{ route($publicPath . 'topics.tweets.index', ['topic' => $topicId]) }}"><i class="fab fa-twitter" title="{{ __('See tweets') }}"></i></a>
                                            </div>
                                            <div class="p-1">
                                                <a class="text-success" href="{{ route($publicPath . 'topics.analysis.index', ['topic' => $topicId]) }}"><i class="fas fa-project-diagram" title="{{ __('See analysis') }}"></i></a>
                                            </div>
                                        @endif
                                    </td>
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
