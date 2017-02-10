<?php
require_once("configuration.php");
function login() {
  // check if email or pass are empty
  if (empty($_POST['email']) || empty($_POST['password']))
    return "Empty email or password";
  // sanitize email and password (for html, not sql)
  $email = htmlspecialchars($_POST['email']);
  $password = htmlspecialchars($_POST['password']);
  // hash password
  $password = hash('sha256', $password);
  // connect to mysql db
  global $db_host, $db_name, $db_pass, $db_user;
  $mysql_connection = new mysqli($db_host, $db_user, $db_pass, $db_name);
  // check connection
  if ($mysql_connection->connect_error) {
    return "Couldn't connect to database: ".$mysql_connection->connect_error;
  }
  $email = $mysql_connection->real_escape_string($email);
  // create and execute sql query
  $sql = "SELECT * FROM users WHERE email='$email' AND password_hash='$password'";
  $query_result = $mysql_connection->query($sql);
  // if result is not found, user wrote wrong credentials
  if($query_result->num_rows <= 0)
    return "User not found, check email or password";

  $user = $query_result->fetch_row();
  // start the session
  session_start();
  $_SESSION['user'] = $email;
  $_SESSION['manager_id'] = $user[0];
  $_SESSION['name'] = $user[1];
  header('Location: ../restaurants.php');
  return $user[1];
}