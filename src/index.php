<?php
require_once __DIR__.'/vendor/autoload.php';
error_reporting(E_ALL);
ini_set('display_errors', 'On');

use App\PhpUnix;
use App\Database\DB;

DB::init();
$postedData = json_decode(file_get_contents('php://input'), true);
if (isset($postedData['cmd'])) {
    $unix = new PhpUnix($postedData['cmd']);
    $unix->run()->then(function($result, $err) {
        dd($result, $err);
    }, function($err, $result) {
        dd($result, $err);
    });
}



