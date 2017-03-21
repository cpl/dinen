<?php

require_once 'globals.php';
require_once 'config.inc.php';
require_once 'connect_to_db.php';
require_once 'validators.php';
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
  {
    $stmt->close();
    $mysqli->close();
    return ['status' => Status::ERROR,
            'data' => 'Invalid email-password combination.'];
  }

  $user = $stmt_result->fetch_row();

  # If the user's email hasn't been confirmed.
  if ($user[6] == 0) {
    # Create the confirmation email.
    $confirmation = create_confirmation($user[0], $user[1], $user[2]);
    if($confirmation == 'success')
    {
      $stmt->close();
      $mysqli->close();
      return ['status' => Status::ERROR,
              'data' => 'Account email confirmation sent, but email not confirmed.'];
    }
    else
    {
      $mysqli->close();
      $stmt->close();
      return ['status' => Status::ERROR, 'data' => $confirmation];
    }
  }

  $stmt->close(); $mysqli->close();
  return ['status' => Status::SUCCESS,
          'data' => ['email' => $user[2], 'name' => $user[1],
                     'category' => $user[4], 'id' => $user[0]]];
}