<?php
require_once('validators.php');
require_once('connect_to_db.php');
function register() {
  if (!empty($_POST['name'].$_POST['email']
             .$_POST['password'].$_POST['c_password'])) {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $c_password = htmlspecialchars($_POST['c_password']);
    if (!nameIsValid($name) || !emailIsValid($email)
        || !passwordsAreValid($password, $c_password))
      return 'Server-side validation failed.<br><br>';
    $password_hash = hash('sha256', $password);
    global $mysqli;
    if ($mysqli->connect_error)
      return 'Database connection failed.<br><br>';
    $stmt = $mysqli->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows >= 1)
      return 'User already exists.<br><br>';
    $stmt->close();
    $stmt = $mysqli->prepare('INSERT INTO
                              users (name, email, password_hash, category)
                              VALUES (?, ?, ?, ?)');
    $category = 'manager';
    $stmt->bind_param('ssss', $name, $email, $password_hash, $category);
    $stmt->execute();
    if ($stmt->errno != 0)
      return 'Failed to create user.<br><br>';
    $stmt->close();
    $mysqli->close();
    return 'User created.<br><br>';
  }
}