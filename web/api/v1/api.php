<?php
if ($_GET['request'] == 'killme')
  header('location: http://uk.ask.com');
else
  echo $_GET['request'];