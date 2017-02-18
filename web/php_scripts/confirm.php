<?php
require_once 'connect_to_db.php';

if (!empty($_GET)){
  if($_GET['key']){
    echo '<h1>CONFRIMING...</h1><br/>';
    echo process_confirmation($_GET['key']);
  }
}

function create_confirmation($uid, $name, $email){
  $mysqli = createMysqlConnection();

  if(empty($uid) || empty($name) || empty($email)){
    return "Missing name|email|id";
  }

  $key = md5($name.$email);

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
    echo shell_exec('echo "Click this link to activate account: <a href=\"dinen.ddns.net/php_scripts/confirm.php?key='.$key.'\"> Activate </a> | mail -s "Dinen Confirmation" '.$email);
    // echo "<br> OUTPUT: <br/>";
    // echo "<pre>" . $output . "</pre>";

  return 'success';
}

function process_confirmation($key){

  if(strlen($key) != 32)
    return 'Key size is wrong!';

  $mysqli = createMysqlConnection();

  if ($mysqli->connect_error)
    return 'Database connection failed at confirm confirm.';

  $results = $mysqli->query("SELECT * FROM `confirm` WHERE `key` = '$key' LIMIT 1");

  if($results->num_rows === 1) {
    $arr = $results->fetch_array();
    $idd = $arr['id'];
    $uid = $arr['user_id'];
    echo $idd . " - " . $uid;
    $mysqli->query("UPDATE `users` SET `active` = 1 WHERE `id` = '$uid' LIMIT 1");
    $mysqli->query("DELETE FROM `confirm` WHERE `id` = '$idd'");
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
