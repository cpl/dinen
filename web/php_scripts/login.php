<?php
require_once 'validators.php';
require_once 'connect_to_db.php';
function login($email, $password) {
  if (empty($email.$password))
    return 'Email and password are empty';
  # Sanitize email and password (for PHP, not SQL).
  global $mysqli;
  if (!emailIsValid($email))
    return 'Email is invalid.';
  if(!passwordIsValid($password))
    return 'Password is invalid.';
  $password_hash = hash('sha256', $password);
  if ($mysqli->connect_error)
    return 'Database connection failed.';
  $stmt = $mysqli->prepare('SELECT * FROM users WHERE email = ?
                            AND password_hash = ?');
  $stmt->bind_param('ss', $email, $password_hash);
  $stmt->execute();
  $stmt_result = $stmt->get_result();
  # If no users are found, then the credentials are incorrect.
  if ($stmt_result->num_rows <= 0)
    return 'Invalid email-password combination.';
  $user = $stmt_result->fetch_row();

  if ($user[6] == 0)
    return 'Please activate your account first, a confirmation email was sent';

  # Store the user's info in a PHP session.
  if(session_status() == PHP_SESSION_NONE)
    session_start();
  $_SESSION['user_id'] = $user[0];
  $_SESSION['user_name'] = $user[1];
  $_SESSION['user_email'] = $user[2];
  $_SESSION['user_category'] = $user[4];
  $stmt->close();
  $mysqli->close();
  return 'success';
}
