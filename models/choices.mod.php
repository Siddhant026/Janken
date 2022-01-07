<?php

class Choices extends Dbh
{

    function setUser($uid)
    {
        $sql = "INSERT INTO `choices` (`uid`, `rock`, `paper`, `scissors`) VALUES ('$uid', '0', '0', '0');";
        $result  = mysqli_query($this->connect(), $sql);
        if($result) {
            return true;
        } else {
            return false;
        }
    }
}
