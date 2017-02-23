<?php

require_once 'connect_to_db.php';

if (!empty($_GET)){
  if($_GET['key']){
    echo '<h1>CONFRIMING...</h1><br/>';
    echo process_confirmation($_GET['key']);
    header("Location: ../index.html");
  }
}

function create_confirmation($uid, $name, $email){
  $mysqli = createMySQLi();

  if(empty($uid) || empty($name) || empty($email)){
    return "Missing name|email|id";
  }

  $key = md5($name.$email);

  if ($mysqli->connect_error)
  {
    $mysqli->close();
    return 'Database connection failed at confirm.';
  }

  $stmt = $mysqli->prepare("INSERT INTO `confirm` (`user_id`, `key`) VALUES (?, ?)");
  $stmt->bind_param('ss', $uid, $key);
  if ($stmt === FALSE) {
      die($mysqli->error);
  }
  $stmt->execute();

  if ($stmt->errno != 0)
  {
    $mysqli->close();
    return 'Failed to create confirmation entry.';
  }

  echo shell_exec('echo "Click this link to activate account: https://dinen.ddns.net/php/confirm.php?key='.$key.'" | mail -s "Dinen Confirmation" ' . $email);

  $mysqli->close();
  return 'success';
}

function process_confirmation($key){

  if(strlen($key) != 32)
    return 'Key size is wrong!';

  $mysqli = createMySQLi();

  if ($mysqli->connect_error)
    return 'Database connection failed at confirm confirm.';

  $stmt = $mysqli->prepare("SELECT * FROM `confirm` WHERE `key` = ? LIMIT 1");
  $stmt->bind_param('s', $key);
  $stmt->execute();
  $results = $stmt->get_result();

  if($results->num_rows === 1) {
    $arr = $results->fetch_array();
    $idd = $arr['id'];
    $uid = $arr['user_id'];
    $mysqli->query("UPDATE `users` SET `active` = 1 WHERE `id` = '$uid' LIMIT 1");
    $mysqli->query("DELETE FROM `confirm` WHERE `id` = '$idd'");
    $mysqli->close();
    return 'success';
  }
  $mysqli->close();
  return 'fail';
}
