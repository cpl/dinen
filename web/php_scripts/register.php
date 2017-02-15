<?php
require_once 'validators.php';
require_once 'connect_to_db.php';
function register($name, $email, $password, $confirmation_password) {
  if (!empty($name.$email.$password.$confirmation_password)) {
    if (!nameIsValid($name) || !emailIsValid($email)
        || !passwordsAreValid($password, $confirmation_password))
      return 'Server-side validation failed.';
    $password_hash = hash('sha256', $password);
    global $mysqli;
    if ($mysqli->connect_error)
      return 'Database connection failed.';
    $stmt = $mysqli->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows >= 1)
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
    if(session_status() == PHP_SESSION_NONE)
      session_start();
    $_SESSION['user_id'] = $stmt->insert_id;
    $_SESSION['user_name'] = $name;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_category'] = $category;
    $stmt->close();
    $mysqli->close();
    return 'success';
  }
  return 'Server-side validation failed.';
}