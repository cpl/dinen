<?php

require_once 'validators.php';
require_once 'connect_to_db.php';

function create_restaurant($user_category, $user_id, $name,
                           $description, $category) {
  // Check if required fields are empty || user is not manager || no connection to dbase
  if (empty($user_category) ||
      $user_category != 'manager' ||
      empty($user_id))
    return 'Not logged in or not manager';
  $mysqli = createMySQLi();

  if ($mysqli->connect_error)
    return 'Database connection failed.';
  $address_id = create_address($mysqli);
  // if is not integer
  if(strval($address_id) != strval(intval($address_id)))
    return $address_id;
  $stmt = $mysqli->prepare('INSERT INTO restaurants (name,
                            description, category, address_id, manager_id)
                            VALUES (?, ?, ?, ?, ?)');

  if (!isValid($name) || !isValid($description) || !isValid($category))
    return;

  // create and execute sql request
  $stmt->bind_param('sssii', $name, $description, $category, $address_id, $user_id);
  $stmt->execute();
  if ($stmt->errno != 0)
    return 'Failed to create restaurant.';
  // get the returned string of schedule creation
  $scheduleReturn = create_schedule($stmt->insert_id, $mysqli);
  create_menu($mysqli, $stmt->insert_id);
  $stmt->close();
  $mysqli->close();
  return $scheduleReturn;
}

// Create all schedules
// Returns 'success' if schedule created
// Error string otherwise
function create_schedule($restaurant_id, $mysqli)
{
  $days = array("monday", "tuesday", "wednesday", "thursday", "friday",
                "saturday", "sunday");
  $stmt = $mysqli->prepare('INSERT INTO schedules (restaurant_id, day_of_week, time_open, time_close)
                            VALUES (?, ?, ?, ?)');
  $dayOfWeek = 1;
  // for each day of the week
  foreach($days as $day)
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
    $stmt->bind_param('ssss', $restaurant_id, $dayOfWeek, $_POST[$startTimeString], $_POST[$endTimeString]);
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
// Returns id of address if successful, error string otherwise
function create_address($mysqli)
{
  if(empty($_POST['town']))
    return 'Town is missing.';
  if(empty($_POST['country']))
    return 'Country is missing.';
  if(empty($_POST['street1']))
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

  if (!isValid($town) || !isValid($country) || !isValid($street1) || !isValid($street2) || !isValid($postcode))
    return;

  $stmt = $mysqli->prepare('INSERT INTO addresses (street_name_line_1,
                            street_name_line_2, town, country, postcode)
                            VALUES (?, ?, ?, ?, ?)');
  $stmt->bind_param('sssss', $street1, $street2, $town, $country, $postcode);
  $stmt->execute();
  if ($stmt->errno != 0)
    return 'Failed to create address' . $stmt->error;
  $id = $stmt->insert_id;
  $stmt->close();
  return $id;
}

function create_menu($mysqli, $restaurant_id)
{
  $mysqli->query("INSERT INTO menus (restaurant_id, name) VALUES ($restaurant_id, 'Main menu')");
}
