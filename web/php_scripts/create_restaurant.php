<?php
require_once 'validators.php';
require_once 'connect_to_db.php';
function create_restaurant($name, $description, $category) {
  if (session_status() == PHP_SESSION_NONE)
    return 'You are not logged in, or session has expired.';
  if (empty($name) || empty($description) || empty($category))
    return 'One of inputs of registration form is empty';
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
  $stmt->close();
  $mysqli->close();
  return 'success';
}

function create_schedule($dayNum, $fromTime, $toTime)
{
  if($dayNum < 0 || $dayNum > 7)
    return 'Invalid day format';
  if($fromTime)
  // TODO
}
