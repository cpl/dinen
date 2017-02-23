<?php

header('Content-Type: application/json');

require_once '../../php/globals.php';
require_once '../../php/config.inc.php';
require_once '../../php/jwt_util.php';
require_once '../../php/register.php';
require_once '../../php/login.php';
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

  if ($userDataGrabAttempt['status'] != Status::SUCCESS) {
    echo json_encode($userDataGrabAttempt);
    return;
  }

  $result = ['status' => Status::SUCCESS];
  $userData = $userDataGrabAttempt['data'];
  $result['data'] = createJWT($userData['email'], $userData['name'],
                              $userData['category']);
  echo json_encode($result);
}

function processCreateRestaurantRequest() {
  if(empty($_POST['name']) || empty($_POST['description'])||
     empty($_POST['category']) || empty($_POST['jwt']))
  {
    echo "Oops, some of the required fields / jwt are empty";
    return;
  }
  if(!correctJWS($_POST['jwt']))
  {
    echo "Incorrect jwt passed";
    return;
  }
  $name = htmlspecialchars($_POST['name']);
  $description = htmlspecialchars($_POST['description']);
  $category = htmlspecialchars($_POST['category']);
  $payload = getJWTPayload($_POST['jwt']);
  create_restaurant($payload['user_category'], $payload['user_id'],
                    $name, $description, $category);
}
