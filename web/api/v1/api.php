<?php
header('Content-Type: application/json');
require_once '../../php_scripts/config.inc.php';
require_once '../../php_scripts/register.php';
require_once '../../php_scripts/login.php';
require_once '../../php_scripts/create_restaurant.php';
$request = htmlspecialchars($_POST['request']);
const DATE_TIME_FORMAT = DateTime::ATOM;
switch ($request) {
  case 'register':
    processRegisterRequest();
    break;
  case 'login':
    processLoginRequest();
    break;
  case 'register_restaurant':
    echo json_encode(create_restaurant());
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

/* Create a JSON Web Token for post-login user authentication (expires after
   six hours). I assume users can be uniquely identified by email. Refer to
   https://tools.ietf.org/html/rfc7519 for information on JWTs. */
function generateJWT($user_email, $user_name, $user_category) {
  $header = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
  $nowInUnixTime = time();
  $sixHoursInSeconds = 6 * 60 * 60;
  # Generate a random id; create a series of random bytes, then encode them in
  # base 64 (so the id is a number).
  $tokenID = base64_encode(random_bytes(32));
  $payload = base64_encode(json_encode([
    'iss' => 'https://dinen.ddns.net/api/v1',
    'sub' => $user_email,
    'aud' => 'https://dinen.ddns.net',
    'exp' => $nowInUnixTime + $sixHoursInSeconds,
    'nbf' => $nowInUnixTime,
    'iat' => $nowInUnixTime,
    'jti' => $tokenID,
    'user_name' => $user_name,
    'user_category' => $user_category
  ]));
  global $api_secret;
  $signature = base64_encode(hash_hmac('sha256', $header.'.'.$payload,
                                       $api_secret));
  return $header.'.'.$payload.'.'.$signature;
}