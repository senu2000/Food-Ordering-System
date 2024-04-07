<?php
require 'core/init.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $price = $_POST["price"];
    $restaurant_id = $_POST["restaurant_id"];
    $image = $_FILES['image']['name'];

    $userObj->addFood($name, $price, $image, $restaurant_id);

    $_SESSION['success_message'] = "Food added successfully";

    header("Location:restaurantAdmin.php");
    exit();
}
?>

