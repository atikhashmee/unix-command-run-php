<?php
namespace App\Database\Models;

use RedBeanPHP\SimpleModel;

$lifeCycle = '';

class Model_User extends SimpleModel
{
    protected $bean = 'users';

    public $id; 

    public $name;

    public $created_at;

    public $updated_at;
    public function open() {
        global $lifeCycle;
        $lifeCycle .= "called open: ".$this->id;
     }
     public function dispense() {
         global $lifeCycle;
         $lifeCycle .= "called dispense() ".$this->bean;
     }
     public function update() {
         global $lifeCycle;
         $lifeCycle .= "called update() ".$this->bean;
         if (strlen($this->name) > 2) {
            dd('can not update');
         }
     }
     public function after_update() {
         global $lifeCycle;
         $lifeCycle .= "called after_update() ".$this->bean;
     }
     public function delete() {
         global $lifeCycle;
         $lifeCycle .= "called delete() ".$this->bean;
     }
     public function after_delete() {
         global $lifeCycle;
         $lifeCycle .= "called after_delete() ".$this->bean;
     }
}
