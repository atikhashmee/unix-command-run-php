<h1>Hello world</h1>
<?php

require_once __DIR__.'/vendor/autoload.php';

echo "hello world and hi";

error_reporting(E_ALL);
ini_set('display_errors', 'On');

use App\PhpUnix;
use App\Database\DB;


DB::init();
$unix = new PhpUnix();
$unix->run()->then(function($result, $err) {
    dd($result, $err);
}, function($err, $result) {

    dd($result, $err);
});

