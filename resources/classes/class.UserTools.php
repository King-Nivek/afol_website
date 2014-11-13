<?php
  require_once 'class.User.php';
  require_once 'class.DB.php';
  require_once 'libraries/password_compat/password.php';
  
  class UserTools {
    
    //  name Function
    //
    //  Use:  
    //  Parameter(s):  
    //  Returns:  
    //
    public function login($email, $password) {
      global $db;

      $result = $db->mysqli->query("SELECT * 
                                      FROM User
                                      WHERE user_email = '$email'");

      if($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if(password_verify($password, $row['user_password'])) {
          $loggedUser = new User($row);
          $_SESSION["user"] = serialize($loggedUser);
          $_SESSION["userID"] = $loggedUser->id;
          $_SESSION["login_time"] = time();
          $_SESSION["logged_in"] = 1;
          return true;
        } else {
          return false;
        }
      } else {
        return false;
      }
    }//  End login  ============================================================

    //  name Function
    //
    //  Use:  
    //  Parameter(s):  
    //  Returns:  
    //
    public function logout() {
      unset($_SESSION["user"]);
      unset($_SESSION["userID"]);
      unset($_SESSION["login_time"]);
      unset($_SESSION["logged_in"]);
      session_destroy();
    }//  End logout  ===========================================================

    //  name Function
    //
    //  Use:  
    //  Parameter(s):  
    //  Returns:  
    //
    public function check_userNameExistence($userName) {
      global $db;
      $result = $db->mysqli->query("SELECT user_id FROM User WHERE user_userName = '$userName'");
      if($result->num_rows == 0) {
        return false;
      } else {
        return true;
      }
    }//  End check_userNameExistence  ==========================================

    //  name Function
    //
    //  Use:  
    //  Parameter(s):  
    //  Returns:  
    //
    public function check_emailExistence($email) {
      global $db;
      $result = $db->mysqli->query("SELECT user_id FROM User WHERE user_email = '$email'");
      if($result->num_rows == 0) {
        return false;
      } else {
        return true;
      }
    }//  End check_emailExistence  =============================================

    //  name Function
    //
    //  Use:  
    //  Parameter(s):  
    //  Returns:  
    //
    public function get($id) {
      $db = new DB();
      $result = $db->select('User', "*", "user_id = $id");
      return new User($result);
    }//  End get  ==============================================================
  }
?>