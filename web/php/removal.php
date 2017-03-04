<?php

require_once 'connect_to_db.php';
require_once 'validators.php';
require_once 'globals.php';

function remove_user($user_id, $password){

  if (empty($password))
    return ['status' => Status::ERROR, 'data' => 'Empty password.'];
  if(!passwordIsValid($password))
    return ['status' => Status::ERROR, 'data' => 'Invalid password.'];

  if(!confirm_action($user_id, $password))
    return ['status' => Status::ERROR, 'data' => 'Auth failed'];

  $mysqli = createMySQLi();

  if ($mysqli->connect_error)
    return ['status' => Status::ERROR,
            'data' => 'Database connection failed'];

  $stmt = $mysqli->prepare('DELETE FROM locations
                            WHERE id in (SELECT address_id FROM restaurants
                                         WHERE manager_id = ?)');
  $stmt->bind_param('i', $user_id);
  $stmt->execute();

  if ($stmt->errno != 0)
    return ['status' => Status::ERROR,
            'data' => 'Error executing user/manager adress removal'];
  $stmt->close();

  $stmt = $mysqli->prepare('DELETE FROM users
                            WHERE id = ?');

  $stmt->bind_param('i', $user_id);
  $stmt->execute();

  if ($stmt->errno != 0)
    return ['status' => Status::ERROR,
            'data' => 'Error executing user removal'];

  $stmt->close();
  $mysqli->close();

} // remove_user

function remove_restaurant($restaurant_id, $manager_id, $password){

  if (empty($password))
    return ['status' => Status::ERROR, 'data' => 'Empty password.'];
  if(!passwordIsValid($password))
    return ['status' => Status::ERROR, 'data' => 'Invalid password.'];

  if(!confirm_action($manager_id, $password))
    return ['status' => Status::ERROR, 'data' => 'Auth failed'];

  $mysqli = createMySQLi();

  if ($mysqli->connect_error)
    return ['status' => Status::ERROR,
            'data' => 'Database connection failed'];

  $stmt = $mysqli->prepare('DELETE FROM addresses
                            WHERE id in (SELECT address_id FROM restaurants
                                         WHERE manager_id = ? AND id = ?)');
  $stmt->bind_param('ii', $manager_id, $restaurant_id);
  $stmt->execute();

  if ($stmt->errno != 0)
    return ['status' => Status::ERROR,
            'data' => 'Error executing restaurant address removal'];

  $stmt->close();

  $stmt = $mysqli->prepare('DELETE FROM restaurants
                            WHERE manager_id = ? AND id = ?');
  $stmt->bind_param('ii', $manager_id, $restaurant_id);
  $stmt->execute();

  if ($stmt->errno != 0)
    return ['status' => Status::ERROR,
            'data' => 'Error executing restaurant removal'];

  $stmt->close();
  $mysqli->close();

} // remove_restaurant

function remove_menu($menu_id, $manager_id, $password){

  if (empty($password))
    return ['status' => Status::ERROR, 'data' => 'Empty password.'];
  if(!passwordIsValid($password))
    return ['status' => Status::ERROR, 'data' => 'Invalid password.'];

  if(!confirm_action($manager_id, $password))
    return ['status' => Status::ERROR, 'data' => 'Auth failed'];

  $mysqli = createMySQLi();

  if ($mysqli->connect_error)
    return ['status' => Status::ERROR,
            'data' => 'Database connection failed'];

  $stmt = $mysqli->prepare('DELETE FROM menus
                            WHERE id = ? AND restaurant_id in (
                                                    SELECT id FROM restaurant
                                                    WHERE manager_id = ?)');

  $stmt->bind_param('ii', $menu_id, $manager_id);
  $stmt->execute();

  if ($stmt->errno != 0)
    return ['status' => Status::ERROR,
            'data' => 'Error executing menu removal'];

  $stmt->close();
  $mysqli->close();
} // remove_menu

function remove_item($item_id, $manager_id, $password){

  if (empty($password))
    return ['status' => Status::ERROR, 'data' => 'Empty password.'];
  if(!passwordIsValid($password))
    return ['status' => Status::ERROR, 'data' => 'Invalid password.'];

  if(!confirm_action($manager_id, $password))
    return ['status' => Status::ERROR, 'data' => 'Auth failed'];

  $mysqli = createMySQLi();

  if ($mysqli->connect_error)
    return ['status' => Status::ERROR,
            'data' => 'Database connection failed'];

  $stmt = $mysqli->prepare('DELETE FROM menu_items
                            WHERE id = ? AND restaurant_id in (
                                                    SELECT id FROM restaurant
                                                    WHERE manager_id = ?)');

  $stmt->bind_param('ii', $item_id, $manager_id);
  $stmt->execute();

  if ($stmt->errno != 0)
    return ['status' => Status::ERROR,
            'data' => 'Error executing item removal'];

  $stmt->close();
  $mysqli->close();

} // remove_item

function confirm_action($user_id, $password){

  $mysqli = createMySQLi();

  if ($mysqli->connect_error)
    return ['status' => Status::ERROR, 'data' => 'Database connection failed.'];

  $password_hash = hash('sha256', $password);
  $stmt = $mysqli->prepare('SELECT * FROM users WHERE id = ?
                            AND password_hash = ?');
  $stmt->bind_param('is', $user_id, $password_hash);
  $stmt->execute();
  $stmt_result = $stmt->get_result();

  if ($stmt_result->num_rows <= 0)
  {
    $stmt->close();
    $mysqli->close();
    return false;
  }
  $stmt->close();
  $mysqli->close();
  return true;
} // confirm_action

?>
