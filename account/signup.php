<?php
	include('../includes/header.php');
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
				<img src="/nepbuy/images/img/hero1.png">
			</div>

		<div class="col-sm-6">	
		<h1 class="log-title">Sign Up</h1>
			<form method ="post" action="body.php" class="signup-form">
			<div>
				<input class="inputfield" name="username" type="text" placeholder="Username"/>
			</div>
			<div>
				<input class="inputfield" name="emailAddress" type="email" placeholder="Email"/>
			</div>
			<div>
				<input class="inputfield" name="password" type="password" placeholder="Password"/>
			</div>
				<input class="inputfield" name="contact" type="number" placeholder="Phone"/>
				<input class="btn-submit" class="inputfield" type="submit" name="signup" value="Sign Up" />
			</form>	
	 	</div>
	</div>
		
  </div>        
</section>		
<!-- product special end -->
<?php include('../includes/footer.php'); ?>