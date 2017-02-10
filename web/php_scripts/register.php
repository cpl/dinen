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
  if (preg_match('/[^a-zA-Z\s-]+/', $name))
    return 'Names must only consist of letters and hyphens.';
  if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    return 'Invalid email.';
  if(strlen($password) < 8)
    return 'Passwords must be more than 7 characters.';
  if(strlen($password) > 250)
    return 'Passwords must be less than 251 characters.';
  if (!preg_match('/[0-9]+/', $password))
    return 'Passwords must include at least one number!';
  if (!preg_match('/[a-zA-Z]+/', $password))
    return 'Passwords must include at least one letter!';
  if($password != $c_password)
    return 'Passwords do not match.';
  $password_hash = hash('sha256', $password);
  global $mysqli;
  if ($mysqli->connect_error)
    return 'Database connection failed.';
  $stmt = $mysqli->prepare('SELECT * FROM users WHERE email = ?');
  $stmt->bind_param('s', $email); $stmt->execute();
  if($stmt->get_result()->num_rows >= 1)
    return 'User already exists.';
  $stmt->close();
  $stmt = $mysqli->prepare('INSERT INTO
                            users (name, email, password_hash, category)
                            VALUES (?, ?, ?, ?)');
  $category = 'manager';
  $stmt->bind_param('ssss', $name, $email, $password_hash, $category);
  $stmt->execute();
  if ($stmt->errno != 0)
    return 'Failed to create user.';
  $stmt->close(); $mysqli->close();
  return 'User created.';
}