<?php

include '/xampp/htdocs/janken/models/user.mod.php';
include '/xampp/htdocs/janken/models/leaderboard.mod.php';
include '/xampp/htdocs/janken/models/choices.mod.php';

class SignupContr
{

    private $username;
    private $password;
    private $cpassword;
    private $userModel;
    private $leaderboardModel;
    private $choicesModel;

    public $usernameErr;
    public $passwordErr;
    public $cpasswordErr;

    public function __construct($username, $password, $cpassword)
    {
        $this->username = $username;
        $this->password = $password;
        $this->cpassword = $cpassword;
        $this->userModel = new User();
        $this->leaderboardModel = new Leaderboard();
        $this->choicesModel = new Choices();
    }

    public function signupUser()
    {
        if ($this->__validateUsername() && $this->__validatePassword()) {
            $this->usernameErr = "";
            $this->passwordErr = "";
            $this->cpasswordErr = "";
            $result = $this->userModel->setUser($this->username, $this->password);
            if ($result != false) {
                $result = $this->userModel->getUser($this->username);
                while ($row = mysqli_fetch_assoc($result)) {
                    $_SESSION['loggedin'] = true;
                    $_SESSION['username'] = $this->username;
                    $_SESSION['uid'] = $row['uid'];
                    $_SESSION['time'] = time();
                    $this->leaderboardModel->setUser($_SESSION['uid']);
                    $this->choicesModel->setUser($_SESSION['uid']);
                    header("location: game.php");
                }
            }
            // user model (add row)
            // leaderboard model (row add)
            // choices model (row add)
        }
    }

    private function __userExists()
    {

        $result = $this->userModel->getUser($this->username);
        if ($result == false) {
            return false;
        } else {
            return true;
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
            if ($this->__userExists()) {
                $this->usernameErr = "This username already Exists!";
                return false;
            } else {
                return true;
            }
        }
    }



    private function __validatePassword()
    {
        $passCorr = false;
        if (!empty($this->password) && ($this->password == $this->cpassword)) {
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
        } elseif (!empty($this->password)) {
            //$cpasswordErr = "Please Check You've Entered Or Confirmed Your Password!";
            //$passCorr = false;
            $this->cpasswordErr = "Please Check You've Entered Or Confirmed Your Password!";
            return false;
        } else {
            //$passwordErr = "Please enter password   ";
            $this->passwordErr = "Please enter password   ";
            $passCorr = false;
        }
        return $passCorr;
    }
}
