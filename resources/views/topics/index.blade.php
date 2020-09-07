@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="row d-flex justify-content-end">
                <a href="{{ route('topics.create') }}" class="btn btn-primary mb-4">Create a topic</a>
            </div>
            <div class="card">
                <div class="card-header">
                    <span>{{ __('My topics') }}</span>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Topic</th>
                                <th scope="col" width="5%">Tweet Count</th>
                                <th scope="col" class="text-center">Last Fetch Tweet</th>
                                <th scope="col" width="5%">Last Fetch Count</th>
                                <th scope="col">Last Fetch Date</th>
                                <th scope="col">Created At</th>
                                <th scope="col" class="text-center">On Queue</th>
                                <th scope="col" class="text-center" width="20%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topics as $topicId => $topic)
                                <tr>
                                    <th scope="row">{{ $topic['text'] ?? ''}}</th>
                                    <td class="text-right">{{ (isset($topic['tweets_count']) && !empty($topic['tweets_count'])) ? $topic['tweets_count'] : '-' }}</td>
                                    <td class="text-center">
                                        @if ($topic['last_fetch_tweet'])
                                            <a href="{{ 'https://twitter.com/' . $topic['last_fetch_tweet']['user']['screen_name'] . '/status/' . $topic['last_fetch_tweet']['id'] }}" target="_blank">
                                                {{ (isset($topic['last_fetch_tweet']) && !empty($topic['last_fetch_tweet'])) ? $topic['last_fetch_tweet']['id'] : '-' }}
                                            </a>
                                        @else
                                            {{ $tweet['id'] ?? '-' }}
                                        @endif
                                    </td>
                                    <td class="text-right">{{ (isset($topic['last_fetch_count']) && !empty($topic['last_fetch_count'])) ? $topic['last_fetch_count'] : '-' }}</td>
                                    <td>{{ (isset($topic['last_fetch_date']) && !empty($topic['last_fetch_date'])) ? $topic['last_fetch_date'] : '-' }}</td>
                                    <td>{{ (isset($topic['created_at']) && !empty($topic['created_at'])) ? $topic['created_at'] : '-' }}</td>
                                    <td class="text-center">{{ $topic['on_queue'] ? 'Yes' : 'No' }}</td>
                                    <td class="d-flex align-items-center justify-content-between">
                                        @if (!$topic['on_queue'])
                                            <form action="{{ route('topics.mining', ['topic' => $topicId]) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-success">Start Mining</button>
                                            </form>
                                            <a href="{{ route('topics.tweets.index', ['topic' => $topicId]) }}" class="btn btn-sm btn-info text-white">See Tweets</a>
                                        @else
                                            <span>{{ __('On queue') }}</span>
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
