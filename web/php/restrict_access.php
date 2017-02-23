<?php

function restrict_access($userType) {
  switch ($userType) {
    case UserType::MANAGER:
      if ($_SESSION['user_category'] != 'manager') {
        header('Location: login.html');
        exit('Page restricted to managers.');
      }
      break;
  }
}

function logged_in() {
  return array_key_exists('user_id', $_SESSION)
         && $_SESSION['user_id'] !== NULL;
}