<?php

header('Content-Type: application/json');

require_once '../../php/globals.php';
require_once '../../php/config.inc.php';
require_once '../../php/jwt_util.php';
require_once '../../php/register.php';
require_once '../../php/login.php';
require_once '../../php/menu.php';
require_once '../../php/restaurant.php';
require_once '../../php/order.php';
require_once '../../php/search.php';
require_once '../../php/removal.php';

$request = htmlspecialchars($_POST['request']);
switch ($request) {
  case 'register':
    processRegisterRequest();
    break;
  case 'login':
    processLoginRequest();
    break;
  case 'create_restaurant':
    processCreateRestaurantRequest();
    break;
  case 'get_restaurants':
    processGetRestaurantsRequest();
    break;
  case 'get_menu':
    processGetMenuRequest();
    break;
  case 'logout':
    processLogoutRequest();
    break;
  case 'add_menu_item':
    processAddMenuItem();
    break;
  case 'create_order':
    processOrder();
    break;
  case 'get_unfinished_order_items':
    processGetUnfinishedOrderItems();
    break;
  case 'remove_restaurant':
    processRemoveRestaurant();
    break;
  case 'get_orders':
    processGetOrders();
    break;
  case 'get_restaurants_near_user':
    processGetRestaurantsNearUser();
    break;
  case 'mark_order_item_finished':
    processMarkOrderItemFinished();
    break;
}

function processMarkOrderItemFinished() {
  $orderItemId = htmlspecialchars($_POST['item']);
  echo json_encode(set_order_item_finished($orderItemId));
}

function processGetRestaurantsNearUser() {
  $lat = htmlspecialchars($_POST['lat']);
  $lng = htmlspecialchars($_POST['lng']);

  echo json_encode(search($lat, $lng));
} // processGetRestaurantsNearUser

function processRegisterRequest() {
  $requestData = json_decode($_POST['data'], true);
  $name = htmlspecialchars($requestData['name']);
  $email = htmlspecialchars($requestData['email']);
  $password = htmlspecialchars($requestData['password']);
  $password_confirmation
    = htmlspecialchars($requestData['password_confirmation']);
  echo json_encode(register($name, $email, $password, $password_confirmation));
}

function processLoginRequest() {
  $requestData = json_decode($_POST['data'], true);
  $email = htmlspecialchars($requestData['email']);
  $password = htmlspecialchars($requestData['password']);

  $userDataGrabAttempt = getUserDataForJWT($email, $password);

  if ($userDataGrabAttempt['status'] !== Status::SUCCESS) {
    echo json_encode($userDataGrabAttempt);
    return;
  }

  $result = ['status' => Status::SUCCESS];
  $userData = $userDataGrabAttempt['data'];
  $result['data'] = createJWT($userData['email'], $userData['name'],
                              $userData['category'], $userData['id']);
  echo json_encode($result);
}

function processCreateRestaurantRequest() {
  // MUST INCLUDE COUNTRY!!!
  // FRONTEND's button doesn't send the COUNTRY!
  if (empty($_POST['name']) || empty($_POST['description'])||
      empty($_POST['category']) || empty($_POST['jwt'] ||
      empty($_POST['street1'] || empty($_POST['town'])))) {
    echo json_encode(['status' => Status::ERROR,
                      'data'   => 'Oops, some of the required fields / jwt are empty']);
    return;
  }
  if (checkJWT($_POST['jwt'])['status'] !== Status::SUCCESS) {
    echo $_POST['jwt'];
    echo checkJWT($_POST['jwt'])['data'];
    return;
  }
  $name = htmlspecialchars($_POST['name']);
  $description = htmlspecialchars($_POST['description']);
  $category = htmlspecialchars($_POST['category']);

  $street1 = htmlspecialchars($_POST['street1']);
  if (empty($_POST['street2']))
    $street2 = "";
  else
    $street2 = htmlspecialchars($_POST['street2']);
  if (empty($_POST['postcode']))
    $postcode = "";
  else
    $postcode = htmlspecialchars($_POST['postcode']);
  $town = htmlspecialchars($_POST['town']);

  $payload = getJWTPayload($_POST['jwt']);
  echo json_encode(create_restaurant($payload['user_category'], $payload['user_id'],
                    $name, $description, $category, $street1, $street2, $postcode, $town));
}

function processGetRestaurantsRequest() {
  if (checkJWT($_POST['jwt'])['status'] !== Status::SUCCESS) {
    echo json_encode(checkJWT($_POST['jwt']));
    //echo checkJWT($_POST['jwt'])['data'];
    return;
  }
  $payload = getJWTPayload($_POST['jwt']);
  $json = json_encode(get_restaurants($payload['user_id'],
                                      $payload['user_category']));
  echo $json;
}

function processGetMenuRequest() {
  if(empty($_POST['restaurant_id'])) {
    echo json_encode("Restaurant id is not included in request");
    return;
  }
  $restaurant_id = htmlspecialchars($_POST['restaurant_id']);
  $json = json_encode(get_menu($restaurant_id));
  echo $json;
}

function processLogoutRequest() {
  $jwt = $_POST['jwt'];
  if (checkJWT($jwt)['status'] === Status::SUCCESS) {
    if (blackListJWT($jwt)['status'] !== Status::SUCCESS) {
      # Hmm.
      //processLogoutRequest();
    }
  }
  echo json_encode(['status' => Status::SUCCESS]);
}

function processAddMenuItem()
{
  $jwt = $_POST['jwt'];
  if (checkJWT($jwt)['status'] !== Status::SUCCESS) {
    echo json_encode(['status' => Status::ERROR,
                      'data'   => 'Wrong jwt sent']);
    return;
  }
  $payload = getJWTPayload($jwt);
  $name = htmlspecialchars($_POST['name']);
  $section = htmlspecialchars($_POST['section']);
  $description = htmlspecialchars($_POST['description']);
  $price = htmlspecialchars($_POST['price']);
  $restaurant_id = htmlspecialchars($_POST['restaurant_id']);
  echo json_encode(create_menu_item($payload['user_id'], $name,
                                    $section, $description,
                                    $price, $restaurant_id));
}

function processOrder()
{
  $comments = htmlspecialchars($_POST['comments']);
  echo json_encode(create_order($_POST['restaurant'], $_POST['comments'],
                                json_decode($_POST['order_items'])));
}

function processGetUnfinishedOrderItems()
{
  $jwt = $_POST['jwt'];
  if (checkJWT($jwt)['status'] !== Status::SUCCESS) {
    echo json_encode(['status' => Status::ERROR,
                      'data'   => 'Wrong jwt sent']);
    return;
  }
  $restaurant_id = htmlspecialchars($_POST['restaurant_id']);
  echo json_encode(get_unfinished_order_items($restaurant_id));
}

function processGetOrders()
{
  $restaurant_id = htmlspecialchars($_POST['restaurant_id']);
  echo json_encode(get_orders($restaurant_id));
}

function processRemoveRestaurant()
{
  $restaurant_id = htmlspecialchars($_POST['restaurant_id']);
  $password = htmlspecialchars($_POST['password']);
  $jwt = $_POST['jwt'];
  if (checkJWT($jwt)['status'] !== Status::SUCCESS) {
    echo json_encode(['status' => Status::ERROR,
                      'data'   => 'Wrong jwt sent']);
    return;
  }
  $payload = getJWTPayload($jwt);
  $id = $payload['user_id'];
  echo json_encode(remove_restaurant($restaurant_id, $id, $password));
}
