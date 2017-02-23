<?php

require_once 'config.inc.php';

/* Create a JSON Web Token for post-login user authentication (expires after
   six hours). I assume users can be uniquely identified by email. Refer to
   https://tools.ietf.org/html/rfc7519 for information on JWTs. */
function createJWT($user_email, $user_name, $user_category) {
  $header = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));

  $nowInUnixTime = time();
  $sixHoursInSeconds = 6 * 60 * 60;

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
    $api_secret, true));

  return $header.'.'.$payload.'.'.$signature;
}

# Make sure the JWT is valid.
function checkJWT($jwt) {
  $jwt_components = explode(".", $jwt);
  $header = $jwt_components[0]; $payload = $jwt_components[1];
  $signature = $jwt_components[2];
  global $api_secret;
  if (hash_equals(hash_hmac('sha256', $header.'.'.$payload, $api_secret),
                  $signature)) {
  }
}