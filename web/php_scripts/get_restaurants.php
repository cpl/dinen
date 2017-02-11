<?php
require_once('connect_to_db.php');
# Returns the restaurants owned by the owner.
function getRestaurants() {
  $manager_id = $_SESSION['manager_id'];
  global $mysqli;
  if ($mysqli->connect_error)
    return 'Database connection failed.';
  $stmt = $mysqli->prepare('SELECT * FROM restaurants WHERE manager_id = ?');
  $stmt->bind_param('i', $manager_id); $stmt->execute();
  $stmt_result = $stmt->get_result();
  if ($stmt_result->num_rows === 0)
    return '<div>No restaurants found.</div>';
  $restaurant_list = '<div>';
  while ($row = $stmt_result->fetch_array()) {
    $restaurant_list .= "<div>{$row['name']}, which is a {$row['category']}."
                        ."</div>";
  }
  $restaurant_list .= '</div>';
  $stmt->close(); $mysqli->close();
  return $restaurant_list;
}