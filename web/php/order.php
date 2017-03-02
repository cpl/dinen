<?php

require_once 'connect_to_db.php';

// All scripts related to handling orders

function create_order($user_id, $restaurant_id, $data)
{
  if(empty($user_id) || empty($restaurant_id))
    return [ 'status' => Status::ERROR,
             'data'   => 'User id is empty'];
  $comments = htmlspecialchars($data['comments']);
  $mysqli = createMySQLi();
  if ($mysqli->connect_error)
    return ['status' => Status::ERROR,
            'data'   => 'Database connection failed.'];
  $stmt = $mysqli->prepare('INSERT INTO
                            orders (restaurant_id, user_id, comments)
                            VALUES (?, ?, ?)');
  $stmt->bind_param('iis', $restaurant_id, $user_id, $comments);
  $stmt->execute();
  if ($stmt->errno != 0)
  {
    $stmt->close();
    $mysqli->close();
    return ['status' => Status::ERROR,
            'data'   => 'Database connection failed.'];
  }
  $order_id = $stmt->insert_id;
  $stmt->close();
  $stmt = $mysqli->prepare('INSERT INTO
                            order_items (order_id, menu_item_id)
                            VALUES (?, ?)');
  foreach($data['order_items'] as $menu_id)
  {
    // TODO: Check if menu item id belongs to restaurant
    // To prevent haxors from ordering items from other restaurants

    // Check if menu id is integer
    if(strval($menu_id) != strval(intval($menu_id)))
      return ['status' => Status::ERROR,
              'data'   => 'Given non-integer menu id.'];
    $stmt->bind_param('ii', $order_id, $menu_id);
    $stmt->execute();
    if ($stmt->errno != 0)
    {
      $stmt->close();
      $mysqli->close();
      return ['status' => Status::ERROR,
              'data'   => 'Failed adding order.'];
    }
  }
  $stmt->close();
  $mysqli->close();
  return ['status' => Status::SUCCESS,
          'data'   => 'Order successfully added.'];
}
