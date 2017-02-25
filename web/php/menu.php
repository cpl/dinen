<?php

/// All scripts related to handling menu and menu items
///
/// get_menu(restaurant id, menu id)
/// returns all menu items from menu id, restaurant id is used to check if menu
/// is valid. Returns status/data dictionary.
///
/// create_menu_item(user id, name, description, price, menu id)
/// creates menu item in dbase with given parameters.
/// Assumes all input is sanitized.
/// Returns status/data dictionary

require_once 'connect_to_db.php';

function get_menu($restaurant_id, $menu_id)
{
  $mysqli = createMySQLi();
  if ($mysqli->connect_error)
    return ['status' => Status::ERROR,
            'data' => 'Database connection failed'];
  //check if menu exists
  $stmt = $mysqli->prepare('SELECT * FROM menus WHERE id = ? AND restaurant_id = ?');
  $stmt->bind_param('ii', $menu_id, $restaurant_id);
  $stmt->execute();
  if ($stmt->errno != 0)
    return ['status' => Status::ERROR,
            'data' => 'Error executing menu query'];
  $stmt_result = $stmt->get_result();
  if ($stmt_result->num_rows === 0)
    return ['status' => Status::ERROR,
            'data' => 'No menus found'];
  $stmt->close();
  $stmt = $mysqli->prepare('SELECT * FROM menu_items WHERE menu_id = ?');
  $stmt->bind_param('i', $menu_id);
  $stmt->execute();
  if ($stmt->errno != 0)
    return ['status' => Status::ERROR,
            'data' => 'Error executing menu items query'];
  $stmt_result = $stmt->get_result();
  $menu_list = array();
  while ($row = $stmt_result->fetch_array()) {
    array_push($menu_list, ['name' => $row['name'],
                            'section' => $row['section'],
                            'price' => $row['price'],
                            'description' => $row['description']]);
  }
  return [ 'status' => Status::SUCCESS,
           'data' => ($menu_list)];
}

function create_menu_item($user_id, $name, $section, $description, $price, $menu_id)
{
  if(empty($user_id) || empty($name) || empty($section) ||
     empty($price))
    return [ 'status' => Status::ERROR,
              'data' => "Empty required fields given to create menu item"];
  $mysqli = createMySQLi();

  if ($mysqli->connect_error)
  return [ 'status' => Status::ERROR,
            'data' => "Database connection failed"];
  $stmt = $mysqli->prepare('INSERT INTO menu_items (name,
                            description, section, price, menu_id)
                            VALUES (?, ?, ?, ?, ?)');
  // create and execute sql request
  $stmt->bind_param('sssii', $name, $description, $section, $price, $menu_id);
  $stmt->execute();
  if ($stmt->errno != 0)
    return ['status' => Status::ERROR,
            'data' => 'Error executing menu item insertion query'];
  return [ 'status' => Status::SUCCESS];
}
