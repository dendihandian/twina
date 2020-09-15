@php
    if ($tweetsAnalysis ?? false) {
        $analysis = [];
        if ($tweetsAnalysis['most_words'] ?? false) {
            $analysis['most_words']['title'] = __('Most words');
            $analysis['most_words']['labels'] = array_column($tweetsAnalysis['most_words'], 'text');
            $analysis['most_words']['values'] = array_column($tweetsAnalysis['most_words'], 'count');
        }

        if ($tweetsAnalysis['most_mentions'] ?? false) {
            $analysis['most_mentions']['title'] = __('Most mentions');
            $analysis['most_mentions']['labels'] = array_column($tweetsAnalysis['most_mentions'], 'text');
            $analysis['most_mentions']['values'] = array_column($tweetsAnalysis['most_mentions'], 'count');
        }

        if ($tweetsAnalysis['most_replies'] ?? false) {
            $analysis['most_replies']['title'] = __('Most replies');
            $analysis['most_replies']['labels'] = array_column($tweetsAnalysis['most_replies'], 'text');
            $analysis['most_replies']['values'] = array_column($tweetsAnalysis['most_replies'], 'count');
        }

        if ($tweetsAnalysis['most_hashtags'] ?? false) {
            $analysis['most_hashtags']['title'] = __('Most hashtags');
            $analysis['most_hashtags']['labels'] = array_column($tweetsAnalysis['most_hashtags'], 'text');
            $analysis['most_hashtags']['values'] = array_column($tweetsAnalysis['most_hashtags'], 'count');
        }

        if ($tweetsAnalysis['langs_count'] ?? false) {
            $analysis['most_langs']['title'] = __('Most languages');
            $analysis['most_langs']['labels'] = array_keys($tweetsAnalysis['langs_count']);
            $analysis['most_langs']['values'] = array_values($tweetsAnalysis['langs_count']);
        }
    }
@endphp


<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script>

var colors = [
    '#A0AEC0',
    '#F56565',
    '#ED8936',
    '#ECC94B',
    '#48BB78',
    '#38B2AC',
    '#4299E1',
    '#667EEA',
    '#9F7AEA',
    '#ED64A6'
];

var analysis = {!! json_encode($analysis ?? false, JSON_HEX_TAG) !!};

if (analysis) {
    // console.log('analysis', analysis)
    _.keys(analysis).forEach((name) => {
        // console.log('analysis[name]', analysis[name]);
        var data = {
            title: analysis[name].title,
            labels: analysis[name].labels,
            values: analysis[name].values
        }

        new Chart(document.getElementById(name), {
            type: 'pie',
            data: {
            labels: data.labels,
            datasets: [{
                label: "idk",
                backgroundColor: _.slice(_.shuffle(colors), 0, data.labels.length),
                data: data.values
            }]
            },
            options: {
                title: {
                    display: true,
                    text: data.title
                },
                legend: {
                    display: true,
                    position: 'bottom'
                }
            }
        });
    });
}
</script>