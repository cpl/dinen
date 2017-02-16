<?php
header('Content-Type: application/json');
require_once '../../php_scripts/register.php';
require_once '../../php_scripts/login.php';
require_once '../../php_scripts/create_restaurant.php';
$request = htmlspecialchars($_POST['request']);
switch ($request) {
  case 'register':
    processRegisterRequest();
    break;
  case 'login':
    processLoginRequest();
    break;
  case 'register_restaurant':
    create_restaurant();
    break;
}

function processRegisterRequest() {
  $requestData = json_decode($_POST['data'], true);
  $name = ''; $email = ''; $password = ''; $password_confirmation = '';
  foreach ($requestData as $key => $value) {
    switch ($key) {
      case 'name':
        $name = htmlspecialchars($value);
        break;
      case 'email':
        $email = htmlspecialchars($value);
        break;
      case 'password':
        $password = htmlspecialchars($value);
        break;
      case 'password_confirmation':
        $password_confirmation = htmlspecialchars($value);
        break;
    }
  }
  echo json_encode(register($name, $email, $password, $password_confirmation));
}

function processLoginRequest() {
  $requestData = json_decode($_POST['data'], true);
  $email = ''; $password = '';
  foreach ($requestData as $key => $value) {
    switch ($key) {
      case 'email':
        $email = htmlspecialchars($value);
        break;
      case 'password':
        $password = htmlspecialchars($value);
        break;
    }
  }
  echo json_encode(login($email, $password));
}
