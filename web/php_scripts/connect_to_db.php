<?php

function createMysqlConnection()
{
  require_once 'config.inc.php';
  global $db_host, $db_user, $db_pass, $db_name;
  $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
  return $mysqli;
}
