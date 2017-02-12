<?php 
// Allow-Origin is only for tests:
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$NAME = $_REQUEST['name'];
$PASSWORD = $_REQUEST['password'];
$EMAIL = $_REQUEST['email'];
$msg = doRegistration();

if ($msg == 'OK'){
    echo json_encode(array('result' => 'OK', 'message' => 'successful registration for '.$NAME));
} else {
    echo json_encode(array('result' => 'ERROR', 'message' => $msg));
}

function doRegistration(){
  global $NAME, $PASSWORD, $EMAIL;   
  if(strlen($PASSWORD) < 8){
      return "Password is less than 8 chars!";
  }  
  if(strlen($PASSWORD) > 250) {
      return "Password is too BIG!";
  }
  if (!preg_match("#[0-9]+#", $PASSWORD)) {
      return "Password must include at least one number!";
  }
  if (!preg_match("#[a-zA-Z]+#", $PASSWORD)) {
      return "Password must include at least one letter!";
  }
  //ToDo ... other validation + database sql commands
  
  return "OK";  
} // end doRegistration

?>
