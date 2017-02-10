<?php
// checks which restaurants owner owns
// if he is not go to login page
require_once("configuration.php");
function getRestaurants() {
  // if no session, go to login site
  if($_SESSION['manager_id'] === NULL)
    header("Location: ../login.php");
  $manager_id = $_SESSION['manager_id'];
  $query = "SELECT * FROM restaurants WHERE manager_id = $manager_id";
  global $db_host, $db_name, $db_pass, $db_user;
  $mysql_connection = new mysqli($db_host, $db_user, $db_pass, $db_name);
  // check connection
  if ($mysql_connection->connect_error) {
    return 'Connection failed';
  }
  $query_result = $mysql_connection->query($query);
  if($query_result->num_rows === 0)
  {
    return '<div>Sorry, no restaurants found</div>';
  }
  $rows = [];
  while($row = $query_result->fetch_array())
  {
    array_push($rows, $row);
  }
  $restaurants_html = "<div>";
  foreach($rows as $row)
  {
    $name = $row['name'];
    $category = $row['category'];
    $restaurants_html .= "<div>You have a restaurant called $name";
    $restaurants_html .= "Which is $category</div>";
  }
  $restaurants_html .= "</div>";
  return $restaurants_html;
}