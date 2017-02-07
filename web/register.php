<?php

$errmsg = Register();

function Register()
{
  if(empty($_POST['email']))
    return 'NO EMAIL!';
  if(empty($_POST['password']))
    return 'NO PASS!';
  if(empty($_POST['name']))
    return 'NO NAME!';
  if(empty($_POST['c_password']))
    return 'NO CPASS!';

  $EMAIL = htmlspecialchars($_POST['email']);
  $PASSWORD = htmlspecialchars($_POST['password']);
  $C_PASSWORD = htmlspecialchars($_POST['c_password']);
  $NAME = htmlspecialchars($_POST['name']);

  if($PASSWORD != $C_PASSWORD) {
    return 'PASSWORDS DONT MATCH!';
  }

  if(strlen($PASSWORD) < 8)
      return "PASS IS LESS THAN 8";

  if(strlen($PASSWORD) > 250)
      return "PASS IS TOO BIG!";

  if (!preg_match("#[0-9]+#", $PASSWORD)) {
      return "Password must include at least one number!";
  }

  if (!preg_match("#[a-zA-Z]+#", $PASSWORD)) {
      return "Password must include at least one letter!";
  }

  if (!preg_match("/^[a-zA-Z ]*$/",$NAME)) {
     return "NAME HAS INVALID CHARS";
  }

  $PASS_HASH = hash('sha256', $PASSWORD);


  if (!filter_var($EMAIL, FILTER_VALIDATE_EMAIL)) {
      return "INVALID EMAIL";
  }

  require_once("configuration.php");
  $SQL = new mysqli($db_host, $db_user, $db_pass, $db_name);
  if ($SQL->connect_error) {
      die("Connection failed: " . mysqli_connect_error());
      return "SQL CONNECTION ERROR";
  }

  $DB_EMAIL = $SQL->real_escape_string($EMAIL);
  $SQL_Q = "SELECT * FROM users WHERE email='$DB_EMAIL'";
  $SQL_QR = $SQL->query($SQL_Q);

  if($SQL_QR->num_rows >= 1)
    return "USER ALREADY EXISTS";

  $SQL_Q = "INSERT INTO users (name, email, password_hash, category) VALUES ('$NAME', '$EMAIL', '$PASS_HASH', 'manager')";

  if ($SQL->query($SQL_Q) === FALSE) {
      return "ERROR IN MAKING RECORD: " . $SQL->error;
  }

  $SQL->close();

  return "YAY";
}
?>

<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Dinen Registration</title>
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
        <li><a href="index.html">Home</a></li>
        <li><a href="#">Business</a></li>
        <li><a href="#">Customers</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
          <li><a href ="login.php">Login</a></li>
          <li class="active"><a href ='register.php'>Sign up</a></li>
      </ul>
    </div>
  </nav>
  <!-- Index page body -->
  <div class = "container">

    <form id='register' action='register.php' method='post'>
      <hr /><label><b>Name</b></label>
      <input type="text" placeholder="Enter Name" name="name" required>

      <hr /><label><b>Email</b></label>
      <input type="text" placeholder="Enter Email" name="email" required>

      <hr /><label><b>Password</b></label>
      <input type="password" placeholder="Enter Password" name="password" required>

      <hr /><label><b>Confirm Password</b></label>
      <input type="password" placeholder="Enter Password" name="c_password" required>

      <hr/><button type="submit">Register</button>
    </form>
  </div>
</body>
<?php echo $errmsg ?>
</html>
