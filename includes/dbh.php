<?php

class Dbh
{

    protected function connect()
    {
        $dbserver = "localhost";
        $dbusername = "root";
        $dbpassword = "";
        $database = "janken";
        $port = 3307;

        $conn = mysqli_connect($dbserver, $dbusername, $dbpassword, $database, $port);

        if ($conn) {
            //echo "Success";
            return $conn;
        } else {
            die("error" . mysqli_connect_error());
        }
    }
}
