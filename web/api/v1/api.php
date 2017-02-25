<?php

header('Content-Type: application/json');

require_once '../../php/globals.php';
require_once '../../php/config.inc.php';
require_once '../../php/jwt_util.php';
require_once '../../php/register.php';
require_once '../../php/login.php';
require_once '../../php/get_menu.php';
require_once '../../php/get_restaurants.php';
require_once '../../php/create_restaurant.php';

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
}

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
  if (empty($_POST['name']) || empty($_POST['description'])||
      empty($_POST['category']) || empty($_POST['jwt'])) {
    echo "Oops, some of the required fields / jwt are empty";
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
  $payload = getJWTPayload($_POST['jwt']);
  echo json_encode(create_restaurant($payload['user_category'], $payload['user_id'],
                    $name, $description, $category));
}

function processGetRestaurantsRequest() {
  if (checkJWT($_POST['jwt'])['status'] !== Status::SUCCESS) {
    echo json_encode(checkJWT($_POST['jwt'])['data']);
    //echo checkJWT($_POST['jwt'])['data'];
    return;
  }
  $payload = getJWTPayload($_POST['jwt']);
  $json = json_encode(get_restaurants_new($payload['user_id'],
                                          $payload['user_category']));
  echo $json;
}

function processGetMenuRequest() {
  if(empty($_POST['restaurant_id'])) {
    echo json_encode("Restaurant id is not included in request");
    return;
  }
  if(empty($_POST['menu_id'])) {
    echo json_encode("Menu id is not included in request");
    return;
  }
  $restaurant_id = htmlspecialchars($_POST['restaurant_id']);
  $menu_id = htmlspecialchars($_POST['menu_id']);
  $json = json_encode(get_menu($restaurant_id, $menu_id));
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
