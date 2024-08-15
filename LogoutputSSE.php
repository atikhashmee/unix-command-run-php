<?php

require_once __DIR__.'/vendor/autoload.php';
error_reporting(E_ALL);
ini_set('display_errors', 'On');

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

use App\Database\DB;
use App\ManageTracker;


function sendMessage($event, $data) {
    echo "id: $event\n";
    echo "data: $data\n\n";
    ob_flush();
    flush();
}
while (ob_get_level() > 0) {
    ob_end_clean();
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    DB::init();
    $tracker = new ManageTracker();
    $trackerData = $tracker->getTracker($id);

    $trackerLog = [];
    $trackerLog['id'] = $trackerData->id;
    $trackerLog['command_name'] = $trackerData->command_name;
    $trackerLog['started_at'] = $trackerData->started_at;
    $trackerLog['status'] = $trackerData->status;
    $trackerLog['pid'] = $trackerData->pid;
    $trackerLog['updated_at'] = $trackerData->updated_at;
    $filePath = __DIR__.'/storage/'.$id.'.log';
    if (file_exists($filePath)) {
        ob_start();
        $fopen = fopen($filePath, 'r');
        while (($line = fgets($fopen)) !== false) {
            sendMessage('data', $line);
            sleep(1);
            // echo $line.'</br>';
            // ob_flush();
            // flush();
            // usleep(10000);
        }
    }
    fclose($fopen);
    sendMessage('end', 'stream ended');
} else {
    sendMessage('end', 'no stream requested');
}
