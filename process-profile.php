<?php
include "core/classes/DBConnector.php";
use MyApp\DBConnector;
$dbcon = new DBConnector();

session_start();
$email = $_SESSION['email'];
$account_type = $_SESSION['account'];

if(isset($_POST['update_profile'])){
    $updated_name = filter_var($_POST["update_name"], FILTER_SANITIZE_STRING);
    $updated_email = filter_var($_POST["update_email"], FILTER_SANITIZE_EMAIL);
    $updated_phone_no = filter_var($_POST["update_phone"], FILTER_SANITIZE_STRING);
    $updated_address = filter_var($_POST["update_address"], FILTER_SANITIZE_STRING);

    
    $con = $dbcon->getConnection();
    //checking the new email address is already in the customer, restaurant and deliver-driver tables
    if($email != $updated_email){
        $query = "SELECT * FROM customer WHERE customer_email=?";
        $pstmt = $con->prepare($query);
        $pstmt->bindValue(1,$updated_email,PDO::PARAM_STR);
        $pstmt->execute();
        if($pstmt->rowCount() > 0){
            header("Location: edit-profile.php?message=4"); //show error msg as email already exist and use different email
            exit;
        } else{
            $query = "SELECT * FROM restaurant WHERE restaurant_email=?";
            $pstmt = $con->prepare($query);
            $pstmt->bindValue(1,$updated_email,PDO::PARAM_STR);
            $pstmt->execute();
            if($pstmt->rowCount() > 0){
                header("Location: edit-profile.php?message=4");
                exit;
            } else{
                $query = "SELECT * FROM deliver_driver WHERE driver_email=?";
                $pstmt = $con->prepare($query);
                $pstmt->bindValue(1,$updated_email,PDO::PARAM_STR);
                $pstmt->execute();
                if($pstmt->rowCount() > 0){
                    header("Location: edit-profile.php?message=4");
                    exit;
                }
            }
        }
    }
    //query run according to the account type
    if($account_type == "customer"){
        $query1 = "UPDATE customer SET customer_name=?, customer_email=?, customer_contact_no=?, customer_address=? WHERE customer_email=?"; //update name,email,contact-no and address
        $query2 = "UPDATE customer SET customer_password=? WHERE customer_email=?"; // update password
        $query3 = "UPDATE customer SET customer_picture=? WHERE customer_email=?"; //update profile picture
    }  else if($account_type == "restaurant"){
        $query1 = "UPDATE restaurant SET restaurant_name=?, restaurant_email=?, restaurant_contact_no=?, restaurant_address=? WHERE restaurant_email=?";
        $query2 = "UPDATE restaurant SET restaurant_password=? WHERE restaurant_email=?";
        $query3 = "UPDATE restaurant SET restaurant_picture=? WHERE restaurant_email=?";
    }  else if($account_type == "deliver_driver"){
        $query1 = "UPDATE deliver_driver SET driver_name=?, driver_email=?, driver_contact_no=?, driver_address=? WHERE driver_email=?";
        $query2 = "UPDATE deliver_driver SET driver_password=? WHERE driver_email=?";
        $query3 = "UPDATE deliver_driver SET driver_picture=? WHERE driver_email=?";
    }

    $pstmt1 = $con->prepare($query1);
    $pstmt1->bindValue(1, $updated_name);
    $pstmt1->bindValue(2, $updated_email);
    $pstmt1->bindValue(3, $updated_phone_no);
    $pstmt1->bindValue(4, $updated_address);
    $pstmt1->bindValue(5, $email);
    $pstmt1->execute();
    //set session email to update email
    $_SESSION['email']=$updated_email;
    if($pstmt1->rowCount() > 0){
        header("Location: edit-profile.php?message=7");
    }
    //change password
    $old_password = $_POST['old_pass'];
    $updated_password = $_POST['update_pass'];
    $new_password = $_POST['new_pass'];
    $confirm_password = $_POST['confirm_pass'];
    $hash_password = password_hash($confirm_password, PASSWORD_DEFAULT);

    if(!empty($updated_password) || !empty($new_password) || !empty($confirm_password)){
        if(!password_verify($updated_password, $old_password)){
            header("Location: edit-profile.php?message=1"); //show error msg as old password not match
            exit;
        }elseif($new_password != $confirm_password){
           header("Location: edit-profile.php?message=2"); //show error msg as confirm password not match
           exit;
        }else{
            $pstmt2 = $con->prepare($query2);
            $pstmt2->bindValue(1, $hash_password);
            $pstmt2->bindValue(2, $updated_email);
            $pstmt2->execute();
            if($pstmt2->rowCount() > 0){
                header("Location: edit-profile.php?message=3"); // show msg as successfully change password
            }
        }
    }
    //change profile picture
    $update_image = $_FILES['update_image']['name'];
    $update_image_size = $_FILES['update_image']['size'];
    $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
    $update_image_folder = 'assets/uploaded_img/'.$update_image;
    
    if(!empty($update_image)){
        if($update_image_size > 2000000){
           header("Location: edit-profile.php?message=5"); //show error msg as image is too large
           exit;
        }else{
            $pstmt3 = $con->prepare($query3);
            $pstmt3->bindValue(1, $update_image);
            $pstmt3->bindValue(2, $updated_email);
            $pstmt3->execute();
            if($pstmt3->rowCount() > 0){  
                move_uploaded_file($update_image_tmp_name, $update_image_folder);
                header("Location: edit-profile.php?message=6"); // show msg as successfully change password
            }
        }
    }

    if(($pstmt1->rowCount() > 0) && (($pstmt2->rowCount() > 0) || ($pstmt3->rowCount() > 0))){
        header("Location: edit-profile.php?message=7"); // when change details and password or profile picture at once show msg as update successfully
        exit;
    } elseif (($pstmt2->rowCount() > 0) && ($pstmt3->rowCount() > 0)){
        header("Location: edit-profile.php?message=8"); //when change profile picture and password only at once show msg as image and password update successfully
        exit;
    }
    
}

?>