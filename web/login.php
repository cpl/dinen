<?php
$message = Login();
function Login()
{
  global $db_host, $db_name, $db_pass, $db_user;
  // check if email or pass are empty
  $message = "Empty email or password";
  if(empty($_POST['email']))
    return $message;
  if(empty($_POST['password']))
    return $message;
  // sanitize email and password (for html, not sql)
  $email = htmlspecialchars($_POST['email']);
  $password = htmlspecialchars($_POST['password']);
  // hash password
  $password = md5($password);
  // connect to mysql dbase
  require_once("configuration.php");
  $mysql_connection = new mysqli($db_host, $db_user, $db_pass, $db_name);
  // check connection
  $message = "Couldn't connect to database";
  if ($mysql_connection->connect_error) {
      //die("Connection failed: " . mysqli_connect_error());
      return $mysql_connection->connect_error;
  }
  $email = $mysql_connection->real_escape_string($email);
  // create and execute sql query
  $sql = "SELECT * FROM users WHERE email='$email' AND password_hash='$password'";
  $query_result = $mysql_connection->query($sql);
  // if result is not found, user wrote wrong credentials
  $message = "User not found, check email or password";
  if($query_result->num_rows <= 0)
    return $message;

  // start the session
  session_start();
  $_SESSION['user'] = $email;
  $message = "User found, main page not implemented (yet)";
  return $message;
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
  <?php echo $message?>

</div>
</body>
