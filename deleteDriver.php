<?php
require 'core/init.php';

if (isset($_GET["driver_id"])) {
    $driver_id = $_GET["driver_id"];
    $userObj->deleteDriver($driver_id);
    header("Location:admin.php");
}