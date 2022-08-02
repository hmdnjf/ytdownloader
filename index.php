<?php
include 'functions.php';

$result = file_get_contents('https://api.btclod.com/v1/youtube/extract-infos/?detail=VxvzmE2CI6Y&video=1');

$title = json_decode($result)->data->detail->title;
echo $title;
SendMessage('1283437650', $title);

$videos = json_decode($result)->data->videos;

// foreach ($videos as $item) {
//     SendMessage('1283437650', ($item->url));
// }