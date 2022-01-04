<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: login.php");
    exit;
} else {
    $username = $_SESSION['username'];
    //echo "$username";
}

$scores = $_POST['score'];
$wins = $scores["wins"];
$losses = $scores["losses"];
$choice = $scores["choice"];
if ($wins > $losses) {
    if ($losses == 0) {
        $kd = $wins;
    } else {
        $kd = $wins / $losses;
    }
} else if ($wins < $losses) {
    if ($wins == 0) {
        $kd = -$losses;
    } else {
        $kd = - ($losses / $wins);
    }
} else {
    $kd = 0;
}

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

    $sql = "select * from choices where uid='$uid'";
    $result  = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);
    if ($num >= 1) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rock = $row['rock'];
            $paper = $row['paper'];
            $scissors = $row['scissors'];
        }
    } else {
        echo "no output";
    }

    $sql = "UPDATE leaderboard SET wins = '$wins', losses= '$losses', kd = '$kd' where uid='$uid'";
    $result  = mysqli_query($conn, $sql);
    if (!empty($choice)) {
        if ($choice == "rock") {
            $rock = $rock + 1;
            $sql = "UPDATE choices SET rock = $rock WHERE uid = $uid;";
            $result  = mysqli_query($conn, $sql);
        } else if ($choice == "paper") {
            $paper = $paper + 1;
            $sql = "UPDATE choices SET paper = $paper WHERE uid = $uid;";
            $result  = mysqli_query($conn, $sql);
        } else {
            $scissors = $scissors + 1;
            $sql = "UPDATE choices SET scissors = $scissors WHERE uid = $uid;";
            $result  = mysqli_query($conn, $sql);
        }
    }
} else {
    die("error" . mysqli_connect_error());
}
