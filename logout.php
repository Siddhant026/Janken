<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: login.php");
    exit;
} else {
    //$username = $_SESSION['username'];
    //echo "$username";
    session_unset();

    // destroy the session
    session_destroy();
    header("location: login.php");
    exit;
}
