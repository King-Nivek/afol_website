<?php
  //include.global.php

  //start the session
  date_default_timezone_set('America/Virgin');
  session_name("afol");
  session_start();

  require_once 'resources/classes/class.DB.php';
  require_once 'resources/classes/class.User.php';
  require_once 'resources/classes/class.UserTools.php';
  //require_once 'includes/include.navbar.php';
  //require_once 'includes/include.utils.php';


  //connect to the database
  $db = new DB();
// print_r($db);
  //initialize UserTools object
  $userTools = new UserTools();

  //refresh session variables if logged in
  if(isset($_SESSION['logged_in'])) {
    $user = unserialize($_SESSION['user']);
    $_SESSION['user'] = serialize($userTools->get($user->id));
  }
?>