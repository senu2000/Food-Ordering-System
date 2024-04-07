<?php
session_start();
$email = $_SESSION['email']; //session variables
$account_type = $_SESSION['account'];
$id = $_SESSION['id'];

if (isset($_SESSION['email'])) {
    unset($email);          //unset session variables when logout
    unset($account_type);
    unset($id);
    session_destroy();      //destroy session when logout
    header("Location: login.php"); // redirect to login page after logout
} else{
    header("Location: login.php");
}

?>