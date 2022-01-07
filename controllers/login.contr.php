<?php

include '/xampp/htdocs/janken/models/user.mod.php';

class LoginContr
{

    private $username;
    private $password;
    private $userModel;

    public $usernameErr;
    public $passwordErr;

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
        $this->userModel = new User();
    }

    public function loginUser()
    {
        if ($this->__validateUsername() && $this->__validatePassword()) {
            $this->usernameErr = "";
            $this->passwordErr = "";
            $this->cpasswordErr = "";
            $result = $this->userModel->getUser($this->username);
            if ($result != false) {
                while ($row = mysqli_fetch_assoc($result)) {
                    if (password_verify($this->password, $row['password'])) {
                        //$login = true;
                        session_start();
                        $_SESSION['loggedin'] = true;
                        $_SESSION['username'] = $this->username;
                        $_SESSION['uid'] = $row['uid'];
                        $_SESSION['time'] = time();
                        header("location: game.php");
                    }
                }
            }
        }
    }

    private function __validateUsername()
    {
        if (empty($this->username)) {
            //$usernameErr = "Username is required";
            return false;
        } else if (!preg_match("/^[a-zA-Z-' ]*$/", $this->username)) {
            //$usernameErr = "Only letters and white space allowed";
            return false;
        } else {
            //$username = test_input($_POST["username"]);
            //$usernameCorr = true;
            return true;
        }
    }

    private function __validatePassword()
    {
        $passCorr = false;
        if (!empty($this->password)) {
            //$password = test_input($_POST["password"]);
            //$cpassword = test_input($_POST["cpassword"]);
            $passCorr = true;
            if (strlen($this->password) <= '8') {
                //$passwordErr = "Your Password Must Contain At Least 8 Characters!";
                //$passCorr = false;
                return false;
            } elseif (!preg_match("#[0-9]+#", $this->password)) {
                //$passwordErr = "Your Password Must Contain At Least 1 Number!";
                //$passCorr = false;
                return false;
            } elseif (!preg_match("#[A-Z]+#", $this->password)) {
                //$passwordErr = "Your Password Must Contain At Least 1 Capital Letter!";
                //$passCorr = false;
                return false;
            } elseif (!preg_match("#[a-z]+#", $this->password)) {
                //$passwordErr = "Your Password Must Contain At Least 1 Lowercase Letter!";
                //$passCorr = false;
                return false;
            }
        } else {
            //$passwordErr = "Please enter password   ";
            $this->passwordErr = "Please enter password   ";
            $passCorr = false;
        }
        return $passCorr;
    }
}
