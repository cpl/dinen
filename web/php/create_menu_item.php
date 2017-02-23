<?php

function createMenuItem($user_email, $name, $section, $description, $price)
{
  if(empty($user_email) || empty($name) || empty($section) ||
     empty($price))
     return "Empty required fields given to create menu item";
  
}
