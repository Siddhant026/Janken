<?php

session_start();

if (isset($_SESSION['loggedin'])) {
    header("location: game.php");
    exit;
}