<?php
require_once __DIR__ . "/../connection.php";
$product_types = get_product_types($CONNECTION);

$user_id = $_SESSION["user_session"];

function get_curr_user($user, $connection) {
  $sqlString = "SELECT * FROM nepbuy_users WHERE PK_USER_ID=$user";
  $stid = oci_parse($connection, $sqlString);
  oci_execute($stid);
  return oci_fetch_assoc($stid);
}

function get_product_types($connection) {
  $sqlString = "SELECT * FROM nepbuy_product_types WHERE FK_PARENT_ID IS NULL";
  $stid = oci_parse($connection, $sqlString);
  oci_execute($stid);

  $product_types = array();
  while($product_type = oci_fetch_assoc($stid)) {
    array_push($product_types, $product_type);
  }
  return $product_types;
}

function get_child_product_types($id, $connection) {
  $sqlString = "SELECT * FROM nepbuy_product_types WHERE FK_PARENT_ID=$id";
  $stid = oci_parse($connection, $sqlString);
  $product_types = array();

  if(oci_execute($stid) > 0) {
    $product_types = array();
    while($product_type = oci_fetch_assoc($stid)) {
      array_push($product_types, $product_type);
    }
  }

  return $product_types;
}

function is_trader_($user_id, $connection) {
  $sqlString = "SELECT COUNT(*) AS COUNT FROM nepbuy_user_roles ur ".
  "JOIN nepbuy_roles r ON r.PK_ROLE_ID=ur.FK_ROLE_ID ".
  "WHERE r.NAME='Trader' AND ur.FK_USER_ID=$user_id";

  $stid = oci_parse($connection, $sqlString);
  if(oci_execute($stid) > 0) {
    if(oci_fetch_assoc($stid)["COUNT"] > 0) {
      return true;
    }
  }

  return false;
}

