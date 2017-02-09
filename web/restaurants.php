<?php
$restaurants_html = "";
$message = "";
CheckRestaurants();
// functions which checks which restaurants owner owns
// if he is not go to login page
function CheckRestaurants()
{
  global $message, $restaurants_html;
  session_start();
  // TODO: Change html generation
  require_once('configuration.php');
  // if no session, go to login site
  if($_SESSION['manager_id'] === NULL)
    header("Location: login.php");
  $manager_id = $_SESSION['manager_id'];
  $query = "SELECT * FROM restaurants WHERE manager_id = $manager_id";
  $mysql_connection = new mysqli($db_host, $db_user, $db_pass, $db_name);
  // check connection
  if ($mysql_connection->connect_error) {
    $message = "Connection failed";
    return;
  }
  $query_result = $mysql_connection->query($query);
  if($query_result->num_rows === 0)
  {
    $restaurants_html = "<div>Sorry, no restaurants found</div>";
    return;
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
}
?>

<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Dinen Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </head>
  <body>
  <!-- Menu -->
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
        <!-- Optional TODO: Add dinen icon -->
        <a class="navbar-brand" href="#">Dinen</a>
      </div>
      <ul class="nav navbar-nav">
        <li><a href="index.html">Home</a></li>
        <li><a href="#">Business</a></li>
        <li><a href="#">Customers</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
          <li><a href ="#">Logout</a></li>
      </ul>
    </div>
  </nav>
  <!-- Index page body -->
  <div class = "container">
    <?php
    $name =  $_SESSION['name'];
    echo "Hello, $name. Restaurant list:"?>
    <?php
    global $message, $restaurants_html;
    echo $restaurants_html; echo $message;?>
  </div>
  </body>
</html>
