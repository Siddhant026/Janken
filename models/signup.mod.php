<?php

class SignupModel extends Dbh
{

    protected function setUser($username, $password)
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO `users` (`username`, `password`) VALUES ('$username', '$hash');";
        $result  = mysqli_query($this->connect(), $sql);
        if ($result) {
            //echo "account created";
            $login = true;
            session_start();
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['time'] = time();
            $sql = "select * from users where username='$username'";
            $result  = mysqli_query($this->connect(), $sql);
            $num = mysqli_num_rows($result);
            if ($num >= 1) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $uid = $row['uid'];
                    //echo $uid;
                }
            } else {
                echo "no output";
            }
            $sql = "INSERT INTO `leaderboard` (`uid`, `wins`, `losses`) VALUES ('$uid', '0', '0');";
            $result  = mysqli_query($this->connect(), $sql);
            $sql = "INSERT INTO `choices` (`uid`, `rock`, `paper`, `scissors`) VALUES ('$uid', '0', '0', '0');";
            $result  = mysqli_query($this->connect(), $sql);
            header("location: game.php");
        }
    }

    protected function userExists($username)
    {
        $existsSql = "select * from users where username='$username'";
        $result = mysqli_query($this->connect(), $existsSql);
        $num = mysqli_num_rows($result);
        if ($num > 0) {
            //$usernameCorr = false;
            //$usernameErr = "This Username Already Exists, Please Enter An Unique Username";
            return true;
        } else {
            return false;
        }
    }
}
