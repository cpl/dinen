<?php
require_once("configuration.php");
function register() {
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

  global $db_host, $db_name, $db_pass, $db_user;
  $SQL = new mysqli($db_host, $db_user, $db_pass, $db_name);
  if ($SQL->connect_error) {
    return "Couldn't connect to database: ".$SQL->connect_error;
  }

  $DB_EMAIL = $SQL->real_escape_string($EMAIL);
  $SQL_Q = "SELECT * FROM users WHERE email='$DB_EMAIL'";
  $SQL_QR = $SQL->query($SQL_Q);

  if($SQL_QR->num_rows >= 1)
    return "USER ALREADY EXISTS";

  $SQL_Q = "INSERT INTO users (name, email, password_hash, category) VALUES ('$NAME', '$EMAIL', '$PASS_HASH', 'manager')";

  if ($SQL->query($SQL_Q) === FALSE)
    return "ERROR IN MAKING RECORD: " . $SQL->error;

  $SQL->close();

  return "YAY";
}