<?php
include 'functions.php';

date_default_timezone_set('Asia/Tehran');

$path = "https://api.telegram.org/bot5470506972:AAG5haKY329tYlJZRBQmSZ8AWA6k2Lh7I10";

$update = json_decode(file_get_contents("php://input"), TRUE);

$chatId = $update["message"]["chat"]["id"];
$messageText = $update["message"]["text"];
$messageId = $update["message"]["message_id"];
$userId = $update["message"]["from"]["id"];
$callback = $update['callback_query'];
$callbackUserId = $callback['from']['id'];

// $vid = sendVideo('1283437650', 'http://dl16.btclod.com/v1/youtube/download/?file_id=QjExTGdOLWRXQkU6dmlkZW86MjI6NzIwcDptcDQ=');
if ($callback) {
    // sendMessage('1283437650', json_encode($callback['data']));
    $preparingVideo = sendMessage($callbackUserId, 'درحال پردازش ویدیو');

    $result = file_get_contents("https://api.btclod.com/v1/youtube/get-download-link/?file_id=" . $callback['data']);

    $video = json_decode($result)->dl_url;

    editMessageText($callbackUserId, $preparingVideo, 'درحال ارسال ویدیو');

    sendMessage($callbackUserId, $video);

    $vid = sendVideo($callbackUserId, ($video));
    // sendMessage('1283437650', json_encode($video));
    sendMessage($callbackUserId, json_encode($vid));
}

// $preparingMessage = sendMessage('1283437650', 'asd');
// editMessageText('1283437650', $preparingMessage, 'dsa');

// preg_match("/youtube.com\/watch\?v=(\w+)/", 'https://www.youtube.com/watch?v=VxvzmE2CI6Y', $videoId);

// $result = file_get_contents('https://api.btclod.com/v1/youtube/extract-infos/?detail=VxvzmE2CI6Y&video=1');
// $title = json_decode($result)->data->detail->title;

if ($messageText) {
    if (preg_match("/youtube.com\/watch\?v=(\S+)/", $messageText)) {
        preg_match("/youtube.com\/watch\?v=(\S+)/", $messageText, $videoId);
    } else if (preg_match("/youtu.be\/(\S+)/", $messageText)) {
        preg_match("/youtu.be\/(\S+)/", $messageText, $videoId);
    } else {
        return;
    }

    $preparingMessage = sendMessage($userId, 'درحال ارسال درخواست');
    // SendMessage('1283437650', $videoId[1]);
    $videoId = $videoId[1];

    editMessageText($userId, $preparingMessage, 'درحال دریافت اطلاعات');

    $result = file_get_contents("https://api.btclod.com/v1/youtube/extract-infos/?detail=$videoId&video=1");

    deleteMessage($userId, $preparingMessage);

    $title = json_decode($result)->data->detail->title;

    $videos = json_decode($result)->data->videos;

    $keyboard = array(
        "inline_keyboard" => array(
            array()
        )
    );

    $i = 0;

    foreach ($videos as $item) {
        if (true) {
            $i++;
            $file_size = formatBytes($item->file_size);
            $array = array("text" => $item->format_note . ' ' . $item->extension . ' ' . $file_size, "callback_data" => $item->id);
            array_push($keyboard['inline_keyboard'][count($keyboard['inline_keyboard']) - 1], $array);
            if ($i % 2 == 0) {
                array_push($keyboard['inline_keyboard'], array());
            }
        }
    }

    $keyboard = json_encode($keyboard, true);

    echo sendMessageWithKeyboard($userId, $title, $keyboard);
}

function formatBytes($size, $precision = 2)
{
    $base = log($size, 1024);
    $suffixes = array('', 'KB', 'MB', 'GB', 'TB');

    return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
}
