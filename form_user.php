<?php
require 'resources/includes/include.global.php';
require_once 'resources/classes/class.FormField.php';
require_once 'resources/includes/include.htmlFormButtons.php';
require_once 'resources/php/validationTools.php';
require_once 'resources/php/inputValid.php';

  //check to see if they're logged in
  if(!isset($_SESSION['logged_in'])) {
    header("Location: form_sign-in.php");
  }
  
  $uTool = new UserTools();
  
  //get the user object from the session
  $userID = $_SESSION["userID"];
  if ($userID == "") {
    echo "Lost userID SESSION variable...<br>";
    $uTool->logout();
    header("Location: form_sign-in.php");
  }
  $user = $uTool->get($userID);
  $toID = "";
  $toUser = null;

  $field_userID = new FormField();
  $field_userID->table = 'User';
  $field_userID->field = 'user_id';

  $field_username = new FormField();
  $field_username->table = 'User';
  $field_username->field = 'user_username';
  $field_username->label_text = 'Username';
  $field_username->id = 'username';
  $field_username->name = 'username';
  $field_username->placeholder = 'CoolDude89';
  $field_username->maxlength = 20;
  $field_username->tooltip_text = "Please enter the User's Username.";

  $field_userFirstName = new FormField();
  $field_userFirstName->table = 'User';
  $field_userFirstName->field = 'user_firstName';
  $field_userFirstName->label_text = 'First Name';
  $field_userFirstName->id = 'firstName';
  $field_userFirstName->name = 'firstName';
  $field_userFirstName->placeholder = 'John';
  $field_userFirstName->maxlength = 25;
  $field_userFirstName->tooltip_text = "Please enter the User's First Name.";

  $field_userLastName = new FormField();
  $field_userLastName->table = 'User';
  $field_userLastName->field = 'user_lastName';
  $field_userLastName->label_text = 'Last Name';
  $field_userLastName->id = 'lastName';
  $field_userLastName->name = 'lastName';
  $field_userLastName->placeholder = 'Doe';
  $field_userLastName->maxlength = 30;
  $field_userLastName->tooltip_text = "Please enter the User's Last Name.";
  
  $field_userEmail = new FormField();
  $field_userEmail->table = 'User';
  $field_userEmail->field = 'user_email';
  $field_userEmail->label_text = 'E-Mail';
  $field_userEmail->id = 'email';
  $field_userEmail->name = 'email';
  $field_userEmail->placeholder = 'john@something.com';
  $field_userEmail->maxlength = 50;
  $field_userEmail->tooltip_text = "Please enter the User's E-mail address.";
  
  $field_userJoinDate = new FormField();
  $field_userJoinDate->table = 'User';
  $field_userJoinDate->field = 'user_joinDate';
  $field_userJoinDate->label_text = 'Join Date';
  $field_userJoinDate->id = 'joinDate';
  $field_userJoinDate->name = 'joinDate';
  $field_userJoinDate->placeholder = '2014-11-12 19:58:04';
  $field_userJoinDate->maxlength = 19;
  $field_userJoinDate->tooltip_text = "Please enter the User's Join Date.";
  
  $field_userPrivilege = new FormField();
  $field_userPrivilege->table = 'User';
  $field_userPrivilege->field = 'user_privilege';
  $field_userPrivilege->label_text = 'User Privilege';
  $field_userPrivilege->id = 'privilege';
  $field_userPrivilege->name = 'privilege';
  $field_userPrivilege->placeholder = 'U';
  $field_userPrivilege->maxlength = 1;
  $field_userPrivilege->tooltip_text = "Please enter the User's Privilege rights.";
  
  $formButtons = '';
  $submitType = 'false';
  $validInput = 0;
  $originalKeys;

  $user = array();
  $user[$field_userID->field] = &$field_userID;
  $user[$field_username->field] = &$field_username;
  $user[$field_userEmail->field] = &$field_userEmail;

  $keys = array();
  $keys[] = &$user;
  

  if($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_SESSION['originalKeys'])) {
      $originalKeys = unserialize($_SESSION['originalKeys']);
      $field_userID->value = $originalKeys[0]['value'];
    }

    if(isset($_POST['submitType'])) {
      $submitType = makeSafer($_POST['submitType']);
      if(!($submitType === 'modify' || $submitType === 'delete' || $submitType === 'addNew')) {
        $submitType = 'false';
      }
    }

    if(isset($submitType) && $submitType === 'addNew') {
      $formButtons = formAddRecord();
    } else {
      $formButtons = formModifyDeleteRecord();
    }

    $input = array();

    if(isset($_POST[$field_username->id])) {                         //  Check if $_POST['username'] is set.
      if(!empty($_POST[$field_username->id])) {                      //  Check if it is empty.
        $input[$field_username->id] = makeSafer($_POST[$field_username->id]); //  Trim, strip slashes, and certain character into their entity form.
        $field_username->value = $input[$field_username->id];        //  Set the fields value.

        if(isUserName($input[$field_username->id])) {  //  Check that the input matches allowed characters and or character types.
          $field_username->has_success();         //  Shows check mark if valid.
          $validInput++;
        } else {
          $field_username->error = 'Bad Value'; //  Error String is set.
          $field_username->has_error();         //  Shows and 'X' that it is not valid.
        }
      } else {
        $field_username->has_required(); //  Shows an asterisk if the field was required and submitted empty.
      }
    }

    if(isset($_POST[$field_userFirstName->id])) {                          //  Check if $_POST['firstName'] is set.
        if(!empty($_POST[$field_userFirstName->id])) {                     //  Check if it is empty.
        $input[$field_userFirstName->id] = makeSafer($_POST[$field_userFirstName->id]); //  Trim, strip slashes, and make certain character into their entity form.
        $field_userFirstName->value = $input[$field_userFirstName->id];    //  Set the fields value.

        if(isColorName($input[$field_userFirstName->id])) {     //  Check that the input matches allowed characters and or character types.
          $field_userFirstName->has_success();  //  Shows check mark if valid.
          $validInput++;
        } else {
          $field_userFirstName->error = 'Bad Value'; //  Error String is set.
          $field_userFirstName->has_error();         //  Shows and 'X' that it is not valid.
        }
      } else {
        $field_userFirstName->has_required(); //  Shows an asterisk if the field was required and submitted empty.
      }
    }

    if(isset($_POST[$field_userLastName->id])) {                         //  Check if $_POST['lastName'] is set.
      if(!empty($_POST[$field_userLastName->id])) {                      //  Check if it is empty.
        $input[$field_userLastName->id] = makeSafer($_POST[$field_userLastName->id]); //  Trim, strip slashes, and certain character into their entity form.
        $field_userLastName->value = $input[$field_userLastName->id];    //  Set the fields value.

        if(isColorName($input[$field_userLastName->id])) {  //  Check that the input matches allowed characters and or character types.
          $field_userLastName->has_success();        //  Shows check mark if valid.
          $validInput++;
        } else {
          $field_userLastName->error = 'Bad Value';  //  Error String is set.
          $field_userLastName->has_error();          //  Shows and 'X' that it is not valid.
        }
      } else { 
        $field_userLastName->has_required();  //  Shows an asterisk if the field was required and submitted empty.
      }
    }

    if(isset($_POST[$field_userEmail->id])) {                      //  Check if $_POST['email'] is set.
      if(!empty($_POST[$field_userEmail->id])) {                   //  Check if it is empty.
        $input[$field_userEmail->id] = makeSafer($_POST[$field_userEmail->id]); //  Trim, strip slashes, and certain character into their entity form.
        $field_userEmail->value = $input[$field_userEmail->id]; //  Set the fields value.

        if(isEmail($input[$field_userEmail->id])) {  //  Check that the input matches allowed characters and or character types.
          $field_userEmail->has_success();     //  Shows check mark if valid.
          $validInput++;
        } else {
          $field_userEmail->error = 'Bad Value';  //  Error String is set.
          $field_userEmail->has_error();          //  Shows and 'X' that it is not valid.
        }
      } else { 
        $field_userEmail->has_required();  //  Shows an asterisk if the field was required and submitted empty.
      }
    }

    if(isset($_POST[$field_userJoinDate->id])) {                         //  Check if $_POST['joinDate'] is set.
      if(!empty($_POST[$field_userJoinDate->id])) {                      //  Check if it is empty.
        $input[$field_userJoinDate->id] = makeSafer($_POST[$field_userJoinDate->id]); //  Trim, strip slashes, and certain character into their entity form.
        $field_userJoinDate->value = $input[$field_userJoinDate->id];    //  Set the fields value.

        if(isDateTime($input[$field_userJoinDate->id])) {  //  Check that the input matches allowed characters and or character types.
          $field_userJoinDate->has_success();        //  Shows check mark if valid.
          $validInput++;
        } else {
          $field_userJoinDate->error = 'Bad Value';  //  Error String is set.
          $field_userJoinDate->has_error();          //  Shows and 'X' that it is not valid.
        }
      } else { 
        $field_userJoinDate->has_required();  //  Shows an asterisk if the field was required and submitted empty.
      }
    }

    if(isset($_POST[$field_userPrivilege->id])) {                          //  Check if $_POST['privilege'] is set.
      if(!empty($_POST[$field_userPrivilege->id])) {                       //  Check if it is empty.
        $input[$field_userPrivilege->id] = makeSafer($_POST[$field_userPrivilege->id]); //  Trim, strip slashes, and certain character into their entity form.
        $field_userPrivilege->value = $input[$field_userPrivilege->id];     //  Set the fields value.

        if(isUserPrivilege($input[$field_userPrivilege->id])) {  //  Check that the input matches allowed characters and or character types.
          $field_userPrivilege->has_success();         //  Shows check mark if valid.
          $validInput++;
        } else {
          $field_userPrivilege->error = 'Bad Value';  //  Error String is set.
          $field_userPrivilege->has_error();          //  Shows and 'X' that it is not valid.
        }
      } else { 
        $field_userPrivilege->has_required();  //  Shows an asterisk if the field was required and submitted empty.
      }
    }

    $data = array_merge($field_username->get_column_Value(),
                        $field_userFirstName->get_column_Value(),
                        $field_userLastName->get_column_Value(),
                        $field_userEmail->get_column_Value(),
                        $field_userJoinDate->get_column_Value(),
                        $field_userPrivilege->get_column_Value());

    if($submitType === 'delete') {
      whatToDo($submitType, $keys, $data, $originalKeys);
    }

    if($validInput === 6 && $submitType !== 'delete') {
      whatToDo($submitType, $keys, $data, $originalKeys);
    }

    if(isset($_POST['keys']) && !empty($_POST['keys'])) {
      $posted_keys = $_POST['keys'];
      $posted_keys = substr_replace($posted_keys,"}",-2);
      $posted_keys = str_replace("'",'"',$posted_keys);
      $posted_keys = json_decode($posted_keys);

      $results = $db->select("User","*","user_id='$posted_keys->user_id'");

      $originalKeys = array();
      $originalKeys[] = array('field' => 'user_id',
                              'value' => $results['user_id']);

      $_SESSION['originalKeys'] = serialize($originalKeys);

      $field_userID->value = $results['user_id'];
      $field_username->value = $results['user_username'];
      $field_userFirstName->value = $results['user_firstName'];
      $field_userLastName->value = $results['user_lastName'];
      $field_userEmail->value = $results['user_email'];
      $field_userJoinDate->value = $results['user_joinDate'];
      $field_userPrivilege->value = $results['user_privilege'];
    }

  } else {
      $formButtons = formAddRecord();
  }

  echo html_header("User form"),
       html_containerStart() ;
