<?php

require_once 'connect_to_db.php';
require_once 'validators.php';

// All scripts related to handling orders

function create_order($restaurant_id, $comments, $data)
{
  // check if restaurant is not emptyand int and data contains only integers
  if(empty($restaurant_id))
    return [ 'status' => Status::ERROR,
             'data'   => 'Restaurant id is empty'];
  if(strval($restaurant_id) != strval(intval($restaurant_id)))
    return [ 'status' => Status::ERROR,
             'data'   => 'HAAAACKS'];
  if(!arrayIsInt($data))
    return [ 'status' => Status::ERROR,
             'data'   => 'HAAAACKS'];

  $mysqli = createMySQLi();
  if ($mysqli->connect_error)
    return ['status' => Status::ERROR,
            'data'   => 'Database connection failed.'];
  // create the order entry in dbase
  $stmt = $mysqli->prepare('INSERT INTO
                            orders (restaurant_id, comments)
                            VALUES (?, ?)');
  $stmt->bind_param('is', $restaurant_id, $comments);
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
  // check if all menu items belong to restaurants
  $items_stringified = "";
  $first = true;
  foreach ($data as $item_id) {
    if(!$first)
      $items_stringified = $items_stringified . ',';
    else
      $first = false;
    $items_stringified = $items_stringified . intval($item_id);
  }
  $stmt = $mysqli->prepare("SELECT * FROM menu_items
                            WHERE restaurant_id NOT IN (?) AND
                            id IN ($items_stringified)");
  $stmt->bind_param('i', $restaurant_id);
  $stmt->execute();
  if ($stmt->errno != 0)
  {
    $stmt->close();
    $mysqli->close();
    return ['status' => Status::ERROR,
            'data'   => 'Failed checking order items.'];
  }
  $stmt->store_result();
  $stmt->fetch();
  if ($stmt->num_rows !== 0)
    return ['status' => Status::ERROR,
            'data'   => 'Some menu items dont belong to restaurant'];
  $stmt->close();
  // now insert the items into array
  $stmt = $mysqli->prepare('INSERT INTO
                            order_items (order_id, menu_item_id)
                            VALUES (?, ?)');
  foreach($data as $item_id)
  {
    $stmt->bind_param('ii', $order_id, $item_id);
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

function mark_order_item_finished($item_id) {
  if (empty($item_id))
    return [ 'status' => Status::ERROR,
             'data'   => 'Order item is empty' ];
  else if (strval($item_id) != strval(intval($item_id)))
    return [ 'status' => Status::ERROR,
             'data'   => 'HAAAACKS' ];

  $mysqli = createMySQLi();
  if ($mysqli->connect_error)
    return [ 'status' => Status::ERROR,
             'data'   => 'Database connection failed.' ];

  $stmt = $mysqli->prepare("UPDATE order_items
                            SET is_finished = 1
                            WHERE id = ?");
  $stmt->bind_param('i', $item_id);
  $stmt->execute();
  if ($stmt->errno != 0) {
    $stmt->close();
    $mysqli->close();
    return [ 'status' => Status::ERROR,
             'data'   => 'Failed to mark item as finished' ];
  }

  $stmt->close();
  $mysqli->close();
  return [ 'status' => Status::SUCCESS,
           'data'   => 'Order item marked as finished' ];
}



function get_orders($restaurant_id)
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

  return [ 'status' => Status::SUCCESS,
           'data' => ($order_list)];
}
