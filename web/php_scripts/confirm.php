<?php

if (!empty($_GET)){
  if($_GET['key']){
    echo '<h1>CONFRIMING...</h1><br/>';
    confirm_confirmation($_GET['key']);
  }
}

function create_confirmation($uid, $name, $email){

  $db_host = 'dinen.ddns.net';
  $db_user = 'teamdinen';
  $db_pass = 'dinenx3';
  $db_name = 'dinen';
  $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

  $action = array();
  $action['result'] = null;
  $text = array();

  if(empty($uid)){
    $action['result'] = 'error';
    array_push($text,'Missing UID.');
  }
  if(empty($name)){
    $action['result'] = 'error';
    array_push($text,'Missing name.');
  }
  if(empty($email)){
    $action['result'] = 'error';
    array_push($text,'Missing email.');
  }


  if($action['result'] != 'error'){
    $key = md5($name.$email);
  } else {
    return var_dump($text);
  }

  $action['text'] = $text;

  if ($mysqli->connect_error)
    return 'Database connection failed at confirm.';

  $stmt = $mysqli->prepare("INSERT INTO `confirm` (`user_id`, `key`) VALUES ($uid, '$key')");

  if ($stmt === FALSE) {
      die($mysqli->error);
  }
  $stmt->execute();

  if ($stmt->errno != 0)
    return 'Failed to create confirmation entry.';

    // $output = shell_exec('./email.sh '.$email.' '.$key);
    echo shell_exec('echo "Click this link to activate account: dinen.ddns.net/php_scripts/confirm.php?key="'.$key.' | mail -s "Dinen Confirmation" '.$email);
    // echo "<br> OUTPUT: <br/>";
    // echo "<pre>" . $output . "</pre>";

  return 'success';
}

function confirm_confirmation($key){

  if(strlen($key) != 32)
    return 'Key size is wrong!';

  $db_host = 'dinen.ddns.net';
  $db_user = 'teamdinen';
  $db_pass = 'dinenx3';
  $db_name = 'dinen';

  $mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

  if ($mysqli->connect_error)
    return 'Database connection failed at confirm confirm.';

  $results = $mysqli->query("SELECT * FROM `confirm` WHERE `key` = '$key' LIMIT 1");

  if($results->num_rows === 1) {
    $arr = $results->fetch_array();
    $idd = $arr['id'];
    $uid = $arr['user_id'];
    echo $idd . " - " . $uid;
    $mysqli->query("UPDATE `users` SET `active` = 1 WHERE `id` = '$uid' LIMIT 1");
    $mysqli->query("DELETE FROM `confirm` WHERE `id` = '$idd' LIMIT 1");
    echo "OK";
    if(file_exists('./email.sh'))
      echo "email.sh";
    else
      echo "no email.sh";
    return 'success';
  }
  echo "FAILED";
  return 'fail';
}

?>
