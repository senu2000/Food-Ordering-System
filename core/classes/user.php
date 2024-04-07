<?php
namespace MyApp;

use MyApp\DBConnector;
use PDO;

class user{

    public function getItems(){
        $dbuser = new DBConnector();
        $con = $dbuser->getConnection();
        $query = "SELECT * FROM menu_item";
        $pstmt = $con->prepare($query);
        $pstmt->execute();
        $rs = $pstmt->fetchAll(PDO::FETCH_OBJ);
        return $rs;
    }

    public function searchItems($foodname){
        $dbuser = new DBConnector();
        $con = $dbuser->getConnection();
        $query = "SELECT * FROM menu_item WHERE menu_item_name LIKE CONCAT('%',?,'%')";
        $pstmt = $con->prepare($query);
        $pstmt->bindValue(1,$foodname);
        $pstmt->execute();
        $rs = $pstmt->fetchAll(PDO::FETCH_OBJ);
        return $rs;
    }

    public function getRestaurants(){
        $dbuser = new DBConnector();
        $con = $dbuser->getConnection();
        $query = "SELECT * FROM restaurant";
        $pstmt = $con->prepare($query);
        $pstmt->execute();
        $rs = $pstmt->fetchAll(PDO::FETCH_OBJ);
        return $rs;
    }

    public function searchItemsByID($restaurantID){
        $dbuser = new DBConnector();
        $con = $dbuser->getConnection();
        $query = "SELECT * FROM menu_item WHERE restaurant_id = ?";
        $pstmt = $con->prepare($query);
        $pstmt->bindValue(1,$restaurantID);
        $pstmt->execute();
        $rs = $pstmt->fetchAll(PDO::FETCH_OBJ);
        return $rs;
    }

    public function getCustomers(){
        $dbuser = new DBConnector();
        $con = $dbuser->getConnection();
        $query = "SELECT * FROM customer";
        $pstmt = $con->prepare($query);
        $pstmt->execute();
        $rs = $pstmt->fetchAll(PDO::FETCH_OBJ);
        return $rs;
    }

    public function deleteCustomer($customer_id){
        $dbuser = new DBConnector();
        $con = $dbuser->getConnection();
        $query1 = "DELETE FROM customer WHERE customer_id = ?";
        $query2 = "DELETE FROM customer_order WHERE customer_id = ?";
        $query3 = "DELETE FROM delivery WHERE customer_id = ?";

        $pstmt2 = $con->prepare($query2);
        $pstmt2->bindValue(1,$customer_id);
        $pstmt2->execute();

        $pstmt3 = $con->prepare($query3);
        $pstmt3->bindValue(1,$customer_id);
        $pstmt3->execute();

        $pstmt1 = $con->prepare($query1);
        $pstmt1->bindValue(1,$customer_id);
        $pstmt1->execute();

    }

    public function getDeliveryDrivers(){
        $dbuser = new DBConnector();
        $con = $dbuser->getConnection();
        $query = "SELECT * FROM deliver_driver";
        $pstmt = $con->prepare($query);
        $pstmt->execute();
        $rs = $pstmt->fetchAll(PDO::FETCH_OBJ);
        return $rs;
    }

    public function deleteRestaurant($restaurant_id){
        $dbuser = new DBConnector();
        $con = $dbuser->getConnection();
        $query1 = "DELETE FROM restaurant WHERE restaurant_id = ?";
        $query2 = "DELETE FROM order_restaurant WHERE restaurant_id = ?";
        $query3 = "DELETE FROM menu_item WHERE restaurant_id = ?";

        $pstmt2 = $con->prepare($query2);
        $pstmt2->bindValue(1,$restaurant_id);
        $pstmt2->execute();

        $pstmt3 = $con->prepare($query3);
        $pstmt3->bindValue(1,$restaurant_id);
        $pstmt3->execute();

        $pstmt1 = $con->prepare($query1);
        $pstmt1->bindValue(1,$restaurant_id);
        $pstmt1->execute();

    }

    public function deleteDriver($driver_id){
        $dbuser = new DBConnector();
        $con = $dbuser->getConnection();
        $query1 = "DELETE FROM deliver_driver WHERE driver_id = ?";
        $query2 = "DELETE FROM delivery WHERE driver_id = ?";

        $pstmt2 = $con->prepare($query2);
        $pstmt2->bindValue(1,$driver_id);
        $pstmt2->execute();

        $pstmt1 = $con->prepare($query1);
        $pstmt1->bindValue(1,$driver_id);
        $pstmt1->execute();

    }

    public function registerAdmin($name, $email, $contact, $password){
        $dbuser = new DBConnector();
        $con = $dbuser->getConnection();
        $query = "INSERT INTO admin(admin_name,admin_email,admin_contact_no,admin_password) VALUES (?,?,?,?)";
        $pstmt = $con->prepare($query);
        $pstmt->bindValue(1, $name);
        $pstmt->bindValue(2, $email);
        $pstmt->bindValue(3, $contact);
        $pstmt->bindValue(4, $password);
        $pstmt->execute();
    }

    public function getFoodByID($menu_item_id){
        $dbuser = new DBConnector();
        $con = $dbuser->getConnection();
        $query = "SELECT * FROM menu_item WHERE menu_item_id = ?";
        $pstmt = $con->prepare($query);
        $pstmt->bindValue(1,$menu_item_id);
        $pstmt->execute();
        $rs = $pstmt->fetch(PDO::FETCH_OBJ);
        return $rs;
    }

    public function deleteFood($menu_item_id){
        $dbuser = new DBConnector();
        $con = $dbuser->getConnection();
        $query1 = "DELETE FROM menu_item WHERE menu_item_id = ?";
        $query2 = "DELETE FROM order_menu_item WHERE menu_item_id = ?";

        $pstmt2 = $con->prepare($query2);
        $pstmt2->bindValue(1,$menu_item_id);
        $pstmt2->execute();

        $pstmt1 = $con->prepare($query1);
        $pstmt1->bindValue(1,$menu_item_id);
        $pstmt1->execute();

    }

    public function addFood($name, $price, $image, $restaurant_id){
        $dbuser = new DBConnector();
        $con = $dbuser->getConnection();
        $query = "INSERT INTO menu_item(menu_item_name,menu_item_price,menu_item_picture,restaurant_id ) VALUES (?,?,?,?)";
        $pstmt = $con->prepare($query);
        $pstmt->bindValue(1, $name);
        $pstmt->bindValue(2, $price);
        $pstmt->bindValue(3, "assets/img/".$image);
        $pstmt->bindValue(4, $restaurant_id);
        $pstmt->execute();
    }

    public function updateFood($name, $price, $image, $restaurant_id, $menu_item_id){
        $dbuser = new DBConnector();
        $con = $dbuser->getConnection();
        $query = "UPDATE menu_item SET menu_item_name=?,menu_item_price=?,menu_item_picture=?,restaurant_id=? WHERE menu_item_id=?";
        $pstmt = $con->prepare($query);
        $pstmt->bindValue(1, $name);
        $pstmt->bindValue(2, $price);
        $pstmt->bindValue(3, "assets/img/".$image);
        $pstmt->bindValue(4, $restaurant_id);
        $pstmt->bindValue(5, $menu_item_id);
        $pstmt->execute();
    }

}
