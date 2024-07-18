<?php

if (file_exists(__DIR__ . '/vendor/autoload.php')) :
    require __DIR__ . '/vendor/autoload.php';
else :
    header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
    echo 'Did you install the dependencies ? ☺';
    exit(1);
endif;

use App\Client as pb;
$pb = new pb('http://pktb_pocketbase:8080');
$response = $pb->collection('countries')->getList();
$names = array_column( $response['items'], 'name');
var_dump($names);

$response = $pb->collection('players')->getList();
$names = array_column( $response['items'], 'name');
var_dump($names);
