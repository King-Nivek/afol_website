<?php 
  // register.php
  // Register a user in the system to allow them
  // to login.
  //
require_once 'resources/includes/include.global.php';
require_once 'libraries/password_compat/password.php';
require_once 'resources/classes/class.FormField.php';
require_once 'resources/includes/include.htmlFormButtons.php';
require_once 'resources/php/validationTools.php';

## NEW
/*  
  //check to see if they're logged in
  if(!isset($_SESSION['logged_in'])) {
    header("Location: form_sign-in.php");
  }
*/  
  $uTool = new UserTools();
/*  
  //get the user object from the session
  $userID = $_SESSION["userID"];
  if ($userID == "") {
    echo "Lost userID SESSION variable...<br>";
    $uTool->logout();
    header("Location: form_sign-in.php");
  }
*/
/*  $user = $uTool->get($userID);
  $toID = "";
  $toUser = null;*/

  $field_userID = new FormField();
  $field_userID->table = 'User';
  $field_userID->field = 'user_id';
  $field_userID->value = NULL;

  $field_userFirstName = new FormField();
  $field_userFirstName->table = $field_userID->table;
  $field_userFirstName->field = 'user_firstName';
  $field_userFirstName->label_text = 'First Name';
  $field_userFirstName->id = 'u_firstName';
  $field_userFirstName->name = 'u_firstName';
  $field_userFirstName->placeholder = 'John';
  $field_userFirstName->maxlength = 25;
  $field_userFirstName->tooltip_text = "Please enter the User's First Name.";

  $field_userLastName = new FormField();
  $field_userLastName->table = $field_userID->table;
  $field_userLastName->field = 'user_lastName';
  $field_userLastName->label_text = 'Last Name';
  $field_userLastName->id = 'u_lastName';
  $field_userLastName->name = 'u_lastName';
  $field_userLastName->placeholder = 'Doe';
  $field_userLastName->maxlength = 30;
  $field_userLastName->tooltip_text = "Please enter the User's Last Name.";

  $field_username = new FormField();
  $field_username->table = $field_userID->table;
  $field_username->field = 'user_username';
  $field_username->label_text = 'Username';
  $field_username->id = 'u_username';
  $field_username->name = 'u_username';
  $field_username->placeholder = 'CoolDude89';
  $field_username->maxlength = 20;
  $field_username->tooltip_text = "Please enter the User's Username.";

  $field_usernameConfirm = new FormField();
  $field_usernameConfirm->table = $field_userID->table;
  $field_usernameConfirm->field = 'user_username';
  $field_usernameConfirm->label_text = 'Confirm '.$field_username->label_text;
  $field_usernameConfirm->id = $field_username->id.'Confirm';
  $field_usernameConfirm->name = $field_username->name.'Confirm';
  $field_usernameConfirm->placeholder = $field_username->placeholder;
  $field_usernameConfirm->maxlength = $field_username->maxlength;
  $field_usernameConfirm->tooltip_text = "Please Confirm your entered Username.";
  
  $field_userEmail = new FormField();
  $field_userEmail->table = $field_userID->table;
  $field_userEmail->field = 'user_email';
  $field_userEmail->label_text = 'E-Mail';
  $field_userEmail->id = 'u_email';
  $field_userEmail->name = 'u_email';
  $field_userEmail->placeholder = 'john@something.com';
  $field_userEmail->maxlength = 50;
  $field_userEmail->tooltip_text = "Please enter the User's E-mail address.";
   
  $field_userEmailConfirm = new FormField();
  $field_userEmailConfirm->table = $field_userID->table;
  $field_userEmailConfirm->field = $field_userEmail->field;
  $field_userEmailConfirm->label_text = 'Confirm '.$field_userEmail->label_text;
  $field_userEmailConfirm->id = $field_userEmail->id.'Confirm';
  $field_userEmailConfirm->name = $field_userEmail->name.'Confirm';
  $field_userEmailConfirm->placeholder = $field_userEmail->placeholder;
  $field_userEmailConfirm->maxlength = $field_userEmail->maxlength;
  $field_userEmailConfirm->tooltip_text = "Please Confirm your entered E-mail address.";
  
  
  $field_userPassword = new FormField();
  $field_userPassword->table = $field_userID->table;
  $field_userPassword->field = 'user_password';
  $field_userPassword->label_text = 'Password';
  $field_userPassword->id = 'u_password';
  $field_userPassword->name = 'u_password';
  $field_userPassword->type = 'password';
  $field_userPassword->placeholder = '';
  $field_userPassword->maxlength = 30;
  $field_userPassword->tooltip_text = "Please enter your Password.";
  
  $field_userPasswordConfirm = new FormField();
  $field_userPasswordConfirm->table = $field_userID->table;
  $field_userPasswordConfirm->field = $field_userPassword->field;
  $field_userPasswordConfirm->label_text = 'Confirm '.$field_userPassword->label_text;
  $field_userPasswordConfirm->id = $field_userPassword->id.'Confirm';
  $field_userPasswordConfirm->name = $field_userPassword->name.'Confirm';
  $field_userPasswordConfirm->type = $field_userPassword->type;
  $field_userPasswordConfirm->placeholder = '';
  $field_userPasswordConfirm->maxlength = $field_userPassword->maxlength;
  $field_userPasswordConfirm->tooltip_text = "Please Confirm your entered Password.";
  
  $userTools = new UserTools();
  $formButtons = formAddRecord();
  $submitType = 'false';
  $validInput = 0;

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
      if(!($submitType === 'addNew')) {
        $submitType = 'false';
      }
    }


    $input = array();

    if(isset($_POST[$field_userFirstName->id])) {                                       //  Check if $_POST['firstName'] is set.
        if(!empty($_POST[$field_userFirstName->id])) {                                  //  Check if it is empty.
        $input[$field_userFirstName->id] = makeSafer($_POST[$field_userFirstName->id]); //  Trim, strip slashes, and make certain character into their entity form.
        $field_userFirstName->value = $input[$field_userFirstName->id];                 //  Set the fields value.

        if(isColorName($input[$field_userFirstName->id])) {  //  Check that the input matches allowed characters and or character types.
          $field_userFirstName->has_success();               //  Shows check mark if valid.
          $validInput++;
        } else {
          $field_userFirstName->error = 'Bad Value'; //  Error String is set.
          $field_userFirstName->has_error();         //  Shows and 'X' that it is not valid.
        }
      } else {
        $field_userFirstName->has_required(); //  Shows an asterisk if the field was required and submitted empty.
      }
    }

    if(isset($_POST[$field_userLastName->id])) {
      if(!empty($_POST[$field_userLastName->id])) {
        $input[$field_userLastName->id] = makeSafer($_POST[$field_userLastName->id]);
        $field_userLastName->value = $input[$field_userLastName->id];

        if(isColorName($input[$field_userLastName->id])) {
          $field_userLastName->has_success();
          $validInput++;
        } else {
          $field_userLastName->error = 'Bad Value';
          $field_userLastName->has_error();
        }
      } else { 
        $field_userLastName->has_required();  
      }
    }

    if(isset($_POST[$field_username->id])) {  ##  Is set
      if(!empty($_POST[$field_username->id])) {  ##  Is NOT empty
        $input[$field_username->id] = makeSafer($_POST[$field_username->id]);
        $field_username->value = $input[$field_username->id];

        if(isUserName($input[$field_username->id])) {  ##  Is good characters
          if($userTools->check_userNameExistence($field_username->value)) {  ##  Does exist (bad)
            $field_username->error = "That username is already taken.\n";
            $field_username->has_error();
          
          } else {  ##  Does NOT exist (good)
            $field_username->has_success();
            $validInput++;
          }

        } else {  ##  Is bad characters
          $field_username->error = 'Bad Value';
          $field_username->has_error();
        }
      } else {  ##  Is empty
        $field_username->has_required(); 
      }
    }  ##  Is NOT set

    if(isset($_POST[$field_usernameConfirm->id])) {
      if(!empty($_POST[$field_usernameConfirm->id])) {
        $input[$field_usernameConfirm->id] = makeSafer($_POST[$field_usernameConfirm->id]);
        $field_usernameConfirm->value = $input[$field_usernameConfirm->id];

        if($field_username->value === $field_usernameConfirm->value) {
          if(isUserName($input[$field_usernameConfirm->id])) {
            $field_usernameConfirm->has_success();
            $field_usernameConfirm->error = 'Confirm matches Username.';
            $validInput++;
          
          } else {
            $field_usernameConfirm->error = 'Bad Value';
            $field_usernameConfirm->has_error();
          }
        } else {
          $field_usernameConfirm->error = 'Confirm does not match Username.';
          $field_usernameConfirm->has_error();
        }
      } else {
        $field_usernameConfirm->has_required(); 
      }
    }

    if(isset($_POST[$field_userEmail->id])) {  ##  Is set
      if(!empty($_POST[$field_userEmail->id])) {  ##  Is NOT empty
        $input[$field_userEmail->id] = trim($_POST[$field_userEmail->id]);
        $field_userEmail->value = $input[$field_userEmail->id];

        if(isEmail($input[$field_userEmail->id])) {  ##  Is good characters
          if($userTools->check_emailExistence($field_userEmail->value)) {  ##  Does exist (bad)
            $field_userEmail->error = "That E-mail is already in use taken.\n";
            $field_userEmail->has_error();
          
          } else {  ##  Does NOT exist (good)
            $field_userEmail->has_success();
            $validInput++;
          }

        } else {  ##  Is bad characters
          $field_userEmail->error = 'Bad Value';
          $field_userEmail->has_error();
        }
      } else {  ##  Is empty
        $field_userEmail->has_required(); 
      }
    }  ##  Is NOT set

    if(isset($_POST[$field_userEmailConfirm->id])) {
      if(!empty($_POST[$field_userEmailConfirm->id])) {
        $input[$field_userEmailConfirm->id] = trim($_POST[$field_userEmailConfirm->id]);
        $field_userEmailConfirm->value = $input[$field_userEmailConfirm->id];

        if($field_userEmail->value === $field_userEmailConfirm->value) {
          if(isEmail($input[$field_userEmailConfirm->id])) {
            $field_userEmailConfirm->has_success();
            $field_userEmailConfirm->error = 'Confirm matches E-mail.';
            $validInput++;
          
          } else {
            $field_userEmailConfirm->error = 'Bad Value';
            $field_userEmailConfirm->has_error();
          }
        } else {
          $field_userEmailConfirm->error = 'Confirm does not match E-mail.';
          $field_userEmailConfirm->has_error();
        }
      } else {
        $field_userEmailConfirm->has_required(); 
      }
    }

    if(isset($_POST[$field_userPassword->id])) {
      if(!empty($_POST[$field_userPassword->id])) {
        $input[$field_userPassword->id] = trim($_POST[$field_userPassword->id]);
        $field_userPassword->value = $input[$field_userPassword->id];

        if(isPassword($input[$field_userPassword->id])) {
          $field_userPassword->has_success();
          $validInput++;
        } else {
          $field_userPassword->error = 'Bad Value';
          $field_userPassword->has_error();
        }
      } else { 
        $field_userPassword->has_required();  
      }
    }

    if(isset($_POST[$field_userPasswordConfirm->id])) {
      if(!empty($_POST[$field_userPasswordConfirm->id])) {
        $input[$field_userPasswordConfirm->id] = trim($_POST[$field_userPasswordConfirm->id]);
        $field_userPasswordConfirm->value = $input[$field_userPasswordConfirm->id];

        if($field_userPassword->value === $field_userPasswordConfirm->value) {
          if(isUserName($input[$field_userPasswordConfirm->id])) {
            $field_userPasswordConfirm->has_success();
            $field_userPasswordConfirm->error = 'Confirm matches Password.';
            $validInput++;
          
          } else {
            $field_userPasswordConfirm->error = 'Bad Value';
            $field_userPasswordConfirm->has_error();
          }
        } else {
          $field_userPasswordConfirm->error = 'Confirm does not match Password.';
          $field_userPasswordConfirm->has_error();
        }
      } else {
        $field_userPasswordConfirm->has_required(); 
      }
    }

    if($validInput === 8 && $submitType === 'addNew') {
      //prep the data for saving in a new user object
      $data = array_merge($field_userID->get_column_Value(),
                          $field_userFirstName->get_column_Value(),
                          $field_userLastName->get_column_Value(),
                          $field_username->get_column_Value(),
                          $field_userEmail->get_column_Value(),
                          array($field_userPassword->field => password_hash($field_userPassword->value, PASSWORD_BCRYPT))); //encrypt the password for storage
      
      //create the new user object
      $newUser = new User($data);
      
      //save the new user to the database
      $newUser->save(true);
      
      //log them in
      $userTools->login($email, $password);
      
      //redirect them to the main page
      header("Location: index.php");
    }

  }
  
  //If the form wasn't submitted, or didn't validate
  //then we show the registration form again
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>

    <!-- Bootstrap -->
    <link href="libraries/custom/custom-bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="resources/css/offcanvas.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container">
      <div class="row row-offcanvas row-offcanvas-right">
        <div class="col-xs-12 col-sm-12">
          
          <div class="col-xs-12 col-sm-9 "><!--  column 1  -->

            <p class="pull-right visible-xs">
              <button type="button" class="btn btn-warning btn-xs" data-toggle="offcanvas">Toggle nav</button>
            </p>
                
            <!-- Page Label -->
            <!-- FORM
            ================================================================ -->
            <form id="form_register" action="form_register.php" method="POST" class="form-horizontal" role="form">                      <!-- form -->
              
              <div class="panel panel-primary">                             <!-- Register Panel -->

                <div class="panel-heading">
                  <h3 class="panel-title">Register:</h3>
                </div>

                <div class="panel-body">                                    <!-- Register Body -->

                  <div class="panel panel-info">
                    
                    <div class="panel-heading">                             <!-- Name Panel -->
                      <h3 class="panel-title">Name:</h3>
                    </div>
                    
                    <div class="panel-body">                                <!-- Name Body -->
                      <?php
                        echo $field_userFirstName->toString(),"\n",
                             $field_userLastName->toString(),"\n";
                      ?>

                    </div><!-- / Name Body -->
                  </div><!-- / Name Panel -->
                  
                  <div class="panel panel-info">                          <!-- User Info Panel -->
                    
                    <div class="panel-heading">
                      <h3 class="panel-title">User Info and Conformation:</h3>
                    </div>
                    
                    <div class="panel-body">                                <!-- User Info Body -->
                      <?php
                        echo $field_username->toString(),"\n",
                             $field_usernameConfirm->toString(),"\n",
                             $field_userEmail->toString(),"\n",
                             $field_userEmailConfirm->toString(),"\n",
                             $field_userPassword->toString(),"\n",
                             $field_userPasswordConfirm->toString(),"\n";
                      ?>

                    </div><!-- / User Info Body -->
                  </div><!-- / User Info Panel -->

                  <?php echo $formButtons,"\n"; ?>
                </div><!-- / Register Body -->
              </div><!-- / Register Panel -->
            </form>

          </div><!--============================================================ End column 1  -->
        
          <div class="col-xs-0 col-sm-3 sidebar-offcanvas" id="sidebar"><!--  column 2  -->
            <div class="well">
              <div class="btn-groupp"><!--  sidebar menu items  -->
                
                <!-- Main Menu -->
                <div class="btn-group">
                  <button type="button" class="btn btn-default btn-block dropdown-toggle" data-toggle="dropdown">
                    Menu <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-right" role="menu">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="forms.php">Forms</a></li>
                    <li><a href="reports.php">Reports</a></li>
                    <li class="divider"></li>
                    <li><a href="form_settings.php">Settings</a></li>
                    <li class="divider"></li>
                    <li><a href="logout.php">Sign-out</a></li>
                  </ul>
                </div>
                
                <!-- Page Label -->
                <div class="h3" style="margin-top: 10px;">
                  <span class="label label-default center-block">Register</span>
                </div>
                
                <div  style="padding-top: 10px;"></div>
                
                <!-- Sign-in -->
                <div class="btn-group">
                  <a class="btn btn-default" href="form_sign-in.php" role="button">Sign-in</a>
                </div>
                <div  style="padding-top: 10px;"></div>
              
                <!-- Register -->
                <div class="btn-group">
                  <a class="btn btn-default" href="form_register.php" role="button">Register</a>
                </div>

              </div><!--======================================================== End sidebar menu items    -->
            </div><!--========================================================== End well                  -->
          </div><!--============================================================ End column 2              -->
        </div><!--============================================================== End Overall Column sizes  -->
      </div><!--================================================================ End row                   -->
    </div><!--================================================================== End container             -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="libraries/jquery/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="resources/js/offcanvas.js"></script>
    <script>
      $(function () { 
        $("[data-toggle='tooltip']").tooltip(); 
      });
    </script>
  </body>
</html>