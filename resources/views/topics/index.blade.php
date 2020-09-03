@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span>{{ __('My topics') }}</span>
                    <a href="{{ route('topics.create') }}" class="btn btn-primary">Create a topic</a>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Topic</th>
                                <th scope="col">Total Tweets</th>
                                <th scope="col">Last Tweet</th>
                                <th scope="col">Last Mining</th>
                                <th scope="col">On Queue</th>
                                <th scope="col">Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topics as $topic)
                                <tr>
                                    <th scope="row">{{ $topic->name }}</th>
                                    <td>{{ $topic->total_tweets }}</td>
                                    <td>{{ $topic->last_tweet ?? '-' }}</td>
                                    <td>{{ $topic->last_mining ?? '-' }}</td>
                                    <td>{{ $topic->on_queue ? 'Yes' : 'No' }}</td>
                                    <td>{{ $topic->created_at->diffForHumans() }}</td>
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
