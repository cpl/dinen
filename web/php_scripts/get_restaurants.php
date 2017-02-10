<?php
require_once('connect_to_db.php');
# Returns the restaurants owned by the owner.
function getRestaurants() {
  $manager_id = $_SESSION['manager_id'];
  $query = "SELECT * FROM restaurants WHERE manager_id = '$manager_id'";
  global $mysqli;
  $query_result = $mysqli->query($query);
  if ($query_result->num_rows === 0)
    return '<div>No restaurants found.</div>';
  $restaurant_list = '<div>';
  while ($row = $query_result->fetch_array()) {
    $restaurant_list .= "<div>{$row['name']}, which is a {$row['category']}."
                        ."</div>";
  }
  $restaurant_list .= '</div>';
  $mysqli->close();
  return $restaurant_list;
}