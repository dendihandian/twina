@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <a href="{{ route('topics.index') }}" class="btn btn-info text-white mb-4">{{ __('Back to list') }}</a>
            <div class="card">
                <div class="card-header">{{ __('Create a topic') }}</div>
                <div class="card-body">
                    <form action="{{ route('topics.store') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="col-md-10">
                            <input name="name" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="{{ __('Enter any topic name') }}">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary form-control">{{ __('Save')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
