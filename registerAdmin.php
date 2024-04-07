<?php
require 'core/init.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $contact = $_POST["tel"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $userObj->registerAdmin($name, $email,$contact, $password);

    $_SESSION['success_message'] = "New admin successfully registered";

    header("Location:admin.php");
    exit();
}
?>