function is_trader_or_admin($user_id, $connection) {
  $sqlString = "SELECT COUNT(*) AS COUNT FROM nepbuy_user_roles ur ".
  "JOIN nepbuy_roles r ON r.PK_ROLE_ID=ur.FK_ROLE_ID ".
  "WHERE (r.NAME='Trader'OR r.NAME='Admin') AND ur.FK_USER_ID=$user_id";

  $stid = oci_parse($connection, $sqlString);
  if(oci_execute($stid) > 0) {
    if(oci_fetch_assoc($stid)["COUNT"] > 0) {
      return true;
    }
  }

  return false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>NepBuy</title>

  <link rel="shortcut icon" href="/nepbuy/images/ico/smalllogo.png">

  <!-- core CSS -->
  <link href="/nepbuy/css/bootstrap.min.css" rel="stylesheet">
  <link href="/nepbuy/css/font-awesome.min.css" rel="stylesheet">
  <link href="/nepbuy/css/main.css" rel="stylesheet"> 
  <link href="/nepbuy/css/front-style.css" rel="stylesheet"> 
  <link rel="stylesheet" href="/nepbuy/css/bootstrap-submenu.min.css">



  <!-- Start WOWSlider.com HEAD section --> <!-- add to the <head> of your page -->
  <link rel="stylesheet" type="text/css" href="/nepbuy/engine1/style.css" />
  <script type="text/javascript" src="/nepbuy/engine1/jquery.js"></script>
  <!-- End WOWSlider.com HEAD section -->
  <!-- GOOGLE fONTS-->
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:300|Oswald:300|Oxygen' rel='stylesheet' type='text/css'>

</head><!--/head-->

<body>
	
  <!--/header menu-->
  <header id="header">
   <nav id="main-menu" class="navbar navbar-default navbar-fixed-top" role="banner">
     <div class="container">
       <div class="navbar-header">
         <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
           <span class="sr-only">Toggle navigation</span>
           <span class="icon-bar"></span><!--- for icons if kept---->
           <span class="icon-bar"></span><!--- for icons if kept---->
           <span class="icon-bar"></span><!--- for icons if kept---->
         </button>
         <a class="navbar-brand" href="/nepbuy/index.php"><img src="/nepbuy/images/rejil.png" height="60px" width="140px"></a>
       </div>

       <!--myprofile-->
       <div class="dropdown">
        <?php
        if(!preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/', $_SESSION["user_session"])) {
          $user = get_curr_user($user_id, $CONNECTION);
          ?>
          <img src="<?php echo $user["PHOTO_LOCATION"]; ?>" height="60px" width="60px " class="img-circle" data-toggle="dropdown" style="margin-left: 900px;">
          <?php
        } else {
          ?>
          <img src="/nepbuy/data1/images/2.jpg" height="60px" width="60px " class="img-circle" data-toggle="dropdown" style="margin-left: 900px;">
          <?php
        }
        ?>
        <ul class="dropdown-menu pull-right">
          <?php
          if(preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/', $_SESSION["user_session"])) {
            ?>
            <li><a href="/nepbuy/account/login.php">Login</a></li>
            <li><a href="/nepbuy/account/signup.php">Sign Up</a></li>

            <?php
          } else {
            ?>
            <li><a href="/nepbuy/account/profile.php">Profile</a></li>
            <li>
              <form method="post" action="/nepbuy/index.php">
                <button name="admin-logout-submit" style="background:#FF8E35;color:#FFF;min-height:40px;width:100%" type="submit"><i class="icon-off">Logout</i></button>
              </form>
            </li>
            <?php  
          }
          ?>
        </ul>
      </div>


      <div class="row">
        <!--endmyprofile-->
        <div class="col-sm-5 col-md-5 pull-right" style="margin-top: 19px">
         <a href="#">
          <form class="navbar-form" role="search" action="/nepbuy/advanced_search.php">
            <div class="col-sm-6">
              <input type="text" class="form-control" placeholder="Search" name="q" >
            </div>
            <div class="col-sm-4">
              <select class="form-control" name="category">
                <option value="all-categories">All categories</option>
                <?php
                foreach ($product_types as $product_type) {
                  ?>
                  <option value="<?php echo $product_type['PK_PRODUCT_TYPE_ID']; ?>"><?php echo $product_type["NAME"]; ?></option>
                  <?php       
                }
                ?>
              </select>
            </div>
            <div class="col-sm-2">
              <button class="btn btn-default btn-xs" type="submit"><i class="glyphicon glyphicon-search" style="margin-top: 10px; height: 20px;"></i></button>
            </div>
          </form>
        </a>
      </div>


      <div class="collapse navbar-collapse navbar-right col-sm-5 col-md-5">

        <ul class="nav navbar-nav">
          <li class="scroll"><a href="/nepbuy/index.php">Home</a></li>

          <li>
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">All Categories <b class="caret"></b></a>
            <ul class="dropdown-menu multi-level">

              <?php 
              foreach ($product_types as $product_type) {
                $child_product_types = get_child_product_types($product_type["PK_PRODUCT_TYPE_ID"], $CONNECTION);
                if(count($child_product_types) == 0) {
                  ?>
                  <li>
                    <a href="/nepbuy/product_type/details.php?id=<?php echo $product_type["PK_PRODUCT_TYPE_ID"]; ?>"><?php echo $product_type["NAME"]; ?></a>
                  </li>
                  <?php
                } else {
                  ?>
                  <li class="dropdown-submenu">
                    <a href="/nepbuy/product_type/details.php?id=<?php echo $product_type["PK_PRODUCT_TYPE_ID"]; ?>" class="dropdown-toggle" data-toggle="dropdown"><?php echo $product_type["NAME"]; ?></a>
                    <ul>
                      <li>
                        <?php
                        foreach ($child_product_types as $child_product_type) {
                          ?>
                          <a href="/nepbuy/product_type/details.php?id=<?php echo $child_product_type["PK_PRODUCT_TYPE_ID"]; ?>"><?php echo $child_product_type["NAME"]; ?></a>
                          <?php
                        }
                        ?>
                      </li>
                    </ul>
                  </li>
                  <?php
                }
              }
              ?>
            </ul>
            <?php
            if (preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/', $_SESSION["user_session"]) || !is_trader_($_SESSION["user_session"], $CONNECTION)) {
              ?>
              <li class="scroll"><a href="/nepbuy/checkout/cart.php">Cart</a></li>
              <?php
            } 
            if(!preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/', $_SESSION["user_session"]) && is_trader_or_admin($_SESSION["user_session"], $CONNECTION)) {
              ?>
              <li class="scroll"><a href="/nepbuy/shop/index.php">Shops</a></li>
              <li class="scroll">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Reports <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="/nepbuy/reports/delivered.php">Delivered</a></li> 
                  <li><a href="/nepbuy/reports/pending.php">Pending</a></li> 
                  <li><a href="/nepbuy/reports/sales.php">Sales</a></li> 
                  <li><a href="/nepbuy/reports/stock_levels.php">Stock levels</a></li>
                </ul>
              </li>
              <?php
            }
            ?>

            <?php
            if(preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/', $_SESSION["user_session"])) {
              ?>
              <li class="scroll"><a href="/nepbuy/account/login.php">Login</a></li>
              <li class="scroll"><a href="/nepbuy/account/signup.php">Signup</a></li>
              <?php
            }
            ?>
          </ul>
        </div>
      </div>
    </div><!--/.container-->
  </nav><!--/nav-->
</header>
<!--/header-->
