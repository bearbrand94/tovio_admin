<?php

$bucket = "";
if (getenv('APP_ENV') == 'local'){
    $bucket = 'jci-indo';
}

if (getenv('APP_ENV') == 'staging'){
    $bucket = 'jci-indo';
}

if (getenv('APP_ENV') == 'production'){
    $bucket = 'jci-indo';
}

return array (
    // the bucket destination for aws
    'bucket' => $bucket,

    'user_path' => 'user/',
    'post_path' => 'post/',
);