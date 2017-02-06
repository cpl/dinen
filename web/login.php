<?php

function Login()
{
  // check if email or pass are empty
  if(empty($_POST['email']))
  {
    $this->HandleError("UserName is empty!");
    return false;
  }
  if(empty($_POST['password']))
  {
    $this->HandleError("Password is empty!");
    return false;
  }
  // sanitize email and password (for html, not sql)
  $email = htmlspecialchars($_POST['email']);
  $password = htmlspecialchars($_POST['password']);
  // hash password
  $password = md5($password);

  return true;
}

?>

<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="styles.css">
  <title>Dinen homepage</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<!-- Menu -->
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <!-- Optional TODO: Add dinen icon -->
      <a class="navbar-brand" href="#">Dinen</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="#">Home</a></li>
      <li><a href="#">Business</a></li>
      <li><a href="#">Customers</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
        <li><a href ="#">Login</a></li>
        <li><a href ='#'>Sign up</a></li>
    </ul>
  </div>
</nav>
<!-- Index page body -->
<div class = "container">

  <form id='login' action='login.php' method='post'>
    <label><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="email" required>

    <label><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="password" required>

    <button type="submit">Login</button>
    <input type="checkbox" checked="checked"> Remember me
  </form>

</div>
</body>
