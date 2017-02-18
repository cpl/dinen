<?php
require_once 'config.inc.php';
function createMysqlConnection()
{
  $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
  return $mysqli;
}
