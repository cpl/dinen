<?php
header('Content-Type: application/json');
require_once '../../php_scripts/register.php';
$request = htmlspecialchars($_GET['request']);
switch ($request) {
  case 'register':
    processRegisterRequest();
    break;
}
function processRegisterRequest() {
  $requestData = json_decode($_GET['data'], true);
  $name = ''; $email = ''; $password = ''; $password_confirmation = '';
  foreach ($requestData as $key => $value) {
    switch ($value['name']) {
      case 'name':
        $name = htmlspecialchars($value['value']);
        break;
      case 'email':
        $email = htmlspecialchars($value['value']);
        break;
      case 'password':
        $password = htmlspecialchars($value['value']);
        break;
      case 'password_confirmation':
        $password_confirmation = htmlspecialchars($value['value']);
        break;
    }
  }
  echo json_encode(register($name, $email, $password, $password_confirmation));
}