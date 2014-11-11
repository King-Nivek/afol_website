<?php
  //  class.ValidationTools.php

  class ValidationTools {

    public function isEmail($email) {
      return (filter_var($email, FILTER_VALIDATE_EMAIL));
    }

    public function isEmail($email) {
      return (filter_var($email, FILTER_VALIDATE_EMAIL));
    }
  }

?>