<?php
$alpha_char_regex = '[^\d_\W]';
$alpha_or_hyphen_char_regex = '('.$alpha_char_regex.'|-)';
$name_regex = $alpha_char_regex.$alpha_or_hyphen_char_regex.'*';
$full_name_regex = $name_regex.'\s'.$name_regex;
$password_regex = '(.*\d+.*'.$alpha_char_regex.'+.*)'
                  .'|(.*'.$alpha_char_regex.'+.*\d+.*)';


function nameIsValid($name) {
  global $full_name_regex;
  return preg_match('/'.$full_name_regex.'/', $name);
}
function emailIsValid($email) {
  return filter_var($email, FILTER_VALIDATE_EMAIL);
}
function passwordIsValid($password) {
  global $password_regex;
  if(strlen($password) < 8 || strlen($password) > 250
    || !preg_match('/'.$password_regex.'/', $password))
    return false;
  return true;
}
function passwordsAreValid($password, $c_password) {
  if(!passwordIsValid($password) || $c_password != $password)
    return false;
  return true;
}

function isValid($str) {
    return !preg_match('/[^ A-Za-z0-9.#!\'-]/', $str);
}

function arrayIsInt($array)
{
  return ctype_digit(implode('', $array));
}
