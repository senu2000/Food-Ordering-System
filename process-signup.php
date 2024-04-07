<?php
include "core/classes/DBConnector.php";
use MyApp\DBConnector;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if(isset($_POST["submit"])){
        //check all the fields are filled except picture
        if(!(empty($_POST["name"]) || empty($_POST["email"]) || empty($_POST["phone-no"]) || empty($_POST["address"]) || empty($_POST["password"]) || empty($_POST["cpassword"]) || empty($_POST["user_type"]))){
            $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
            $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
            $phone_no = filter_var($_POST["phone-no"], FILTER_SANITIZE_STRING);
            $address = filter_var($_POST["address"], FILTER_SANITIZE_STRING);
            $password = $_POST["password"];
            $cpassword = $_POST["cpassword"];
            $hashpassword = password_hash($password, PASSWORD_DEFAULT); //password is secured using password_hash
            $user_type = filter_var($_POST["user_type"], FILTER_SANITIZE_STRING);
            $image = $_FILES['image']['name'];
            $image_size = $_FILES['image']['size'];
            $image_tem_name = $_FILES['image']['tmp_name'];
            $image_folder = './assets/uploaded_img/'.$image;

            if(!filter_var($email, FILTER_VALIDATE_EMAIL) === false){ //check the validation of email
                $dbcon = new DBConnector;
                try {
                    $con = $dbcon->getConnection();
                    //checking the entered email address is already in the customer table
                    $query = "SELECT * FROM customer WHERE customer_email=?";
                    $pstmt = $con->prepare($query);
                    $pstmt->bindValue(1,$email,PDO::PARAM_STR);
                    $pstmt->execute();
                    if($pstmt->rowCount() > 0){
                        header("Location: signup.php?message=5"); //if email already exists show error message
                        exit;
                    } else{
                        //checking the entered email address is already in the restaurant table
                        $query = "SELECT * FROM restaurant WHERE restaurant_email=?";
                        $pstmt = $con->prepare($query);
                        $pstmt->bindValue(1,$email,PDO::PARAM_STR);
                        $pstmt->execute();
                        if($pstmt->rowCount() > 0){
                            header("Location: signup.php?message=5");//if email already exists show error message
                            exit;
                        } else{
                            //checking the entered email address is already in the deliver_driver table
                            $query = "SELECT * FROM deliver_driver WHERE driver_email=?";
                            $pstmt = $con->prepare($query);
                            $pstmt->bindValue(1,$email,PDO::PARAM_STR);
                            $pstmt->execute();
                            if($pstmt->rowCount() > 0){
                                header("Location: signup.php?message=5");//if email already exists show error message
                                exit;
                            } else{
                                //checking the entered email address is already in the admin table
                                $query = "SELECT * FROM admin WHERE admin_email=?";
                                $pstmt = $con->prepare($query);
                                $pstmt->bindValue(1,$email,PDO::PARAM_STR);
                                $pstmt->execute();
                                if($pstmt->rowCount() > 0){
                                    header("Location: signup.php?message=5");//if email already exists show error message
                                    exit;
                                }
                            } 
                        }
                    }

                    if($password!=$cpassword){ //compare password and confirm password
                        header("Location: signup.php?message=6"); //show error message if passwords are not matched
                        exit;
                    } elseif($image_size > 2000000){ //check the size of image
                        header("Location: signup.php?message=7"); //show error message as image is big
                        exit;
                    } else{
                        //store details in the database according to the user account type
                        if($user_type=="customer"){
                            $query = "INSERT INTO customer (customer_name, customer_email, customer_contact_no, customer_address, customer_password, customer_picture) VALUES (?, ?, ?, ?, ?, ?)";
                        }  else if($user_type=="restaurant"){
                            $query = "INSERT INTO restaurant (restaurant_name, restaurant_email, restaurant_contact_no, restaurant_address, restaurant_password, restaurant_picture) VALUES (?, ?, ?, ?, ?, ?)";
                        }  else if($user_type=="delivery"){
                            $query = "INSERT INTO deliver_driver (driver_name, driver_email, driver_contact_no, driver_address, driver_password, driver_picture) VALUES (?, ?, ?, ?, ?, ?)";
                        }        
                            
                        
                        $pstmt = $con->prepare($query);
                        $pstmt->bindValue(1, $name);
                        $pstmt->bindValue(2, $email);
                        $pstmt->bindValue(3, $phone_no);
                        $pstmt->bindValue(4, $address);
                        $pstmt->bindValue(5, $hashpassword);
                        $pstmt->bindValue(6, "assets/img/".$image);
                        $pstmt->execute();
                        if($pstmt->rowCount() > 0){
                            move_uploaded_file($image_tem_name, $image_folder); //image is move to the uploaded_img folder
                            header("Location: login.php?message=1"); //redirect to the login page and show msg as successfully registered
                            exit;
                        } else{
                            header("Location: signup.php?message=8"); //show error message as Registered failed!
                            exit;
                        }
                    }
                } catch(PDOException $e){
                    echo $e->getMessage();
                }
                    
            } else{
                header("Location: signup.php?message=4"); //show error message as Invalid email address!
                exit;
            }
        } else{
            header("Location: signup.php?message=3"); //show error message as Please fill out all required feilds!
            exit;
        }
    } else{
        header("Location: signup.php?message=2"); //show error message as Please click the 'Sign Up' button to complete the Signup process!
        exit;
    }
} else{
    header("Location: signup.php?message=1"); //show error message as Invalid Request Method!
    exit;
}

?>