<?php
include 'includes/header.php';
?>

<div class="container" >
  <div class="jumbotron" >
  <h2 style="text-align:center;">New Orders</h2>
  
  <form role="form">
          <div class="row">
          <div class="col-md-6">
           <div class="form-group">
            <label for="email">Full Name: </label>
            <input type="text" class="form-control" name="fullname">
          </div>
          </div>
          <div class="col-md-6">
           <div class="form-group">
            <label for="email">username</label>
            <input type="text" class="form-control" name="username" >
          </div>
          </div>
           </div>
           <div class="row">
          <div class="col-md-6">
           <div class="form-group">
            <label for="email">Email: </label>
            <input type="text" class="form-control" name="email">
          </div>
          </div>
          <div class="col-md-6">
           <div class="form-group">
            <label for="email">Phone number: </label>
            <input type="text" class="form-control" name="phonenumber" >
          </div>
          </div>
           </div>
           <div class="row">
          <div class="col-md-6">
           <div class="form-group">
            <label for="email">Address: </label>
            <input type="text" class="form-control" name="address">
          </div>
          </div>
          <div class="col-md-6">
           <div class="form-group">
            <label for="email">Password: </label>
            <input type="text" class="form-control" name="password" >
          </div>
          </div>
           </div>

<button type="button" class="btn btn-success">Success</button>

  </form>
  
  </div>  
</div>


<?php
include 'includes/footer.php';
?>