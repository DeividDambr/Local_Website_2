<!-- Checks if user is online and updates the last time they were online -->
<?php
require_once "config.php";

session_start();

// user attributes
$user_id = $_SESSION["id"];
$username = $_SESSION["username"];
$user_status = $_SESSION["loggedin"];
$admin = $_SESSION["admin"];

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
else{
    $sql = "UPDATE vartotojai SET prisijungimo_laikas = CURRENT_TIMESTAMP() where id = ".$_SESSION["id"];
    mysqli_query($link, $sql);
}
?>