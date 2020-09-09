@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="row d-flex justify-content-end">
                <a href="{{ route('public.topics.create') }}" class="btn btn-primary mb-4">{{ __('Add a public topic') }}</a>
            </div>
            <div class="card">
                <div class="card-header">
                    <span>{{ __('Public topics') }}</span>
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
                                <th scope="col" rowspan="2" class="align-middle text-center" width="20%">Actions</th>
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
                                        @if ($topic['last_fetch_tweet'])
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
                                    <td class="align-middle text-center">{{ $topic['on_queue'] ? 'Yes' : 'No' }}</td>
                                    <td class="d-flex align-items-center justify-content-between">
                                        @if (!$topic['on_queue'])
                                            <form action="{{ route('public.topics.mining', ['topic' => $topicId]) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-success">Start Mining</button>
                                            </form>
                                        @endif
                                        @if (isset($topic['tweets']) && !empty($topic['tweets']))
                                            <a href="{{ route('public.topics.tweets.index', ['topic' => $topicId]) }}" class="btn btn-sm btn-info text-white">See Tweets</a>
                                            <a href="{{ route('public.topics.analysis.index', ['topic' => $topicId]) }}" class="btn btn-sm btn-danger text-white">See Analysis</a>
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
