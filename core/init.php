<?php

use MyApp\user;

session_start();

require 'classes/DBConnector.php';
require 'classes/user.php';


$userObj = new \MyApp\user();

define('BASE_URL','http://localhost/food-odering-system/');

?>