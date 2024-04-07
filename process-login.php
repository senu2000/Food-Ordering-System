<?php
session_start();
include "core/classes/DBConnector.php";
use MyApp\DBConnector;

if($_SERVER["REQUEST_METHOD"] === "POST"){
    if(isset($_POST["submit"])){
        if(!(empty($_POST["email"]) || empty($_POST["password"]))){ //check email and password not empty
            $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL); //sanitize email
            $password = $_POST["password"];
            $dbcon = new DBConnector;
            try {
                $con = $dbcon->getConnection();
                //search email form customer table. If email exist then compare entered password with password in DB
                $query = "SELECT * FROM customer WHERE customer_email=?";
                $pstmt = $con->prepare($query);
                $pstmt->bindValue(1,$email,PDO::PARAM_STR);
                $pstmt->execute();
                if($pstmt->rowCount()>0){
                    $data = $pstmt-> fetch(PDO::FETCH_ASSOC);
                
                    if($data && password_verify($password, $data['customer_password'])){
                        $_SESSION['email'] = $data['customer_email']; //set session variables if passwords are matched
                        $_SESSION['account'] = "customer";
                        $_SESSION['id'] = $data['customer_id'];
                        header('Location: profile.php');  // redirect to index page
                        exit;
                    } else{
                        header("Location: login.php?message=4"); //redirect to login page if passwords are not matched
                        exit;
                    }
                } else{
                    //search email form restaurant table. If email find then compare entered password with password in DB
                    $query = "SELECT * FROM restaurant WHERE restaurant_email=?";
                    $pstmt = $con->prepare($query);
                    $pstmt->bindValue(1,$email,PDO::PARAM_STR);
                    $pstmt->execute();
                    if($pstmt->rowCount() > 0){
                        $data = $pstmt-> fetch(PDO::FETCH_ASSOC);
                    
                        if($data && password_verify($password, $data['restaurant_password'])){
                            $_SESSION['email'] = $data['restaurant_email']; //set session variables if passwords are matched
                            $_SESSION['account'] = "restaurant";
                            $_SESSION['id'] = $data['restaurant_id'];
                            header('Location: restaurantAdmin.php');  // redirect to restaurantAdmin page
                            exit;
                        } else{
                            header("Location: login.php?message=4"); //redirect to login page if passwords are not matched
                            exit;
                        }
                    } else{
                        //search email form deliver_driver table. If email find then compare entered password with password in DB
                        $query = "SELECT * FROM deliver_driver WHERE driver_email=?";
                        $pstmt = $con->prepare($query);
                        $pstmt->bindValue(1,$email,PDO::PARAM_STR);
                        $pstmt->execute();
                        if($pstmt->rowCount() > 0){
                            $data = $pstmt-> fetch(PDO::FETCH_ASSOC);
                        
                            if($data && password_verify($password, $data['driver_password'])){
                                $_SESSION['email'] = $data['driver_email']; //set session variables if passwords are matched
                                $_SESSION['account'] = "deliver_driver";
                                $_SESSION['id'] = $data['driver_id'];
                                header('Location: profile.php');  // redirect to index page
                                exit;
                            } else{
                                header("Location: login.php?message=4"); //redirect to login page if passwords are not matched
                                exit;
                            }
                        } else{
                            $query = "SELECT * FROM admin WHERE admin_email=?";
                            $pstmt = $con->prepare($query);
                            $pstmt->bindValue(1,$email,PDO::PARAM_STR);
                            $pstmt->execute();
                            if($pstmt->rowCount() > 0){
                                $data = $pstmt-> fetch(PDO::FETCH_ASSOC);
                            
                                if($data && $password==$data['admin_password']){
                                    $_SESSION['email'] = $data['admin_email']; //set session variables if passwords are matched
                                    $_SESSION['account'] = "admin";
                                    $_SESSION['id'] = $data['admin_id'];
                                    header('Location: admin.php');  // redirect to admin page
                                    exit;
                                } else{
                                    header("Location: login.php?message=4"); //redirect to login page if passwords are not matched
                                    exit;
                                }   
                            }
                        }
                    }
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
    } else{
        header("Location: login.php?message=3"); //show error message as Please click the 'Login' button to complete the Login process!
    exit;
    }
} else{
    header("Location: login.php?message=2"); //show error message as Invalid Request Method!
    exit;
}
?>

