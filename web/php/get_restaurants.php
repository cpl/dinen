<?php

require_once 'connect_to_db.php';

// new function to get restaurants
// manager id and user category should be sanitized by now(i.e. not empty and
// without html chars)
function get_restaurants_new($manager_id, $user_category)
{
  if($user_category != 'manager')
    return [ 'status' => Status::ERROR,
             'data' => 'Ooops, trying to access restaurants while not being manager'];
  $mysqli = createMySQLi();
  if ($mysqli->connect_error)
    return ['status' => Status::ERROR,
            'data' => 'Database connection failed'];
  $stmt = $mysqli->prepare('SELECT * FROM restaurants WHERE manager_id = ?');
  $stmt->bind_param('i', $manager_id);
  $stmt->execute();
  if ($stmt->errno != 0)
    return ['status' => Status::ERROR,
            'data' => 'Error executing restaurants query'];
  $stmt_result = $stmt->get_result();
  $restaurant_list = array();
  while ($row = $stmt_result->fetch_array()) {
    array_push($restaurant_list, ['name' => $row['name'],
                                  'description' => $row['description'],
                                  'category' => $row['category']]);
  }
  return [ 'status' => Status::SUCCESS,
           'data' => ($restaurant_list)];
}
