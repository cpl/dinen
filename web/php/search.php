<?php

// search function stub
// Will be used to return all restaurants which are closed to location
// via JSON

require_once 'globals.php';
require_once 'connect_to_db.php';
require_once 'validators.php';

function search_string($searchstring) {

  if(empty($searchstring))
    return ['status' => Status::ERROR, 'data' => 'Empty search request.'];

  $searchstring = "%".$searchstring."%";
  $mysqli = createMySQLi();
  if ($mysqli->connect_error)
    return ['status' => Status::ERROR,
            'data' => 'Database connection failed'];

  $stmt = $mysqli->prepare('SELECT * FROM restaurants
                            WHERE name LIKE ?');
  $stmt->bind_param('s', $searchstring);
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




// function search($location, $sort_method, $amount)
// {
//   // TODO : Search database for the restaurants closes to $location
//   // Possibly using google maps api, if such exists for php
// }
