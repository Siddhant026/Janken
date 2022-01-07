<?php

class Leaderboard extends Dbh
{

    function setUser($uid)
    {
        $sql = "INSERT INTO `leaderboard` (`uid`, `wins`, `losses`) VALUES ('$uid', '0', '0');";
        $result  = mysqli_query($this->connect(), $sql);
        if($result) {
            return true;
        } else {
            return false;
        }
    }
}
