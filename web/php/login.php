<?php

require_once 'config.inc.php';
require_once 'globals.php';
require_once 'validators.php';
require_once 'connect_to_db.php';
require_once 'confirm.php';

function login($email, $password) {
  if (empty($email.$password))
    return 'Email and password are empty';
  # Sanitize email and password (for PHP, not SQL).
  $mysqli = createMySQLi();
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
  if ($user[6] == 0) {
    # Create the confirmation email.
    $confirmation = create_confirmation($user[0], $user[1], $user[2]);
    if($confirmation == 'success')
      return 'Please activate your account first, a confirmation email was sent';
    else
      return $confirmation;
  }
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

function getUserDataForJWT($email, $password) {
  $result = ['status' => Status::UNSET];

  if (empty($email.$password))
    $result['data'] = 'Empty email and password.';
  if (!emailIsValid($email))
    $result['data'] = 'Invalid email.';
  if(!passwordIsValid($password))
    $result['data'] = 'Invalid password.';

  $mysqli = createMySQLi();

  if ($mysqli->connect_error)
    $result['data'] = 'Database connection failed.';

  $password_hash = hash('sha256', $password);
  $stmt = $mysqli->prepare('SELECT * FROM users WHERE email = ?
                            AND password_hash = ?');
  $stmt->bind_param('ss', $email, $password_hash);
  $stmt->execute();
  $stmt_result = $stmt->get_result();

  # If no users are found, then the credentials are incorrect.
  if ($stmt_result->num_rows <= 0)
    $result['data'] = 'Invalid email-password combination.';

  $user = $stmt_result->fetch_row();

  # If the user's email hasn't been confirmed.
  if ($user[6] == 0) {
    # Create the confirmation email.
    $confirmation = create_confirmation($user[0], $user[1], $user[2]);
    if($confirmation == 'success')
      $result['data']
        = 'Account email confirmation sent, but email not confirmed.';
    else
      $result['data'] = $confirmation;
  }

  if ($result['status'] == Status::UNSET) {
    $result['status'] = Status::SUCCESS;
    $result['data'] = ['email' => $user[2], 'name' => $user[1],
                       'category' => $user[4]];
  }

  $stmt->close(); $mysqli->close();
  return $result;
}