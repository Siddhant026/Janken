<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: login.php");
    exit;
} else {
    $username = $_SESSION['username'];
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
            }
        }

        $sql = "SELECT time.seconds FROM time WHERE time.uid = $uid;";
        $result  = mysqli_query($conn, $sql);
        $num = mysqli_num_rows($result);
        if ($num >= 1) {
            while ($row = mysqli_fetch_assoc($result)) {
                $userTime = $row['seconds'];
            }
        }

        $currentTime = time() - 30;

        $sql = "SELECT users.username, leaderboard.kd, leaderboard.seconds FROM users INNER JOIN leaderboard ON users.uid = leaderboard.uid ORDER BY leaderboard.kd DESC;";
        $boardResult = mysqli_query($conn, $sql);
    }
}
