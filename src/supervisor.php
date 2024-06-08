<?php


require_once __DIR__.'/vendor/autoload.php';
error_reporting(E_ALL);
ini_set('display_errors', 'On');

use RedBeanPHP\R;
use App\Database\DB;

DB::init();
$trackers = R::getAll('SELECT * FROM trackers WHERE status = "running" AND pid is not null');

foreach ($trackers as $tracker) {
    if (!posix_kill($tracker['pid'], 0)) {
        $trac = R::load( 'trackers', $tracker['id']);
        $trac->status = 'finished';
        $trac->updated_at = time();
        R::store($trac);
    }
}

