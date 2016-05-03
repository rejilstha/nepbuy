<?php
	include('../includes/header.php');

	if(isset($_GET["status"])) {
		switch ($_GET["status"]) {
			case 1:
				//User hasn't logged in
				echo "You need to login before checking out.";
				break;
			
			default:
				# code...
				break;
		}
	}
?>

<!-- hero page -->
<section id="hero-page1">
	<div class="row">
	 <div class="container">
		<div class="col-sm-12">

		<div class="col-sm-4">
			<img src="/nepbuy/images/img/veg.png" width="100%" >
		</div>
		<div class="col-sm-8">
			<h2 class="title">NepBuy Introduction</h2>
			<p class="text">Munchery chefs come from top restaurants. They bring mad skills, tons of passion, and expertise in a delicious array of cuisines. They insist, as we do, on using only the freshest ingredients to make our tasty, nourishing food.</p>
		</div>	
		</div>

		
	</div>
		
	</div>            
</section>		
<!-- hero page  -->

<!-- product special / latest -->
<section id="">		
   <div class="row">
	 <div class="container">
			<div class="col-sm-6">	
				<h1  class="log-title"><i class="fa fa-edit"></i> Login <a id="signup" href="signup.php"><i class="fa fa-users"></i>  Create New Account ?</a></h1>
				<form method ="post" action="/nepbuy/body.php" class="signup-form">
						<input class="inputfield0" placeholder="Username" name="username-email" type="text" />
						<input class="inputfield0" placeholder="Password" name="password" type="password" />
						<input class="btn-submit" type="submit" name="login" value="Login" />
						<a href="/nepbuy/account/forgot_password.php">Forgot password?</a>
				</form>
			</div>

			<div class="col-sm-6">	
			<img src="/nepbuy/images/img/hero1.png">
			</div>

			</div>	
  </div>        
</section>		
<!-- product special end -->


<!-- hero page -->
		<section id="hero-page">
			<div class="row">
			 <div class="container">
				<div class="col-sm-8">
					<h2 class="title">Delivery Info</h2>
					<p class="text">Munchery chefs come from top restaurants. They bring mad skills, tons of passion, and expertise in a delicious array of cuisines. They insist, as we do, on using only the freshest ingredients to make our tasty, nourishing food.</p>
				</div>
				<div class="col-sm-4">
					<img src="/nepbuy/images/img/hero1.png" >
				</div>
			</div>
				
			</div>            
		</section>		
		<!-- hero page  -->




<?php include('../includes/footer.php'); ?>


