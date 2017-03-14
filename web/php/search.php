<?php

// search function stub
// Will be used to return all restaurants which are closed to location
// via JSON

require_once 'globals.php';
require_once 'connect_to_db.php';
require_once 'validators.php';

function search($searchstring, $lat, $lng) {

  if(empty($searchstring))
    return ['status' => Status::ERROR, 'data' => 'Empty search request.'];

  if(empty($lat) || empty($lng))
    return ['status' => Status::ERROR, 'data' => 'Our website is centered around your location'];

  $searchstring = "%".$searchstring."%";
  $mysqli = createMySQLi();
  if ($mysqli->connect_error)
    return ['status' => Status::ERROR,
            'data' => 'Database connection failed'];

  $latDiff = 0.02;
  $lngDiff = 0.04;
  $stmt = $mysqli->prepare("SELECT * FROM restaurants WHERE location_id IN (
                            SELECT id FROM locations WHERE 
                              longitude <= (? + $lngDiff) AND 
                              longitude >= (? - $lngDiff) AND
                              latitude  >= (? - $latDiff) AND 
                              latitude  <= (? + $latDiff)
                            ) AND name LIKE ?");

  $stmt->bind_param('dddds',$lng, $lng, $lat, $lat, $searchstring);
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