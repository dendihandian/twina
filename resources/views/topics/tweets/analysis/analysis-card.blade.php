<div class="card" id="tweets-analysis">
    <div class="card-header card-header-info d-flex justify-content-between">
        <span>{{  __('Tweets Analysis') }}</span>
        <div class="d-flex justify-content-center">
            <div class="mx-1">
                <span class="badge badge-pill badge-info">{{ __('Tweets count: ') . ($tweetsAnalysis['tweets_count'] ?? '-')}}</span>
            </div>
            @if ($tweets ?? false)
                <div class="mx-1">
                    <a href="#tweets" title="{{ __('See tweets') }}"><i class="fab fa-twitter text-white"></i></a>
                </div>
            @endif
        </div>
    </div>
    <div class="card-body">
        {{-- <div class="row d-flex justify-content-baseline">
            <span class="badge badge-pill badge-primary">{{ __('Tweets count: ') . ($tweetsAnalysis['tweets_count'] ?? '-')}}</span>
            <span class="badge badge-pill badge-info">{{ __('Tweets minimum date: ') . ($tweetsAnalysis['tweets_date_range']['min'] ?? '-')}}</span>
            <span class="badge badge-pill badge-info">{{ __('Tweets maximum date: ') . ($tweetsAnalysis['tweets_date_range']['max'] ?? '-')}}</span>
        </div> --}}
        <div class="row d-flex justify-content-center">
            <div class="analysis-wrapper my-2">
                <canvas id="most_words" width="400" height="400"></canvas>
            </div>
            <div class="analysis-wrapper my-2">
                <canvas id="most_mentions" width="400" height="400"></canvas>
            </div>
            <div class="analysis-wrapper my-2">
                <canvas id="most_replies" width="400" height="400"></canvas>
            </div>
            <div class="analysis-wrapper my-2">
                <canvas id="most_hashtags" width="400" height="400"></canvas>
            </div>
            <div class="analysis-wrapper my-2">
                <canvas id="most_langs" width="400" height="400"></canvas>
            </div>
        </div>
    </div>
</div>