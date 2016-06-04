<?php

/**
 * Created by PhpStorm.
 * User: kritarth
 * Date: 29/5/16
 * Time: 2:46 AM
 */
class dbcon
{
    private $conn;
    function __construct()
    {
    }
    function connect()
    {
        include_once 'Config.php';

        //connecting to database
        $this -> conn = new mysqli($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);
        //check for connection error
        if(mysqli_connect_errno()) {
            echo "Failed to connect to MySQL Database: " . mysqli_connect_error();
        }

        return $this -> conn;
    }

}
