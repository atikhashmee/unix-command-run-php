<?php

namespace App;

use Throwable;
use RedBeanPHP\R;
use Monolog\Level;
use Monolog\Logger;
use App\Database\DB;
use React\Promise\Deferred;
use App\Encrypter\DataEncryption;
use Monolog\Handler\StreamHandler;

class PhpUnix 
{
    private $storage = 'storage';
    private $cmd;
    
    const BEAN = 'trackers';
    private $log;
    private $auth = [];
    private $tracker_id = null;
    public function __construct($cmd = 'ls -al') {
        $this->cmd = $cmd;
        $this->log = new Logger('my_file');
        $this->log->pushHandler(new StreamHandler($this->getProjectRootDirectory().'/logs/log.log', Level::Error));
    }

    public function makeTracker() {
        /**
         * id 
         * command_name
         * started_at
         * status , pending, running, completed, failed, timeout, uknown
         * end_at
         * initiated_by
         * initiated_by_name
         */

        $tracker = R::dispense(self::BEAN);
        $tracker->command_name = $this->cmd;
        $tracker->started_at = time();
        $tracker->status = 'pending';
        if (!empty($this->getAuth())) {
            if (isset($this->getAuth()['initiated_by'])) {
                $tracker->initiated_by = $this->getAuth()['initiated_by'];
            }
            if (isset($this->getAuth()['initiated_by_name'])) {
                $tracker->initiated_by_name = $this->getAuth()['initiated_by_name'];
            }
        }
        return $this->tracker_id = R::store($tracker); //returns id 
    }
    public function init() {
        $this->makeTracker();
    }

    public function run() {
        $this->init();
        $deferred = new Deferred();
        $this->prepareDeferrdPromise(function($e, $result ) use ($deferred) {
            if ($e) {
                $deferred->reject($e);
            } else {
                $deferred->resolve($result);    
            }
        });
        return $deferred->promise();
    }

    public function prepareDeferrdPromise($callBack) {
        $result = [];
        try {
            $descriptorspec = array(
                0 => array("pipe", "r"),
                1 => array("file",  $this->getProjectRootDirectory().'/storage/'.$this->getTheTrackerFileName(), "a"),
                2 => array("file",  $this->getProjectRootDirectory().'/storage/'.$this->getTheTrackerFileName(), "a")
            );
            $process = proc_open($this->cmd, $descriptorspec, $pipes);
            if (is_resource($process)) {
                fclose($pipes[0]);
                $proc_status = proc_get_status($process);
                $loadObject = R::load(self::BEAN, $this->tracker_id);
                $loadObject->status = $proc_status['running'] ? 'running': 'failed'; 
                $loadObject->pid = $proc_status['pid'];
                R::store($loadObject);
            }
            proc_close($process);
            $callBack(null, $result);
        } catch (\Throwable $e) {
            $callBack($e, $result);
        }
       
    }
    public function getProjectRootDirectory() {
        return dirname(__FILE__, 2);
    }

    public function getTheTrackerFileName() {
        if (!empty($this->tracker_id)) {
            return $this->tracker_id.'.log';
        }
        return 'default.log';
    }

    public function setAuth(array $auth)
    {
        $this->auth = $auth;
    }

    public function getAuth(): array
    {
        return $this->auth;
    }

    

}
