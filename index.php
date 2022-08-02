<?php
include 'functions.php';

date_default_timezone_set('Asia/Tehran');

$path = "https://api.telegram.org/bot5470506972:AAG5haKY329tYlJZRBQmSZ8AWA6k2Lh7I10";

$update = json_decode(file_get_contents("php://input"), TRUE);

$chatId = $update["message"]["chat"]["id"];
$messageText = $update["message"]["text"];
$messageId = $update["message"]["message_id"];
$userId = $update["message"]["from"]["id"];

// preg_match("/youtube.com\/watch\?v=(\w+)/", 'https://www.youtube.com/watch?v=VxvzmE2CI6Y', $videoId);

// SendMessage('1283437650', $videoId[1]);


if (true) {
    preg_match("/youtube.com\/watch\?v=(\w+)/", $messageText, $videoId);

    SendMessage('1283437650', $videoId[1]);
    $result = file_get_contents('https://api.btclod.com/v1/youtube/extract-infos/?detail=' + $videoId[1] + '&video=1');

    $title = json_decode($result)->data->detail->title;
    echo $title;

    $videos = json_decode($result)->data->videos;
}


// foreach ($videos as $item) {
//     SendMessage('1283437650', ($item->url));
// }