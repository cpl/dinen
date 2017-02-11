<?php
  abstract class UserType {
    const MANAGER = 0;
  }
  function restrict_access($userType) {
    switch ($userType) {
      case UserType::MANAGER:
        if ($_SESSION['user_category'] != 'manager') {
          header('Location: login.php');
          exit('Page restricted to managers.');
        }
        break;
    }
  }
  function logged_in() {
    return $_SESSION['user_id'] !== NULL;
  }