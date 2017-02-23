<?php

require_once 'globals.php';
require_once 'config.inc.php';
require_once 'validators.php';
require_once 'connect_to_db.php';
require_once 'confirm.php';

function getUserDataForJWT($email, $password) {
  if (empty($email.$password))
    return ['status' => Status::ERROR, 'data' => 'Empty email and password.'];
  if (!emailIsValid($email))
    return ['status' => Status::ERROR, 'data' => 'Invalid email.'];
  if(!passwordIsValid($password))
    return ['status' => Status::ERROR, 'data' => 'Invalid password.'];

  $mysqli = createMySQLi();

  if ($mysqli->connect_error)
    return ['status' => Status::ERROR, 'data' => 'Database connection failed.'];

  $password_hash = hash('sha256', $password);
  $stmt = $mysqli->prepare('SELECT * FROM users WHERE email = ?
                            AND password_hash = ?');
  $stmt->bind_param('ss', $email, $password_hash);
  $stmt->execute();
  $stmt_result = $stmt->get_result();

  # If no users are found, then the credentials are incorrect.
  if ($stmt_result->num_rows <= 0)
    return ['status' => Status::ERROR,
            'data' => 'Invalid email-password combination.'];

  $user = $stmt_result->fetch_row();

  # If the user's email hasn't been confirmed.
  if ($user[6] == 0) {
    # Create the confirmation email.
    $confirmation = create_confirmation($user[0], $user[1], $user[2]);
    if($confirmation == 'success')
      return ['status' => Status::ERROR,
              'data'
                => 'Account email confirmation sent, but email not confirmed.'];
    else
      return ['status' => Status::ERROR, 'data' => $confirmation];
  }

  $stmt->close(); $mysqli->close();
  return ['status' => Status::SUCCESS,
          'data'
            => ['email' => $user[2], 'name' => $user[1],
                'category' => $user[4]]];
}

/*
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
*/