<?php
require 'core/init.php';

if (isset($_GET["restaurant_id"])) {
    $restaurant_id = $_GET["restaurant_id"];
    $userObj->deleteRestaurant($restaurant_id);
    header("Location:admin.php");
}