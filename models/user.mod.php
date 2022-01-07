<?php

class User extends Dbh{

    function getUser($username) {
        $existsSql = "select * from users where username='$username'";
        $result = mysqli_query($this->connect(), $existsSql);
        $num = mysqli_num_rows($result);
        if ($num > 0) {
            return $result;
        } else {
            return false;
        }
    }

    function setUser($username, $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO `users` (`username`, `password`) VALUES ('$username', '$hash');";
        $result  = mysqli_query($this->connect(), $sql);
        if ($result) {
            //session_start();
            return $result;
        } else {
            return false;
        }
    }
}