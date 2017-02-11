
<?php
require_once 'validators.php';
require_once 'connect_to_db.php';
login();
function login() {
  if (!empty($_POST['email'].$_POST['password'])) {
    # Sanitize email and password (for PHP, not SQL).
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    if (!emailIsValid($email) || !passwordIsValid($password))
      return 'Server-side validation failed.<br><br>';
    $password_hash = hash('sha256', $password);
    global $mysqli;
    if ($mysqli->connect_error)
      return 'Database connection failed.<br><br>';
    $stmt = $mysqli->prepare('SELECT * FROM users WHERE email = ?
                              AND password_hash = ?');
    $stmt->bind_param('ss', $email, $password_hash);
    $stmt->execute();
    $stmt_result = $stmt->get_result();
    # If no users are found, then the credentials are incorrect.
    if ($stmt_result->num_rows <= 0)
      return 'Invalid email-password combination.<br><br>';
    $user = $stmt_result->fetch_row();
    # Store the user's info in a PHP session.
    session_start();
    $_SESSION['user_id'] = $user[0];
    $_SESSION['user_name'] = $user[1];
    $_SESSION['user_email'] = $user[2];
    $_SESSION['user_category'] = $user[4];
    header('Location: restaurants.php');
    $stmt->close();
    $mysqli->close();
    return 'Success.<br><br>';
  }
}
