<?php
require_once 'validators.php';
require_once 'connect_to_db.php';

function create_restaurant() {
  // Check if required fields are empty || user is not manager || no connection to dbase
  if (session_status() == PHP_SESSION_NONE)
    session_start();
  if (empty($_SESSION['user_category']) ||
      $_SESSION['user_category'] != 'manager' ||
      empty($_SESSION['user_id']))
    return 'Not logged in or not manager';
  if (empty($_POST['name']))
    return 'Restaurant name is missing';
  if (empty($_POST['description']))
    return 'Description is empty';
  if (empty($_POST['type']))
    return 'Restaurant has no type';
  global $mysqli;
  if ($mysqli->connect_error)
    return 'Database connection failed.';
  $address_id = create_address();
  if($address_id == -1)
    return "Failed to create address.";
  $stmt = $mysqli->prepare('INSERT INTO restaurants (name,' .
                             'description, type, address_id, manager_id)' .
                             ' VALUES (?, ?, ?, ?, ?)');
  // santize name, type and description
  $name = htmlspecialchars($_POST['name']);
  $type = htmlspecialchars($_POST['type']);
  $description = htmlspecialchars($_POST['description']);
  // create and execute sql request
  $stmt->bind_param('sssss', $name, $description, $type, $address_id, $_POST['user_id']);
  $stmt->execute();
  if ($stmt->errno != 0)
    return 'Failed to create restaurant.';
  // get the returned string of schedule creation
  $scheduleReturn = create_schedule();
  $mysqli->close();
  return $scheduleReturn;
}

// Create all schedules
// Returns 'success' if schedule created
// Error string otherwise
function create_schedule($restaurant_id)
{
  // By now mysqli connection should have been created
  global $mysqli;
  $days = array("monday", "tuesday", "wednesday", "thursday", "friday",
                "saturday", "sunday");
  $stmt = $mysqli->prepare('INSERT INTO schedules (restaurant_id, day_of_week, time_open, time_close)' .
                           ' VALUES (?, ?, ?, ?)');
  $dayOfWeek = 1;
  // for each day of the week
  foreach($day in $days)
  {
    // check that day of the week exists in
    $startTimeString = $day . "StartTime";
    $endTimeString = $day . "EndTime";
    // if the time is empty, don't create it
    if(empty($_POST[$startTimeString]) || empty($_POST[$endTimeString]))
    {
      $dayOfWeek++;
      continue;
    }
    // TODO: Check start time string and end time string for
    // potential vulnerabilities
    $stmt->bind_param('ssss', $restaurant_id, $dayOfWeek, $startTimeString, $endTimeString);
    $stmt->execute();
    if ($stmt->errno != 0)
      return 'Failed to create schedule';
    $stmt->reset();
    $dayOfWeek++;
  }
  $stmt->close();
  return 'success';
}

// Create address function
// Creates address entry in sql dbase
// Returns id of address if successful, -1 otherwise
function create_address()
{
  global $mysqli;
  if(empty($_POST['town'])
    return 'Town is missing.'
  if(empty($_POST['country'])
    return 'Country is missing.';
  if(empty($_POST['street1'])
    return 'Empty street input.';
  $town = htmlspecialchars($_POST['town']);
  $country = htmlspecialchars($_POST['country']);
  $street1 = htmlspecialchars($_POST['street1']);
  if(empty($_POST['postcode']))
    $postcode = "";
  else
    $postcode = htmlspecialchars($_POST['postcode']);
  if(empty($_POST['street2']))
    $street2 = "";
  else
    $street2 = htmlspecialchars($_POST['street2']);
  $stmt = $mysqli->prepare('INSERT INTO addresses (street_name_line_1,' .
                           'street_name_line_2, town, country, postcode)' .
                           ' VALUES (?, ?, ?, ?, ?)');
  $stmt->bind_param('sssss', $street1, $street2, $town, $country, $postcode);
  $stmt->execute();
  if ($stmt->errno != 0)
    return -1;
  $id = $stmt->insert_id;
  $stmt->close();
  return $id;
}
