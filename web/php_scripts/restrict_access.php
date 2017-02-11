<?php
  abstract class UserType {
    const MANAGER = 0;
  }
  function restrict_access($userType) {
    switch ($userType) {
      case UserType::MANAGER:
        if ($_SESSION['manager_id'] === NULL) {
          header('Location: login.php');
          exit('Page restricted to owners.');
        }
        break;
    }
  }