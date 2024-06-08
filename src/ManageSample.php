<?php
require_once __DIR__.'/vendor/autoload.php';
error_reporting(E_ALL);
ini_set('display_errors', 'On');
// header('Content-Type: application/json; charset=utf-8');

use App\Database\DB;
use App\ManageTracker;

DB::init();

$trackers = new ManageTracker();

$returnData = [
    'status' => 1,
    'data' => $trackers->getAllTrackers()->toArray()
];

echo json_encode($returnData);
