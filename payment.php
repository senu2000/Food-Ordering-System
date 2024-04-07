<?php
use MyApp\DBConnector;

if(isset($_POST['quan'])){
    $number = $_POST['quan'];
require 'core/classes/DBConnector.php';
$id = $_GET['pay'];
$dbuser = new DBConnector();
    $con = $dbuser->getConnection();
    $query = "SELECT * FROM menu_item WHERE menu_item_id = '$id' ";
    $pstmt = $con->prepare($query);
    $pstmt->execute();
    $rs = $pstmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rs as $rows) {
        //store that values to variables
        $name = $rows['menu_item_name'];
        $price = $rows['menu_item_price'];
        $image = $rows['menu_item_picture'];
        $total = $number * $price;
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <!-- <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/css/Banner-Heading-Image-images.css">
    <link rel="stylesheet" href="assets/css/Navbar-Right-Links-Dark-icons.css">
</head>

<body style="background: url(assets/img/bg3.jpg); background-size: cover; background-repeat: no-repeat; background-position: center bottom">
    <div id="navbar">
        <nav class="navbar navbar-expand-md bg-dark py-3 navbar-dark">
            <div class="container"><a class="navbar-brand d-flex align-items-center" href="#"><img class="navbar-logo"
                        src="assets/img/logo.png" style="width: 5rem;padding: 0.5rem;"></a><button
                    data-bs-toggle="collapse" class="navbar-toggler" data-bs-target="#navcol-5"><span
                        class="visually-hidden">Toggle navigation</span><span
                        class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navcol-5">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link active" href="index.php">Home<i class="fas fa-home"
                                    style="padding: 0.3rem;color: var(--bs-warning);"></i></a></li>
                        <li class="nav-item"><a class="nav-link" href="foods.php">Foods<i class="fas fa-fish"
                                    style="padding: 0.3rem;color: var(--bs-warning);"></i></a></li>
                        <li class="nav-item"><a class="nav-link" href="restaurent.php">Restaurant<i class="fas fa-warehouse"
                                    style="padding: 0.3rem;color: var(--bs-warning);"></i></a></li>
                        <li class="nav-item"><a class="nav-link" href="cart.php">Cart<i class="fas fa-cart-arrow-down"
                                    style="padding: 0.3rem;color: var(--bs-warning);"></i></a></li>
                    </ul><a class="btn btn-primary ms-md-2" role="button" href="profile.php"
                        style="background: var(--bs-warning);">Login</a>
                </div>
            </div>
        </nav>
    </div>

  <section class="h-100 gradient-custom" >
    <div class="container py-5">
      <div class="row d-flex justify-content-center my-4">
        <div class="col-md-8">
          <div class="card mb-4">
            <div class="card-header py-3">
              <h5 class="mb-0">Order Summary</h5>
            </div>
            <div class="card-body">
              <!-- Single item -->
              <div class="row">
                <div class="col-lg-3 col-md-12 mb-4 mb-lg-0">
                  <!-- Image -->
                  <div class="bg-image hover-overlay hover-zoom ripple rounded" data-mdb-ripple-color="light">
                    <img src="<?php echo $image ?>"
                      class="w-100"/>
                    <a href="#!">
                      <div class="mask" style="background-color: rgba(251, 251, 251, 0.2)"></div>
                    </a>
                  </div>
                  <!-- Image -->
                </div>

                <div class="col-lg-5 col-md-6 mb-4 mb-lg-0 text-center">
                  <!-- Data -->
                  <p><strong><?php echo $name ?></strong></p>

                  <!-- Data -->
                </div>

                <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                    
                <!-- Price -->
                  <p class="text-start text-md-center">
                    <h6>Price: </h6><strong>Rs.<?php echo $price ?>.00</strong>
                  </p>
                  <!-- Price -->

                  <!-- Quantity -->
                  <div class="d-flex mb-4" style="max-width: 300px">
                    <div class="form-outline">
                      <!--<input id="form1" min="0" name="quantity" value="1" type="fixed" class="form-control" />-->
                      <label class="form-label" for="form1">Quantity: <br><strong><?php echo $number ?></strong></label>
                    </div>
                  </div>
                  <!-- Quantity -->
              
                </div>
              </div>
              <!-- Single item -->


              <!--Payment form-->
              <hr class="my-4" />
            </div>
          </div>
          <div class="card mb-4 mb-lg-0">
            <strong><h6 class="text-center">We Accept</h6></strong>
            <div class="card-body mx-auto d-block">
              <img class="me-2" width="220px" src="assets/img/visa.png">
            </div>

          </div>
          <br>
          <div class="col-md-12">
            <div class="card mb-4">
              <div class="card-header py-3">
                <h5 class="mb-0">Payment</h5>
              </div>

              <div class="card-body mx-auto d-block">
                <!--get card number and cvv-->
                <form name="pay">
                  <input type="text" placeholder="Enter card number" name="num" required> &ensp;&ensp; <input type="text" placeholder="CVV" name="cv" required><br><br>
                </form>
                <hr>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
                    <div>
                      <strong>Total amount : </strong>
                      <strong> </strong>
                    </div>
                    <span><strong>Rs.<?php echo $total ?>.00</strong></span>
                  </li>
                </ul><hr>
                <button class="btn btn-primary btn-lg btn-block mx-auto d-block" onclick="fill(<?php echo $id ?>)">Pay Now</button>
              </div>
            </div>
          </div>
          
        </div>
      </div>
    </div>
  </section>
  
  <script src="assets/bootstrap/js/bootstrap.min.js"></script>
  <script src="assets/js/Off-Canvas-Sidebar-Drawer-Navbar-swipe.js"></script>
  <script src="assets/js/Off-Canvas-Sidebar-Drawer-Navbar-off-canvas-sidebar.js"></script>
  <script>
    var ac_num = document.forms['pay']['num'];
    var ac_cv = document.forms['pay']['cv'];
    function fill(x) {
        if (ac_num.value.length > 15 && ac_num.value.length < 17 ){
            if(ac_cv.value.length >2 && ac_cv.value.length < 4 ){
                alert("Payment Success");
                location.href="ordre_statues.php?stat=" + x;

            }else{
                alert("Payment Unseccess \n \t\t\tCheck your CVV number.");
            }   
        } else{
            alert("Payment Unseccess \n \t\t\tCheck your Card Number.");
        }  
    }
</script>
</body>

</html>
