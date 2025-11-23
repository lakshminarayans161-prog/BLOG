<?php
$host ="localhost";
$user ="root";
$pass = "";
$dbname = "blog";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error){
    die("connection failed: ". $conn->connect_error);
}
if (session_status()=== PHP_SESSION_NONE){
    session_start();
}
functiomn require_login()
{
    if(!isset($_SESSION['username'])){
        header("location: login.php");
        exit;
    }
}
