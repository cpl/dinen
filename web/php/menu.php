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

function get_menu($restaurant_id)
{
  $mysqli = createMySQLi();
  if ($mysqli->connect_error)
    return ['status' => Status::ERROR,
            'data'   => 'Database connection failed'];
  $itemStmt = $mysqli->prepare('SELECT * FROM menu_items WHERE restaurant_id = ?');
  $menu_list = array();
  $itemStmt->bind_param('i', $restaurant_id);
  $itemStmt->execute();
  if ($itemStmt->errno != 0)
    return ['status' => Status::ERROR,
            'data' => 'Error executing menu items query'];
  $itemStmt_result = $itemStmt->get_result();
  while ($row = $itemStmt_result->fetch_array()) {
    array_push($menu_list, ['name'        => $row['name'],
                            'section'     => $row['section'],
                            'price'       => $row['price'],
                            'description' => $row['description'],
                            'id'          => $row['id']]);
  }
  return [ 'status' => Status::SUCCESS,
           'data' => ($menu_list)];
}

function create_menu_item($user_id, $name, $section, $description, $price, $restaurant_id)
{
  if(empty($user_id) || empty($name) || empty($section) ||
     empty($price) || empty($restaurant_id))
    return [ 'status' => Status::ERROR,
              'data' => "Empty required fields given to create menu item"];
  $mysqli = createMySQLi();
  if ($mysqli->connect_error)
    return [ 'status' => Status::ERROR,
             'data' => "Database connection failed"];
  // check if restaurant is owned by user
  $stmt = $mysqli->prepare('SELECT * FROM restaurants
                            WHERE id = ? AND manager_id = ?');
  $stmt->bind_param('ii', $restaurant_id, $user_id);
  $stmt->execute();
  $stmt->store_result();
  if ($stmt->errno != 0)
    return ['status' => Status::ERROR,
            'data' => 'Error executing menu item insertion query'];
  if ($stmt->num_rows === 0)
    return ['status' => Status::ERROR,
            'data' => 'No restaurant with given user id found'];
  // by now we know that there exists restaurant with given id
  $stmt->free_result();
  $stmt->close();
  $stmt = $mysqli->prepare("SELECT * FROM menu_items
                            WHERE name = ? AND restaurant_id = ?");
  $stmt->bind_param('si', $name, $restaurant_id);
  $stmt->execute();
  $stmt_result = $stmt->get_result();
  if ($stmt->errno != 0)
    return ['status' => Status::ERROR,
            'data' => 'Error executing menu item insertion query'];
  if ($stmt_result->num_rows != 0)
    return ['status' => Status::ERROR,
            'data' => 'Item with exact same name found in restaurant'];
  //$row = $stmt_result->fetch_array();
  $stmt->close();
  // insert item into first menu associated with restaurant given by id
  $stmt = $mysqli->prepare('INSERT INTO menu_items (name,
                            description, section, price, restaurant_id)
                            VALUES (?, ?, ?, ?, ?)');
  // create and execute sql request
  $stmt->bind_param('sssdi', $name, $description, $section, $price, $restaurant_id);
  $stmt->execute();
  if ($stmt->errno != 0)
    return ['status' => Status::ERROR,
            'data'   => 'Error executing menu item insertion query'];
  return ['status' => Status::SUCCESS];
}

function get_unfinished_order_items($restaurant_id)
{
  // TODO: Check if user can access this data (is cook)
  if(empty($restaurant_id))
    return [ 'status' => Status::ERROR,
              'data' => "No restaurant id given"];
  $mysqli = createMySQLi();
  if ($mysqli->connect_error)
    return [ 'status' => Status::ERROR,
             'data' => "Database connection failed"];
  // get the orders from dbase to get time of order
  $stmt = $mysqli->prepare('SELECT * FROM orders
                            WHERE restaurant_id = ? AND
                            is_finished = 0');
  $stmt->bind_param('i', $restaurant_id);
  $stmt->execute();
  if ($stmt->errno != 0)
  {
    $mysqli->close();
    return ['status' => Status::ERROR,
            'data' => 'Error getting all orders'];
  }
  $stmt_result = $stmt->get_result();
  $order_list = array();
  while ($row = $stmt_result->fetch_array()) {
    $order_list[$row['id']] = ['id'       => $row['id'],
                               'comments' => $row['comments'],
                               'time'     => $row['time']];
  }
  // get all order items from restaurants
  $stmt = $mysqli->prepare('SELECT * FROM order_items
                            WHERE order_id IN (SELECT id FROM orders
                                  WHERE restaurant_id = ? AND
                                        is_finished = 0)');
  $stmt->bind_param('i', $restaurant_id);
  $stmt->execute();
  if ($stmt->errno != 0)
  {
    $mysqli->close();
    return ['status' => Status::ERROR,
            'data' => 'Error getting all order items'];
  }
  $stmt_result = $stmt->get_result();
  $order_item_list = array();
  while ($row = $stmt_result->fetch_array()) {
    array_push($order_item_list, ['menu_item_id' => $row['menu_item_id'],
                                  'is_finished'  => $row['is_finished'],
                                  'order_id'     => $row['order_id'],
                                  'id'           => $row['id'],
                                  'time'         => $order_list[$row['order_id']]['time']]);
  }
  $mysqli->close();
  return [ 'status' => Status::SUCCESS,
           'data' => ($order_item_list)];
}
