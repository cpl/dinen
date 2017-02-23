<?php

require_once 'config.inc.php';
require_once 'globals.php';

const ISSUER = 'https://dinen.ddns.net/api/v1';
const AUDIENCE = 'https://dinen.ddns.net';

/* Create a JSON Web Token for post-login user authentication (expires after
   six hours). I assume users can be uniquely identified by email. Refer to
   https://tools.ietf.org/html/rfc7519 for information on JWTs. */
function createJWT($user_email, $user_name, $user_category) {
  $header = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));

  $nowInUnixTime = time();
  $sixHoursInSeconds = 6 * 60 * 60;

  $tokenID = base64_encode(random_bytes(32));

  $payload = base64_encode(json_encode([
    'iss' => ISSUER,
    'sub' => $user_email,
    'aud' => AUDIENCE,
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
  $jwt_components = explode('.', $jwt);
  $header = $jwt_components[0]; $payload = $jwt_components[1];
  $signature = $jwt_components[2];

  $payload_json = json_decode(base64_decode($payload), true);

  global $api_secret;
  # Check the JWT hasn't been tampered with or generated illegitimately.
  if (hash_equals(hash_hmac('sha256', $header.'.'.$payload, $api_secret),
                  $signature)
      && $payload_json['iss'] == ISSUER
      && $payload_json['aud'] == AUDIENCE
      && time() >= $payload_json['nbf']) {
    if (time() < $payload_json['exp'])
      return ['status' => Status::ERROR, 'data' => 'expired'];
    return ['status' => Status::SUCCESS];
  }
  return ['status' => Status::ERROR, 'data' => 'invalid'];
}