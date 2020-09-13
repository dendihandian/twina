@php
    $publicPath = isset($isPub) && !empty($isPub) ? 'public.' : '';
@endphp

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <a href="{{ route($publicPath . 'topics.index') }}" class="btn btn-info text-white mb-4">{{ __('Back to list') }}</a>
            <div class="card">
                <div class="card-header card-header-primary">{{ __('Add a topic') }}</div>
                <div class="card-body">
                    <form action="{{ route($publicPath . 'topics.store') }}" method="POST">
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
                                <button class="btn btn-primary btn-round w-100" type="submit">{{ __('Add')}}<div class="ripple-container"></div></button>
                                {{-- <button type="submit" class="btn btn-primary w-100">{{ __('Save')}}</button> --}}
                            </div>
                        </div>
                        <div class="form-row mt-2">
                            <div class="col-md-12">
                                @foreach ($resultTypes as $index => $resultType)
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" name="result_type" id="{{ $resultType['value'] . '_input' }}" value="{{ $resultType['value'] }}" 
                                            @if ($index === 0)
                                                checked
                                            @endif
                                            >
                                            {{ $resultType['label'] }}
                                            <span class="circle">
                                            <span class="check"></span>
                                            </span>
                                        </label>
                                    </div>
                                    {{-- <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="result_type" id="{{ $resultType['value'] . '_input' }}" value="{{ $resultType['value'] }}"
                                        @if ($index === 0)
                                            checked
                                        @endif
                                        >
                                        <label class="form-check-label" for="{{ $resultType['value'] . '_input' }}">{{ $resultType['label'] }}</label>
                                    </div> --}}
                                @endforeach
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
