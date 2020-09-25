<?php

return [
    'disable_register' => env('TWINA_DISABLE_REGISTER', true),

    'cache_paths' => [
        'topics' => 'topics',
        'topic' => 'topics.[topicId]',
        'preferences_selected_topic' => 'preferences.selected_topic',
    ],
];
