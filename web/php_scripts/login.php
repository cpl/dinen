<?php
require_once 'validators.php';
require_once 'connect_to_db.php';
function login() {
  if (empty($_POST['email']) || empty($_POST['password']))
    return 'Empty email or password.';
  # Sanitize email and password (for PHP, not SQL).
  $email = htmlspecialchars($_POST['email']);
  $password = htmlspecialchars($_POST['password']);
  $password_hash = hash('sha256', $password);
  global $mysqli;
  if ($mysqli->connect_error)
    return 'Database connection failed.';
  $stmt = $mysqli->prepare('SELECT * FROM users WHERE email = ?
                            AND password_hash = ?');
  $stmt->bind_param('ss', $email, $password_hash); $stmt->execute();
  $stmt_result = $stmt->get_result();
  # If no users are found, then the credentials are incorrect.
  if($stmt_result->num_rows <= 0)
    return 'User not found, check email and password.';
  $user = $stmt_result->fetch_row();
  # Store the user's info in a PHP session.
  session_start();
  $_SESSION['user'] = $email;
  $_SESSION['manager_id'] = $user[0];
  $_SESSION['name'] = $user[1];
  header('Location: restaurants.php');
  $stmt->close(); $mysqli->close();
  return 'Success.';
}
