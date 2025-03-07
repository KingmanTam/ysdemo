<?php

class Mysql
{

    private mysqli $mysqli;

    public function init(): void
    {
        $this->mysqli = new mysqli(app::$mysql['host'], app::$mysql['user'], app::$mysql['password'], app::$mysql['dbname'], app::$mysql['port']);
    }

    public function getMysqlDb(): mysqli
    {
        if (empty($this->mysqli)){
            $this->init();
        }
        return $this->mysqli;
    }
}