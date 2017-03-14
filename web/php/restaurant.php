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
require_once 'mapsapi.php';
require_once 'globals.php';

// Change restaurant function
// assuming every value was htmlspecialchars-sanitized before
// should be tested
// TODO: Check if restaurant belongs to logged in user
function change_restaurant($user_id, $restaurant_id, $name, $description, $category)
{
  if(empty($restaurant_id) || empty($user_id))
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
                            WHERE id = ? AND manager_id = ?");

  if (!isValid($name) || !isValid($description) || !isValid($category))
    return;

  // create and execute sql request
  $stmt->bind_param('sssii', $name, $description, $category, $restaurant_id, $user_id);
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

// TEST
// var_dump(create_restaurant('manager', 1, 'TESTING FROM SCRIPT', 'SCRIPT', 'tavern', 'Bulevardul 1 Decembrie 1918', '', '', 'Bucuresti'));

function create_restaurant($user_category, $user_id, $name,
                           $description, $category, $street1, $street2, $postcode, $town) {
  // Check if required fields are empty || user is not manager || no connection to dbase
  if (empty($user_category) ||
      $user_category != 'manager' ||
      empty($user_id))
    return [ 'status' => Status::SUCCESS, 'data' => 'Not logged in or manager'];
  $mysqli = createMySQLi();

  if ($mysqli->connect_error)
    return [ 'status' => Status::SUCCESS, 'data' => 'Database connection failed'];

  // CREATE ADDRESS AND OBTAIN ID FOR RESTAURANT RELATION
  $adrid = create_address($mysqli, $street1, $street2, $postcode, $town, "");

  $stmt = $mysqli->prepare('INSERT INTO restaurants (name,
                            description, category, manager_id, location_id)
                            VALUES (?, ?, ?, ?, ?)');

  if (!isValid($name) || !isValid($description) || !isValid($category))
    return;

  // create and execute sql request
  $stmt->bind_param('sssii', $name, $description, $category, $user_id, $adrid);
  $stmt->execute();
  $testarr = array();
  array_push($testarr, $name, $description, $category, $user_id, $adrid, $street1, $street2, $postcode, $town);
  if ($stmt->errno != 0)
    return [ 'status' => Status::ERROR, 'data' => 'Failed to create restaurant', 'var_dump' => $testarr];
  // get the returned string of schedule creation
  // $scheduleReturn = create_schedule($stmt->insert_id, $mysqli);
  create_menu($mysqli, $stmt->insert_id);
  $stmt->close();
  $mysqli->close();
  return [ 'status' => Status::SUCCESS, 'data' => 'Success'];;
}

// Create all schedules
// Returns 'success' if schedule created
// Error string otherwise
function create_schedule($restaurant_id, $mysqli) {
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

// TEST
// $mysqli = createMySQLi();
// create_address($mysqli, "Bulevardul Camil Ressu 1", "", "", "Bucuresti", "");

function create_address($mysqli, $street1, $street2, $postcode, $town, $country)
{
  $town = htmlspecialchars($town);
  $country = htmlspecialchars($country);
  $street1 = htmlspecialchars($street1);

  if(empty($postcode))
    $postcode = "";
  else
    $postcode = htmlspecialchars($postcode);
  if(empty($street2))
    $street2 = "";
  else
    $street2 = htmlspecialchars($street2);

  if (!isValid($town) || !isValid($country) || !isValid($street1) || !isValid($street2) || !isValid($postcode))
    return [ 'status' => Status::ERROR, 'data' => 'Invalid input for address'];

  // MAPS GEOCODING API
  $finalAddress = $street1." ".$street2." ".$town." ".$country;
  $geodata = geocode($finalAddress);

  // var_dump($geodata);

  $stmt = $mysqli->prepare('INSERT INTO locations (address_line_1,
                            address_line_2, postcode, city, country, latitude, longitude)
                            VALUES (?, ?, ?, ?, ?, ?, ?)');
  $stmt->bind_param('sssssdd', $street1, $street2, $postcode, $town, $country, $geodata[0], $geodata[1]);
  $stmt->execute();
  if ($stmt->errno != 0)
    return [ 'status' => Status::ERROR, 'data' => 'Failed to create address', 'error' => $stmt->errno];
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
    array_push($restaurant_list, ['name'        => $row['name'],
                                  'description' => $row['description'],
                                  'category'    => $row['category'],
                                  'id'          => $row['id']]);
  }
  $mysqli->close();
  $stmt->close();
  return [ 'status' => Status::SUCCESS,
           'data' => ($restaurant_list)];
}
