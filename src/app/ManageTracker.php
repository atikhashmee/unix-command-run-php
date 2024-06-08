<?php
namespace App;

use RedBeanPHP\R;


class ManageTracker 
{
    public function killProcess($pid) {
        return posix_kill($pid, 15);
    } 


    public function getAllTrackers() {
        return collect(R::getAll('SELECT * FROM trackers'));
    }


    public function getTracker($id) {
        return R::load('trackers', $id);
    }
    public function getRunningProcesses() {

    }
}
