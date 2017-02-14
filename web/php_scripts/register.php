<?php
require_once 'validators.php';
require_once 'connect_to_db.php';
echo register();
function register() {
  if (!empty($_POST['name'].$_POST['email']
             .$_POST['password'].$_POST['c_password'])) {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $c_password = htmlspecialchars($_POST['c_password']);
    if (!nameIsValid($name) || !emailIsValid($email)
        || !passwordsAreValid($password, $c_password))
      return 'Either name or email or password are not of required format';
    $password_hash = hash('sha256', $password);
    global $mysqli;
    if ($mysqli->connect_error)
      return 'Database connection failed';
    $stmt = $mysqli->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows >= 1)
      return 'User already exists';
    $stmt->close();
    $stmt = $mysqli->prepare('INSERT INTO
                              users (name, email, password_hash, category)
                              VALUES (?, ?, ?, ?)');
    $category = 'manager';
    $stmt->bind_param('ssss', $name, $email, $password_hash, $category);
    $stmt->execute();
    if ($stmt->errno != 0)
      return 'Failed to create user';
    $stmt->close();
    $mysqli->close();
    return 'User created';
  }
}
