<?php

// search function stub
// Will be used to return all restaurants which are closed to location
// via JSON

require_once 'globals.php';
require_once 'connect_to_db.php';
require_once 'validators.php';

function search($lat, $lng) {

  if(empty($lat) || empty($lng))
    return ['status' => Status::ERROR, 'data' => 'Our website is centered around your location'];
  $lat = floatval($lat);
  $lng = floatval($lng);
  $mysqli = createMySQLi();
  if ($mysqli->connect_error)
    return ['status' => Status::ERROR,
            'data' => 'Database connection failed'];

  $latDiff = 0.16;
  $lngDiff = 0.32;
  $stmt = $mysqli->prepare("SELECT * FROM restaurants WHERE location_id IN (
                            SELECT id FROM locations WHERE
                              longitude <= (? + $lngDiff) AND
                              longitude >= (? - $lngDiff) AND
                              latitude  >= (? - $latDiff) AND
                              latitude  <= (? + $latDiff))");

  $stmt->bind_param('dddd',$lng, $lng, $lat, $lat);
  $stmt->execute();

  if ($stmt->errno != 0)
    return ['status' => Status::ERROR,
            'data' => 'Error executing search!'];

  $stmt_result = $stmt->get_result();
  $restaurant_list = array();
  while ($row = $stmt_result->fetch_array()) {
    array_push($restaurant_list, ['name'        => $row['name'],
                                  'description' => $row['description'],
                                  'category'    => $row['category'],
                                  'id'          => $row['id'],
                                  'tags'        => $row['tags']]);
  } // while

  $mysqli->close();
  $stmt->close();

  if (!empty($restaurant_list)) {
    return [ 'status' => Status::SUCCESS,
             'data' => ($restaurant_list)];
  } else {
    return ['status' => Status::ERROR,
            'data' => 'No restaurants found!'];
  } // else

} // search_string
