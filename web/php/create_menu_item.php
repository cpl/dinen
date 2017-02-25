<?php

function createMenuItem($user_id, $name, $section, $description, $price, $menu_id)
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
