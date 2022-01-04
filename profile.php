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

echo $_SESSION['timestamp'];

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
        $sql = "SELECT * FROM `leaderboard` WHERE leaderboard.uid = $uid;";
        $result  = mysqli_query($conn, $sql);
        $num = mysqli_num_rows($result);
        if ($num >= 1) {
            while ($row = mysqli_fetch_assoc($result)) {
                $wins = $row['wins'];
                $losses = $row['losses'];
                $kd = $row['kd'];
                //echo $uid;
            }
        }
    }
} else {
    die("error" . mysqli_connect_error());
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
    <link rel="stylesheet" href="/janken/css/profile.css">
    <title><?php echo $username; ?></title>
    <script src='http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'>
    </script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light" style="position: fixed;
    top: 0px;
    width: 100%;
    opacity: 1
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
    <div class="container d-flex justify-content-center align-items-center">
        <div class="card">
            <div class="upper"> </div>
            <div class="user text-center">
                <div class="profile"> <img src="/janken/assets/profile.png" class="rounded-circle" width="80"> </div>
            </div>
            <div class="mt-5 text-center">
                <h4 class="mb-0"><?php echo $username; ?></h4>
                <div class="d-flex justify-content-between align-items-center mt-4 px-4 mb-4">
                    <div class="stats">
                        <h6 class="mb-0">Wins</h6> <span><?php echo $wins; ?></span>
                    </div>
                    <div class="stats">
                        <h6 class="mb-0">Losses</h6> <span><?php echo $losses; ?></span>
                    </div>
                    <div class="stats">
                        <h6 class="mb-0">K/D</h6> <span><?php echo $kd; ?></span>
                    </div>
                </div>
                <a href="/janken/changeUsername.php"><button class="btn btn-primary btn-sm follow">Edit Username</button></a>
                <a href="/janken/changePass.php"><button class="btn btn-primary btn-sm follow">Edit Password</button></a>
                <!-- <button class="btn btn-primary btn-sm follow">Edit Picture</button> -->
                <br>
                <br>
                <button class="btn btn-danger btn-sm follow" onclick="deleteFunc()">Delete Account</button>
            </div>
        </div>
    </div>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script>
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

        function deleteFunc() {
            if (confirm("Your account will be deleted") == true) {
                // $.ajax({
                //     url: "delete.php",
                //     type: "POST",
                //     success: function(data, status) {
                //         console.log(status);
                //     }
                // });
                window.location.href = '/janken/delete.php';
            }
        }
    </script>
    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
</body>

</html>