<?php

# Errors

error_reporting(E_ALL);
ini_set('display_errors', 1);

# Headers setup

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");
header('Content-Type: application/json; charset=utf-8');

# Composer & vendor

if (file_exists(__DIR__ . '/vendor/autoload.php')) :
    require __DIR__ . '/vendor/autoload.php';
else :
    header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
    echo 'Did you install the dependencies ? ☺';
    exit(1);
endif;

# Namespaces

use Ramsey\Uuid\Uuid;
use App\Client as Pb;

# Pocketbase

const POCKETBASE_APP = 'http://pktb_pocketbase:8080';

try {
    $pocketbase =  new pb(POCKETBASE_APP);
} catch (\Exception $e) {
    echo "Pocketbase Error: " . $e->getMessage();
}

# Router

$method = $_SERVER['REQUEST_METHOD']; # GET | POST | PUT | DELETE
$requestUri = $_SERVER['REQUEST_URI']; # '/index.php/xxxxxxxx'
$route = preg_replace('#^/index.php/#', '', $requestUri);

switch(true) {
    case ('GET' === $method && 'countries' === $route): # GET /countries
        echo json_encode(getCountries());
        break;
    case ('GET' === $method && 'players' === $route): # GET /players
        echo json_encode(getPlayers());
        break;
    case ('POST' === $method && 'countries' === $route): # POST /countries
        echo json_encode(insertCountries());
        break;
    default :
        $response = [
            'status' => 418,
            'statusText' => 'I am a teapot',
            'payload' => [],
        ];
        echo json_encode($response);
}

# App codebase

/**
 * GET /countries
 */
function getCountries(): array {
    global $pocketbase;
    $response = $pocketbase->collection('countries')->getList();
    $names = array_column($response['items'], 'name');

    return [
        'status' => 200,
        'statusText' => 'OK',
        'payload' => [
            'uuid' => Uuid::uuid7()->__toString(),
            'countries' => $names,
        ]
    ];
}

/**
 * GET /players
 */
function getPlayers(): array {
    global $pocketbase;
    $response = $pocketbase->collection('players')->getList();
    $names = array_column($response['items'], 'name');

    return [
        'status' => 200,
        'statusText' => 'OK',
        'payload' => [
            'uuid' => Uuid::uuid7()->__toString(),
            'players' => $names,
        ]
    ];
}

/**
 * POST /countries
 */
function insertCountries(): array {
    global $pocketbase;

    try {
        $content = json_decode(file_get_contents('php://input'));
        $response = $pocketbase->collection('countries')->create([
            'name' => $content->name
        ]);
        $responseArr = json_decode($response);

        if (isset($responseArr->code, $responseArr->message)) {
            throw new Exception($responseArr->message, $responseArr->code);
        }

        http_response_code(201);
        $response = [
            'status' => 201,
            'statusText' => 'Record created successfully.',
            'payload' => $responseArr
        ];
    } catch (Exception $e) {
        http_response_code($e->getCode());
        $response = [
            'status' => $e->getCode(),
            'statusText' =>$e->getMessage(),
        ];
    }

    return $response;
}