<?php

/// This script holds everything restaurant related
/// Functions:
///
/// change_restaurant(restaurant id, name, description, category)
/// Changes name, description or category for restaurant by the given id
/// Doesn't check if restaurant belongs to manager or if user is manager/logged
/// Fields can be empty
/// returns status/error dictionary
///
/// change_address(address id, town, country, street 1, street 2, postcode)
/// Changes the address given by id
/// As with change restaurant, doesn't check if user has that restaurant
/// fields can be empty
/// returns status/error array
///
/// create_restaurant(user category, user id, name, description, category)
/// Create restaurant. Also creates the address from the data from POST
/// returns 'success' if succeeded, error string otherwise
///
/// create_address(mysqli)
/// only called inside the create_restaurant. Gets data from POST
/// returns address id, on success, error string on failure
///
/// get_restaurants(manager id, user category)
/// gets all restaurants belonging to manager in json format, assumes input was
/// sanitized

require_once 'validators.php';
require_once 'connect_to_db.php';

// Change restaurant function
// assuming every value was htmlspecialchars-sanitized before
// should be tested
// TODO: Check if restaurant belongs to logged in user
function change_restaurant($restaurant_id, $name, $description, $category)
{
  if(empty($restaurant_id))
    return ['status' => Status::ERROR, 'data' => 'Restaurant id not specified'];
  $mysqli = createMySQLi();

  if ($mysqli->connect_error)
    return ['status' => Status::ERROR, 'data' => 'Database connection failed.'];
  // explanation: coalesce returns first non-null value
  // so if one of the values is null, it will just use the previous
  $stmt = $mysqli->prepare("UPDATE restaurants
                            SET name = COALESCE('?', name),
                                description = COALESCE('?', description),
                                category = COALESCE('?', category)
                            WHERE id = ?");

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
  return [ 'status' => Status::SUCCESS, 'data' => 'Changed restaurant'];
}

function change_address($address_id, $town, $country, $street1, $street2, $postcode)
{
  if(empty($address_id))
    return ['status' => Status::ERROR, 'data' => 'Address id not specified'];
  $mysqli = createMySQLi();
  if ($mysqli->connect_error)
    return ['status' => Status::ERROR, 'data' => 'Database connection failed.'];
  $stmt = $mysqli->prepare("UPDATE addresses
                            SET town = COALESCE('?', town),
                                country = COALESCE('?', country),
                                street_name_line_1 = COALESCE('?', street_name_line_1),
                                street_name_line_2 = COALESCE('?', street_name_line_2),
                                postcode = COALESCE('?', postcode)
                            WHERE id = ?");
  $stmt->bind_param('sssi', $town, $country, $street1, $street2, $postcode, $address_id);
  $stmt->execute();
  if ($stmt->errno != 0)
  {
    $mysqli->close();
    $stmt->close();
    return [ 'status' => Status::ERROR, 'data' => 'Failed to change address.'];
  }
  $stmt->close();
  $mysqli->close();
  return [ 'status' => Status::SUCCESS, 'data' => 'Changed address'];
}

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
  // $scheduleReturn = create_schedule($stmt->insert_id, $mysqli);
  create_menu($mysqli, $stmt->insert_id);
  $stmt->close();
  $mysqli->close();
  return 'success';
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

// manager id and user category should be sanitized by now(i.e. not empty and
// without html chars)
function get_restaurants($manager_id, $user_category)
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
  {
    $mysqli->close();
    $stmt->close();
    return ['status' => Status::ERROR,
            'data' => 'Error executing restaurants query'];
  }
  $stmt_result = $stmt->get_result();
  $restaurant_list = array();
  while ($row = $stmt_result->fetch_array()) {
    array_push($restaurant_list, ['name' => $row['name'],
                                  'description' => $row['description'],
                                  'category' => $row['category']]);
  }
  $mysqli->close();
  $stmt->close();
  return [ 'status' => Status::SUCCESS,
           'data' => ($restaurant_list)];
}
