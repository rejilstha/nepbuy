<?php
  require_once __DIR__ . "/../connection.php";
$product_types = get_product_types($CONNECTION);

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

function is_trader_($user_id, $connection) {
    $sqlString = "SELECT COUNT(*) AS COUNT FROM nepbuy_user_roles ur ".
          "JOIN nepbuy_roles r ON r.PK_ROLE_ID=ur.FK_ROLE_ID ".
          "WHERE (r.NAME='Trader' OR r.NAME='Admin') AND ur.FK_USER_ID=$user_id";

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
  <link href="/nepbuy/css/responsive.css" rel="stylesheet"> 
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
        <img src="/nepbuy/data1/images/2.jpg" height="60px" width="60px " class="img-circle" data-toggle="dropdown" style="margin-left: 900px;">
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
      <!--endmyprofile-->
      <div class="col-sm-3 col-md-3 pull-right">
       <a href="#">
        <form class="navbar-form" role="search" action="/nepbuy/advanced_search.php">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Search" name="q" id="srch-term" style="height: 25px; margin-top: 23px;">
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
            <div class="input-group-btn">
              <button class="btn btn-default btn-xs" type="submit"><i class="glyphicon glyphicon-search" style="margin-top: 23px; height: 20px;"></i></button>
            </div>
          </div>
        </form>
      </a>
    </div>
    <div class="collapse navbar-collapse navbar-right">

      <ul class="nav navbar-nav">
        <li class="scroll"><a href="/nepbuy/index.php">Home</a></li>

        <li>
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">All Categories <b class="caret"></b></a>
          <ul class="dropdown-menu multi-level">

            <li class="dropdown-submenu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Butcher</a>
              <ul class="dropdown-menu">
                <li>
                  <a href="list.php">Pig</a>
                  <a href="#">Chicken</a>

                </li>
              </ul>
            </li> 
            <li class="dropdown-submenu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Fish Monger</a>
              <ul class="dropdown-menu">
                <li >
                  <a href="#">Pig</a>
                  <a href="#">Chicken</a>
                </li>
              </ul>
            </li> 
            <li class="dropdown-submenu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Green Grocer</a>
              <ul class="dropdown-menu">
                <li>
                  <a href="#">Pig</a>
                  <a href="#">Chicken</a>
                </li>
              </ul>
            </li> 
            <li class="dropdown-submenu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Bakery</a>
              <ul class="dropdown-menu">
                <li>
                  <a href="#">Pig</a>
                  <a href="#">Chicken</a>
                </li>
              </ul>
            </li>
          </ul>
        </li>
        <?php
          if (preg_match('/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/', $_SESSION["user_session"]) || !is_trader_($_SESSION["user_session"], $CONNECTION)) {
            ?>
              <li class="scroll"><a href="/nepbuy/checkout/cart.php">Cart</a></li>
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
  </div><!--/.container-->
</nav><!--/nav-->
</header>
<!--/header-->
