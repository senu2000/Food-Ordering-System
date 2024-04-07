<?php
require 'core/init.php';

if (isset($_GET["menu_item_id"])) {
    $menu_item_id = $_GET["menu_item_id"];
    $userObj->deleteFood($menu_item_id);
    header("Location:restaurantAdmin.php");
}