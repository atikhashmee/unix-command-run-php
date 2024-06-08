<?php
namespace App\Database;

use \RedBeanPHP\R as R;

class DB 
{
    public static function init() {
        R::setup('sqlite:'.__DIR__.'/db.sqlite3');
    }
}
