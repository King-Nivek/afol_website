<?php
  // logout.php
  // Log a user out of the system utilizing the logout
  // method in UserTools.
  //
  require 'resources/includes/include.global.php';

  $userTools = new UserTools();
  $userTools->logout();

  header("Location: form_sign-in.php");

?>