?>
              <form id="theForm" class="form-horizontal" role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                
                <div class="panel panel-primary">

                  <div class="panel-heading">
                    <h3 class="panel-title">Form: Users</h3>
                  </div>

                  <div class="panel-body">
                    <?php 
                      echo $field_username->toString(),"\n",
                           $field_userFirstName->toString(),"\n",
                           $field_userLastName->toString(),"\n",
                           $field_userEmail->toString(),"\n",
                           $field_userJoinDate->toString(),"\n",
                           $field_userPrivilege->toString(),"\n",
                           $formButtons,"\n";
                    ?>
                    
                  </div>
                </div><!-- End panel  -->
              </form>
<?php echo html_column1End(),
           html_menuStart();
?>              
                <!-- Page Label -->
                <div class="h3" style="margin-top: 10px;">
                  <span class="label label-default center-block">Forms</span>
                </div>
                
                <div  style="padding-top: 10px;"></div>
                
                <!-- Return to Forms page -->
                <div class="btn-group">
                  <a class="btn btn-default" href="forms.php" role="button">Forms Home</a>
                </div>

<?php 
  echo html_Ending(),"\n",
       formDeleteScripts();
?>
    <script>
      $(function () { 
        $("[data-toggle='tooltip']").tooltip(); 
      });
    </script>
  </body>
</html>