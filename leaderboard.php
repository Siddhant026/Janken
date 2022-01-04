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
    //echo "Success";
    $sql = "select * from users where username='$username'";
    $result  = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);
    if ($num >= 1) {
        while ($row = mysqli_fetch_assoc($result)) {
            $uid = $row['uid'];
            //echo $uid;
        }
    } else {
        echo "no output";
    }
    $sql = "SELECT users.username, leaderboard.kd, leaderboard.seconds FROM users INNER JOIN leaderboard ON users.uid = leaderboard.uid ORDER BY leaderboard.kd DESC;";
    $boardResult = mysqli_query($conn, $sql);
} else {
    die("error" . mysqli_connect_error());
}

//include "status.php";
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script> -->
    <script src='http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'>
    </script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">

    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css"> -->
    <title>LeaderBoard</title>
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
    <br>
    <br>
    <br>
    <br>
    <h1 style="text-align: center;">LeaderBoard</h1>
    <br>
    <div class="conatiner" style="width: 50%; margin: auto; background-color: white; ">

        <table id="leaderboard" class="table table-striped" style="border-left: 1px solid grey;
                                                                    border-right: 2px solid grey; border-top: 1px solid grey;">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Username</th>
                    <th>K/D</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $rank = 1;
                while ($row = mysqli_fetch_array($boardResult)) {
                    $userTime = $row["seconds"];
                    $currentTime = time() - 30;
                    if ($currentTime > $userTime) {
                        //offline
                        echo "
                        <tr>
                            <td>" . $rank . "</td>
                            <td>" . $row["username"] . "</td>
                            <td>" . $row["kd"] . "</td>
                            <td style='color: red;'> Offline </td>
                        </tr>
                        ";
                    } else {
                        //online
                        echo "
                        <tr>
                            <td>" . $rank . "</td>
                            <td>" . $row["username"] . "</td>
                            <td>" . $row["kd"] . "</td>
                            <td style='color: green;'> Online </td>
                        </tr>
                        ";
                    }

                    $rank = $rank + 1;
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('#leaderboard').DataTable();

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

            var reload = window.setInterval(reloadPage, 30000);

            function reloadPage() {
                location.reload();
            }

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