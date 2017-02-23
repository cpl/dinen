<?php

require 'config.inc.php';

function createMySQLi() {
  global $db_host; global $db_user; global $db_pass; global $db_name;
  $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
  return $mysqli;
}
