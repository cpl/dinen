<!DOCTYPE html>
<html>
  <head>
    <?php require_once 'common/head.php'; ?>
    <title>Dinen Registration</title>
  </head>
  <body>
  <?php require_once 'common/navbar.php'; ?>
  <div class = "container">

    <form id='register' action='register.php' method='post'>
      <hr /><label><b>Name</b></label>
      <input type="text" placeholder="Enter Name" name="name" required>

      <hr /><label><b>Email</b></label>
      <input type="text" placeholder="Enter Email" name="email" required>

      <hr /><label><b>Password</b></label>
      <input type="password" placeholder="Enter Password" name="password" required>

      <hr /><label><b>Confirm Password</b></label>
      <input type="password" placeholder="Enter Password" name="c_password" required>

      <hr/><button type="submit">Register</button>
    </form>
  </div>
  <?php require_once 'php_scripts/register.php'; echo register(); ?>
</body>
</html>