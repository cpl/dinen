<?php
require_once 'validators.php';
require_once 'connect_to_db.php';
require_once 'confirm.php';

function register($name, $email, $password, $confirmation_password) {
  if (empty($name.$email.$password.$confirmation_password))
    return 'Register form is empty';
  if (!nameIsValid($name))
    return 'Name is not of the required format("Name Surname").';
  if (!emailIsValid($email))
    return 'Email is not of the required format.';
  if (!passwordsAreValid($password, $confirmation_password))
    return 'Passwords are invalid.';
  $password_hash = hash('sha256', $password);
  $mysqli = createMySQLi();
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
  {
    $stmt->close();
    $mysqli->close();
    return 'Failed to create user.';
  }

  $stmtuid = $stmt->insert_id;

  $stmt->close();
  $mysqli->close();

  $conf_status = create_confirmation($stmtuid, $name, $email);
  if($conf_status != "success")
    return $conf_status;

  return 'success';
}
