<?php

function createMysqlConnection()
{
  require_once 'config.inc.php';
  $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
  return $mysqli;
}
