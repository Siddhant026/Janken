<?php

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: login.php");
    exit;
} else {
    $username = $_SESSION['username'];
    $sessionTime = time() - 1800;
    if ($sessionTime > $_SESSION["time"]) { //subtract new timestamp from the old one
        echo "<script>alert('You have been inactive for too long!'); window.location.href='/janken/logout.php';</script>";
        //header("location: logout.php");
        //exit;
    }
}

//echo $_SESSION['timestamp'];

$dbserver = "localhost";
$dbusername = "root";
$dbpassword = "";
$database = "janken";
$port = 3307;

$conn = mysqli_connect($dbserver, $dbusername, $dbpassword, $database, $port);

if ($conn) {
    $sql = "select * from users where username='$username'";
    $result  = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);
    if ($num >= 1) {
        while ($row = mysqli_fetch_assoc($result)) {
            $uid = $row['uid'];
            //echo $uid;
        }
    }

    $oldPasswordErr = $newPasswordErr = $cpasswordErr = "";
    $oldPassword = $newPassword = $cpassword = "";
    $passCorr = $oldPassCorr = $oldPassMatch = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!empty($_POST["old-password"])) {
            $oldPassword = $_POST["old-password"];
            $oldPassCorr = true;
            if (strlen($_POST["old-password"]) <= '8') {
                $oldPasswordErr = "Your Password Must Contain At Least 8 Characters!";
                $oldPassCorr = false;
            } elseif (!preg_match("#[0-9]+#", $oldPassword)) {
                $oldPasswordErr = "Your Password Must Contain At Least 1 Number!";
                $oldPassCorr = false;
            } elseif (!preg_match("#[A-Z]+#", $oldPassword)) {
                $oldPasswordErr = "Your Password Must Contain At Least 1 Capital Letter!";
                $oldPassCorr = false;
            } elseif (!preg_match("#[a-z]+#", $oldPassword)) {
                $oldPasswordErr = "Your Password Must Contain At Least 1 Lowercase Letter!";
                $oldPassCorr = false;
            }
        } else {
            $oldPasswordErr = "Please enter password   ";
            $oldPassCorr = false;
        }

        if ($oldPassCorr) {
            //echo "old pass correct $oldPassword <br>";
            $sql = "select * from users where username='$username'";
            $result  = mysqli_query($conn, $sql);
            $num = mysqli_num_rows($result);
            if ($num >= 1) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $storedPass = $row['password'];
                    //echo "stored Pass $storedPass <br>";
                }
                //$oldPassHash = password_hash($oldPassword, PASSWORD_DEFAULT);
                //echo "old pass hash $oldPassHash <br>";
                if (password_verify($oldPassword, $storedPass)) {
                    echo "old pass match";
                    if (!empty($_POST["new-password"]) && ($_POST["new-password"] == $_POST["cpassword"])) {
                        $newPassword = test_input($_POST["new-password"]);
                        $cpassword = test_input($_POST["cpassword"]);
                        $passCorr = true;
                        if (strlen($_POST["new-password"]) <= '8') {
                            $newPasswordErr = "Your Password Must Contain At Least 8 Characters!";
                            $passCorr = false;
                        } elseif (!preg_match("#[0-9]+#", $newPassword)) {
                            $newPasswordErr = "Your Password Must Contain At Least 1 Number!";
                            $passCorr = false;
                        } elseif (!preg_match("#[A-Z]+#", $newPassword)) {
                            $newPasswordErr = "Your Password Must Contain At Least 1 Capital Letter!";
                            $passCorr = false;
                        } elseif (!preg_match("#[a-z]+#", $newPassword)) {
                            $newPasswordErr = "Your Password Must Contain At Least 1 Lowercase Letter!";
                            $passCorr = false;
                        }
                    } elseif (!empty($_POST["new-password"])) {
                        $cpasswordErr = "Please Check You've Entered Or Confirmed Your Password!";
                        $passCorr = false;
                    } else {
                        $newPasswordErr = "Please enter password   ";
                        $passCorr = false;
                    }

                    if ($passCorr) {
                        echo "new pass = c pass";
                        //echo "correct username";
                        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
                        $sql = "UPDATE users SET password = '$hash' WHERE uid = $uid;";
                        $result = mysqli_query($conn, $sql);
                        // $_SESSION['username'] = $newUsername;
                        // $username = $_SESSION['username'];
                        header("location: profile.php");
                    }
                }
            }
        }

        
    }
} else {
    die("error" . mysqli_connect_error());
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src='http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'>
    </script>
    <title>Janken</title>
    <style>
        body {
            background-image: url('/janken/assets/background.jpg');
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light" style="position: fixed;
    top: 0px;
    width: 100%;
    opacity: 0
    ">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Janken</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/janken/game.php">Home</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="#">Profile</a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link" href="/janken/leaderboard.php">Leaderboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/janken/profile.php"><?php echo "$username"; ?></a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="/janken/logout.php">Logout</a>
                    </li> -->
                    <!-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Dropdown
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li>-->
                    <!-- <li class="nav-item">
                        <a class="nav-link disabled">Disabled</a>
                    </li>  -->
                </ul>
                <ul class="navbar-nav text-right">
                    <li class="nav-item">
                        <a class="nav-link" href="/janken/logout.php">Logout</a>
                    </li>
                </ul>
                <!-- <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form> -->
            </div>
        </div>
    </nav>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <h1 style="text-align: center;">New Password</h1>
    <br>
    <div class="container d-flex justify-content-center align-items-center">
        <div class="card" style="width: 380px;
    border: none;
    border-radius: 15px;
    padding: 8px;
    background-color: #fff;
    position: relative;
    min-height: 100px;
overflow: hidden;">
            <form name="signup" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="mb-3">
                    <label for="password" class="form-label">Old Password</label>
                    <input type="password" name="old-password" class="form-control" id="old-password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>
                    <?php
                    if (!empty($oldPasswordErr)) {
                        echo "$oldPasswordErr";
                    }
                    ?>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="new-password" class="form-control" id="new-password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>
                    <?php
                    if (!empty($newPasswordErr)) {
                        echo "$newPasswordErr";
                    }
                    ?>
                </div>
                <div class="mb-3">
                    <label for="cpassword" class="form-label">Confirm Password</label>
                    <input type="password" name="cpassword" class="form-control" id="cpassword" title="Must match the password" required>
                    <?php
                    if (!empty($cpasswordErr)) {
                        echo "$cpasswordErr";
                    }
                    ?>
                </div>

                <!-- <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1">Check me out</label>
            </div> -->
                <button type="submit" class="btn btn-primary">Edit</button>
                <!-- <div id="login message" class="form-text">New Username has to be Unique</div> -->
            </form>
        </div>
    </div>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            // $('#leaderboard').DataTable();

            var signal = window.setInterval(sendSignal, 5000);

            function sendSignal() {
                $.ajax({
                    url: "time.php",
                    type: "POST",
                    success: function(data, status) {
                        console.log(status);
                    }
                });
            }

            setTimeout(function() {
                alert("You have not played game for 30 minutes");
                location.replace("/janken/logout.php")
            }, 1800000);

            // setTimeout(function() {
            //     $.ajax({
            //         url: "status.php",
            //         type: "POST",
            //         success: function(data, status) {
            //             console.log(status);
            //         }
            //     })
            // }, 30000);
        });
    </script>
    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
</body>

</html>