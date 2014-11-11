<?php 
  // register.php
  // Register a user in the system to allow them
  // to login.
  //
  require 'resources/includes/include.global.php';

/*  //check to see if they're logged in
  if(!isset($_SESSION['logged_in'])) {
    header("Location: form_sign-in.php");
  }
  
  //get the user object from the session
  $userID = $_SESSION["userID"];
  $uTool = new UserTools();
  $user = $uTool->get($userID);
  
  // This function is only available to administrators.
  if ($user->privilage != 'A') {
    header("Location: form_sign-in.php");
  }*/
  
  //initialize php variables used in the form
  $username = "";
  $username_confirm = "";
  $password = "";
  $password_confirm = "";
  $error = "";
  $email = "";
  $email_confirm = "";
  $firstName = "";
  $lastName = "";
  
  //check to see that the form has been submitted
  if(isset($_POST['form_register'])) { 
  
    //retrieve the $_POST variables
    $username = $_POST['u_username'];
    $username_confirm = $_POST['u_confirmUsername'];
    $password = $_POST['u_password'];
    $password_confirm = $_POST['u_confirmPassword'];
    $email = $_POST['u_email'];
    $email_confirm = $_POST['u_confirmEmail'];
    $firstName = $_POST['u_firstName'];
    $lastName = $_POST['u_lastName'];
  
    //initialize variables for form validation
    $success = true;
    $userTools = new UserTools();
    
    //validate that the form was filled out correctly
    //check to see if user name already exists
    if($userTools->check_userNameExistence($username))
    {
      $error .= "That username is already taken.<br/> \n\r";
      $success = false;
    }

    //check to see if user name already exists
    if($userTools->check_emailExistence($email))
    {
      $error .= "That email is already Registered.<br/> \n\r";
      $success = false;
    }

    //check to see if passwords match
    if($username != $username_confirm) {
      $error .= "Usernames do not match.<br/> \n\r";
      $success = false;
    }

    //check to see if passwords match
    if($email != $email_confirm) {
      $error .= "Emails do not match.<br/> \n\r";
      $success = false;
    }

    //check to see if passwords match
    if($password != $password_confirm) {
      $error .= "Passwords do not match.<br/> \n\r";
      $success = false;
    }
  
    if($success)
    {
      //prep the data for saving in a new user object
      $data['user_username'] = $username;
      $data['user_password'] = md5($password); //encrypt the password for storage
      $data['user_email'] = $email;
      $data['user_firstName'] = $firstName;
      $data['user_lastName'] = $lastName;
    
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
        <div class="col-xs-12 col-sm-10">
          
          <div class="col-xs-10 col-sm-10 "><!--  column 1  -->

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
                      
                      <div class="form-group">
                        <label for="u_firstName" class="col-sm-2 control-label">First</label>
                        <div class="col-sm-10">
                          <input type="text" 
                                 class="form-control" 
                                 id="u_firstName" 
                                 name="u_firstName"
                                 value="<?php echo $firstName; ?>"
                                 placeholder="First"
                                 maxlenght="25"data-toggle="tooltip"
                                 data-placement="bottom"
                                 data-delay='{ "show": 100, "hide": 200 }'
                                 data-original-title="Enter your First Name."
                                 required
                          >
                        </div>
                      </div>
                      <!-- <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">Middle</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="inputEmail3" placeholder="Middle">
                        </div>
                      </div> -->
                      
                      <div class="form-group">
                        <label for="u_lastName" class="col-sm-2 control-label">Last</label>
                        <div class="col-sm-10">
                          <input type="text"
                                 class="form-control"
                                 id="u_lastName"
                                 name="u_lastName"
                                 value="<?php echo $lastName; ?>"
                                 placeholder="Last"
                                 maxlenght="30"
                                 data-toggle="tooltip"
                                 data-placement="bottom"
                                 data-delay='{ "show": 100, "hide": 200 }'
                                 data-original-title="Enter your Last Name."
                                 required
                          >
                        </div>
                      </div>
                    </div><!-- / Name Body -->
                  </div><!-- / Name Panel -->
                  
                  <div class="panel panel-info">                          <!-- User Info Panel -->
                    
                    <div class="panel-heading">
                      <h3 class="panel-title">User Info and Conformation:</h3>
                    </div>
                    
                    <div class="panel-body">                                <!-- User Info Body -->
                      
                      <div class="form-group">
                        <label for="u_username" class="col-sm-2 control-label">User Name</label>
                        <div class="col-sm-10">
                          <input type="text"
                                 class="form-control"
                                 id="u_username"
                                 name="u_username"
                                 value="<?php echo $username; ?>"
                                 placeholder="Username"
                                 maxlenght="20"
                                 data-toggle="tooltip"
                                 data-placement="bottom"
                                 data-delay='{ "show": 100, "hide": 200 }'
                                 data-original-title="Enter your prefered Username"
                                 required
                          >
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label for="u_confirmUsername" class="col-sm-2 control-label">Confirm</label>
                        <div class="col-sm-10">
                          <input type="text"
                                 class="form-control"
                                 id="u_confirmUsername"
                                 name="u_confirmUsername"
                                 value="<?php echo $username_confirm; ?>"
                                 placeholder="Confirm Username"
                                 maxlenght="20"
                                 data-toggle="tooltip"
                                 data-placement="bottom"
                                 data-delay='{ "show": 100, "hide": 200 }'
                                 data-original-title="Please confirm your Username."
                                 required
                          >
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label for="u_email" class="col-sm-2 control-label">E-mail</label>
                        <div class="col-sm-10">
                          <input type="email"
                                 class="form-control"
                                 id="u_email"
                                 name="u_email"
                                 value="<?php echo $email; ?>"
                                 placeholder="Email"
                                 maxlenght="50"
                                 data-toggle="tooltip"
                                 data-placement="bottom"
                                 data-delay='{ "show": 100, "hide": 200 }'
                                 data-original-title="Enter your Email."
                                 required
                          >
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label for="u_confirmEmail" class="col-sm-2 control-label">Confirm</label>
                        <div class="col-sm-10">
                          <input type="email"
                                 class="form-control"
                                 id="u_confirmEmail"
                                 name="u_confirmEmail"
                                 value="<?php echo $email_confirm; ?>"
                                 placeholder="Confirm Email"
                                 maxlenght="50"
                                 data-toggle="tooltip"
                                 data-placement="bottom"
                                 data-delay='{ "show": 100, "hide": 200 }'
                                 data-original-title="Please confirm your Email."
                                 required
                          >
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label for="u_password" class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10">
                          <input type="password"
                                 class="form-control"
                                 id="u_password"
                                 name="u_password"
                                 value="<?php echo $password; ?>"
                                 placeholder="Password"
                                 maxlenght="20"
                                 data-toggle="tooltip"
                                 data-placement="bottom"
                                 data-delay='{ "show": 100, "hide": 200 }'
                                 data-original-title="Enter your Password."
                                 required
                          >
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label for="u_confirmPassword" class="col-sm-2 control-label">Confirm</label>
                        <div class="col-sm-10">
                          <input type="password"
                                 class="form-control"
                                 id="u_confirmPassword"
                                 name="u_confirmPassword"
                                 value="<?php echo $password_confirm; ?>"
                                 placeholder="Confirm Password"
                                 maxlenght="20"
                                 data-toggle="tooltip"
                                 data-placement="bottom"
                                 data-delay='{ "show": 100, "hide": 200 }'
                                 data-original-title="Please confirm your Password."
                                 required
                          >
                        </div>
                      </div>

                    </div><!-- / User Info Body -->
                  </div><!-- / User Info Panel -->
                  <p><?php print $error; ?></p>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button name="form_register" value="form_register" type="submit" class="btn btn-default">Submit</button>
                    </div>
                  </div>
                </div><!-- / Register Body -->
              </div><!-- / Register Panel -->
            </form>

          </div><!--============================================================ End column 1  -->
        
          <div class="col-xs-2 col-sm-2 sidebar-offcanvas" id="sidebar"><!--  column 2  -->
            <div class="well">
              <div class="btn-group-vertical"><!--  sidebar menu items  -->
                      
                <!-- Main Menu -->
                <div class="btn-group">
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
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