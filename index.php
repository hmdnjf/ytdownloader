<?php

$result = file_get_contents('https://api.btclod.com/v1/youtube/extract-infos/?detail=VxvzmE2CI6Y&video=1');

$videos = json_decode($result)->data->videos;

foreach ($videos as $item) {
    echo 'test';
}