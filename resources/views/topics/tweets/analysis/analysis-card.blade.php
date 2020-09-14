<div class="card" id="tweets-analysis">
    <div class="card-header card-header-info d-flex justify-content-between">
        <span>{{  __('Tweets Analysis') }}</span>
        <a href="#tweets" title="{{ __('See tweets') }}"><i class="fab fa-twitter text-white"></i></a>
    </div>
    <div class="card-body">
        <p>{{ __('Tweets count: ') . ($tweetsAnalysis['tweets_count'] ?? '-')}} </p>
        <p>{{ __('Tweets minimum date: ') . ($tweetsAnalysis['tweets_date_range']['min'] ?? '-')}} </p>
        <p>{{ __('Tweets maximum date: ') . ($tweetsAnalysis['tweets_date_range']['max'] ?? '-')}} </p>

        <div class="mt-2">
            <p>{{ __('Language counts:') }}</p>

            @php
                $langsCount = $tweetsAnalysis['langs_count'] ?? [];
                arsort($langsCount);
            @endphp

            <ul>
                @forelse ($langsCount as $lang => $count)
                    <li>{{ $lang }} : {{ $count}}</li>
                @empty
                    <li>-</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>