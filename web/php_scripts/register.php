<?php
require_once 'validators.php';
require_once 'connect_to_db.php';
echo register();
function register() {
  if (!empty($_POST['name'].$_POST['email']
             .$_POST['password'].$_POST['c_password'])) {
    // Sanitize input
    global $mysqli;
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $c_password = htmlspecialchars($_POST['c_password']);
    $name = $mysqli->real_escape_string($name);
    $email = $mysqli->real_escape_string($email);
    $password = $mysqli->real_escape_string($password);
    $c_password = $mysqli->real_escape_string($c_password);
    // Check if input is valid (password > 8 char etc.)
    if (!nameIsValid($name) || !emailIsValid($email)
        || !passwordsAreValid($password, $c_password))
      return 'Either name or email or password are not of required format';
    // Create password hash
    $password_hash = hash('sha256', $password);
    if ($mysqli->connect_error)
      return 'Database connection failed';
    // Check if there are users with same email in database, if yes, return
    $stmt = $mysqli->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows >= 1)
      return 'User already exists';
    // Insert the user into database
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
    // Start the session for user
    $stmt = $mysqli->prepare('SELECT * FROM users WHERE email = ?
                              AND password_hash = ?');
    $stmt->bind_param('ss', $email, $password_hash);
    $stmt->execute();
    $stmt_result = $stmt->get_result();
    if ($stmt_result->num_rows <= 0)
      return 'Database connection failed';
    $user = $stmt_result->fetch_row();
    // Store the user's info in a PHP session.
    if(session_status() == PHP_SESSION_NONE)
      session_start();
    $_SESSION['user_id'] = $user[0];
    $_SESSION['user_name'] = $user[1];
    $_SESSION['user_email'] = $user[2];
    $_SESSION['user_category'] = $user[4];
    $stmt->close();
    $mysqli->close();
    return 'User created';
  }
}
