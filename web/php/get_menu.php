<?php

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
