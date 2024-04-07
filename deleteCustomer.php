<?php
require 'core/init.php';

if (isset($_GET["customer_id"])) {
    $customer_id = $_GET["customer_id"];
    $userObj->deleteCustomer($customer_id);
    header("Location:admin.php");
}