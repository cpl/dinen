<?php
require_once('connect_to_db.php');
function register() {
  if(empty($_POST['name']))
    return 'Name required.';
  if(empty($_POST['email']))
    return 'Email required.';
  if(empty($_POST['password']))
    return 'Password required.';
  if(empty($_POST['c_password']))
    return 'Password confirmation required.';
  $name = htmlspecialchars($_POST['name']);
  $email = htmlspecialchars($_POST['email']);
  $password = htmlspecialchars($_POST['password']);
  $c_password = htmlspecialchars($_POST['c_password']);
  if($password != $c_password)
    return 'Passwords do not match.';
  if(strlen($password) < 8)
    return 'Passwords must be more than 7 characters.';
  if(strlen($password) > 250)
    return 'Passwords must be less than 251 characters.';
  if (!preg_match('/[0-9]+/', $password))
    return 'Passwords must include at least one number!';
  if (!preg_match('/[a-zA-Z]+/', $password))
    return 'Passwords must include at least one letter!';
  if (!preg_match('/^[a-zA-Z-]*$/',$name))
    return 'Names can only consist of letters and hyphens.';
  if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    return 'Invalid email.';
  $password_hash = hash('sha256', $password);
  global $mysqli;
  if ($mysqli->connect_error)
    return 'Database connection failed.';
  $email = $mysqli->real_escape_string($email);
  $query = "SELECT * FROM users WHERE email = '$email'";
  if($mysqli->query($query)->num_rows >= 1)
    return 'User already exists.';
  $query= "INSERT INTO users (name, email, password_hash, category)
           VALUES ('$name', '$email', '$password_hash', 'manager')";
  if ($mysqli->query($query) === FALSE)
    return 'Failed to create user.';
  $mysqli->close();
  return 'User created.';
}