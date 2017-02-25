<?php


function change_restaurant($restaurant_id, $name, $description, $category)
{
  if(empty($restaurant_id))
    return ['status' => Status::ERROR, 'data' => 'Restaurant id not specified'];
  $mysqli = createMySQLi();

  if ($mysqli->connect_error)
    return 'Database connection failed.';
  // explanation: coalesce returns first non-null value
  // so if one of the values is null, it will just use the previous
  $stmt = $mysqli->prepare('UPDATE restaurants
                              SET name = COALESCE(?, name),
                                  description = COALESCE(?, description),
                                  category = COALESCE(?, category),
                            WHERE id = ?');

  if (!isValid($name) || !isValid($description) || !isValid($category))
    return;

  // create and execute sql request
  $stmt->bind_param('sssi', $name, $description, $category, $restaurant_id);
  $stmt->execute();
  if ($stmt->errno != 0)
  {
    $mysqli->close();
    $stmt->close();
    return [ 'status' => Status::ERROR, 'data' => 'Failed to change restaurant.'];
  }
  $stmt->close();
  $mysqli->close();
  return [ 'status' => Status:SUCCESS, 'data' => 'Changed restaurant'];
}
