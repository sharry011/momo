<?php

return [
    'feeds' => [
        'main' => [
            'items' => [\App\Models\Post::class, 'getFeedItems'],
            'url' => '/rss.xml',
            'title' => 'Latest Articles',
            'description' => 'The latest updates from shahid4u.it.com',
            'language' => 'en-US',
            'view' => 'feed::rss',
            'format' => 'rss',
        ],
    ],
];
