<?php

return [
    'endpointHost' => 'trialbot-api.line.me',
    'channel'      => [
        'id'     => $_ENV['LINE_CHANNEL_ID'],
        'secret' => $_ENV['LINE_CHANNEL_SECRET'],
        'mid'    => $_ENV['LINE_BOT_MID'],
    ]
];
