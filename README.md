# LineBot

```
<?php
require_once 'LineBot.php';
require_once 'LineSend.php';
require_once 'LineRecv.php';

$config = [
    'endpointHost' => 'trialbot-api.line.me',
    'channel'      => [
        'id'     => $_ENV['LINE_CHANNEL_ID'],
        'secret' => $_ENV['LINE_CHANNEL_SECRET'],
        'mid'    => $_ENV['LINE_BOT_MID'],
    ]
];

$lineRecv = new LineRecv($config);
$response = $lineRecv->receive();

$lineSender = new LineSend($config);
foreach ($response->result as $result) {
    $userId = $result->content->from;
    $text   = $result->content->text;
    $result = $lineSender->sendText([ $userId ], $text);
}
```