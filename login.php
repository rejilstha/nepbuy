<?php
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

<form method ="post" action="body.php">
	<div>
		UserName/Email: <input name="username-email" type="text" />
	</div>
	<div>
		Password: <input name="password" type="password" />
	</div>
	<div>
		<input type="submit" name="login" value="Login" />
	</div>
</form>

<a href="signup.php">Create account</a>