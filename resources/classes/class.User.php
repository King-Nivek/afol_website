<?php

  require_once 'class.DB.php';

  //
  //
  //
  class User {

    ////  Properties
    ///////////////////////////////////
    public $id;
    public $userName;
    public $privilage;
    public $hashpass;
    public $email;
    public $joinDate;
    public $firstName;
    public $lastName;

    ////  Constructor
    ///////////////////////////////////

    //  name Function
    //
    //  Use:  
    //  Parameter(s):  
    //  Returns:  
    //
    function __construct($data) {
      $this->id        =              (isset($data['user_id']))        ? $data['user_id']        : "" ;
      $this->username  = stripslashes((isset($data['user_username']))  ? $data['user_username']  : "");
      $this->privilage =              (isset($data['user_privilege'])) ? $data['user_privilege'] : "" ;
      $this->hashpass  =              (isset($data['user_password']))  ? $data['user_password']  : "" ;
      $this->email     = stripslashes((isset($data['user_email']))     ? $data['user_email']     : "");
      $this->joinDate  =              (isset($data['user_joinDate']))  ? $data['user_joinDate']  : "" ;
      $this->firstName = stripslashes((isset($data['user_firstName'])) ? $data['user_firstName'] : "");
      $this->lastName  = stripslashes((isset($data['user_lastName']))  ? $data['user_lastName']  : "");
    }//  End __construct  =======================================================

    //  name Function
    //
    //  Use:  
    //  Parameter(s):  
    //  Returns:  
    //
    public function save($isNewUser = false) {
      //  Create a new database object.
      $db = new DB();
      $username  = $db->mysqli->real_escape_string($this->username  );
      $email     = $db->mysqli->real_escape_string($this->email     );
      $firstName = $db->mysqli->real_escape_string($this->firstName );
      $lastName  = $db->mysqli->real_escape_string($this->lastName  );

      //  If just modifying a current user.
      if(!$isNewUser) {
        //  set the data array
        $data = array("user_username"  => "$username",
                      "user_password"  => "$this->hashpass",
                      "user_email"     => "$email",
                      "user_firstName" => "$firstName",
                      "user_lastName"  => "$lastName"
                      );
        //  Modify the user in the database
        $db->modify('User', $data, "'id = $this->id'");

      } else {
        //  If this is a new user being registered.
        $data = array("user_id"  => "NULL",
                      "user_username"  => "$username",
                      "user_password"  => "$this->hashpass",
                      "user_email"     => "$email",
                      "user_firstName" => "$firstName",
                      "user_lastName"  => "$lastName",
                      "user_joinDate"  => "".date("Y-m-d H:i:s")
                      );

        $this->id = $db->insert('User', $data);
        $this->joinDate = time();
      }
      return true;
    }//  End save  =============================================================
  }
?>