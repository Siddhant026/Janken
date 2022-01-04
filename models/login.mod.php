<?php

class LoginModel extends Dbh
{

    protected function getUser($username, $password)
    {
        $sql = "select * from users where username='$username'";
        $result  = mysqli_query($this->connect(), $sql);
        $num = mysqli_num_rows($result);
        if ($num == 1) {
            while ($row = mysqli_fetch_assoc($result)) {
                if (password_verify($password, $row['password'])) {
                    //$login = true;
                    session_start();
                    $_SESSION['loggedin'] = true;
                    $_SESSION['username'] = $username;
                    $_SESSION['time'] = time();
                    header("location: game.php");
                }
            }

            //echo "account created";
        } else {
            //echo "account NOT created";
        }
    }
}
