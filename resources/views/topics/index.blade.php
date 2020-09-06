@extends('layouts.app')

@section('content')
<div class="container">
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
                                <th scope="col">Tweet Count</th>
                                <th scope="col">Last Tweet</th>
                                <th scope="col">Last Fetch Count</th>
                                <th scope="col">Last Fetch Date</th>
                                <th scope="col">Created At</th>
                                <th scope="col">On Queue</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topics as $topicId => $topic)
                                <tr>
                                    <th scope="row">{{ $topic['text'] ?? ''}}</th>
                                    <td>{{ (isset($topic['tweet_count']) && !empty($topic['tweet_count'])) ? $topic['tweet_count'] : '-' }}</td>
                                    <td>{{ (isset($topic['last_tweet']) && !empty($topic['last_tweet'])) ? $topic['last_tweet'] : '-' }}</td>
                                    <td>{{ (isset($topic['last_fetch_count']) && !empty($topic['last_fetch_count'])) ? $topic['last_fetch_count'] : '-' }}</td>
                                    <td>{{ (isset($topic['last_fetch_date']) && !empty($topic['last_fetch_date'])) ? $topic['last_fetch_date'] : '-' }}</td>
                                    <td>{{ (isset($topic['created_at']) && !empty($topic['created_at'])) ? $topic['created_at'] : '-' }}</td>
                                    <td>{{ $topic['on_queue'] ? 'Yes' : 'No' }}</td>
                                    <td class="d-flex align-items-center justify-content-between">
                                        @if (!$topic['on_queue'])
                                            <form action="{{ route('topics.mining', ['topic' => $topicId]) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-success">Start Mining</button>
                                            </form>
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
