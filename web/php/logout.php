<?php

# Courtesy of http://php.net/session_destroy

# Unset all of the session variables.
$_SESSION = array();

# Also delete the session cookie if it exists.
if (ini_get("session.use_cookies")) {
  $params = session_get_cookie_params();
  setcookie(session_name(), '', time() - 42000,
    $params["path"], $params["domain"],
    $params["secure"], $params["httponly"]
  );
}

# Finally, destroy the session.
session_destroy();

header('Location: ../index.html');