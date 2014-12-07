<?php
  // form_sign-in.php
  // Provides a form for users to attempt to login to
  // the system. It uses UserTools to check credentials.
  //
  require 'resources/includes/include.global.php';
  require_once 'resources/php/validationTools.php';

  $error = "";
  $email = "";
  $password = "";

  //check to see if they've submitted the login form
  if(isset($_POST['sign-in']) && !empty($_POST["sign-in"])) { 
  
    $email = trim($_POST['u_email']);
    $password = trim($_POST['u_password']);
    
    if(isEmail($email) && isPassword($password)) {
      $userTools = new UserTools();
      if($userTools->login($email, $password)){ 
        //successful login, redirect them to a page
        header("Location: index.php");
      }
      else{
        $error = "<h4 class=\"text-danger\" style=\"text-align: center\">Incorrect username or password. Please try again.</h4>";
      }
    } else {
      $error = "<h4 class=\"text-danger\" style=\"text-align: center\">Bad Input for username or password. Please try again.</h4>";
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="User login." content="">
     <meta name="Kevin M. Albright" content="">
    <title>Sign-in</title>

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
    <img src="logo.gif" width="1" height="1" alt="">
    <div class="container">
      <div class="row row-offcanvas row-offcanvas-right">
        <div class="col-xs-12 col-sm-12">
          
          <div class="col-xs-12 col-sm-9 "><!--  column 1  -->

            <p class="pull-right visible-xs">
              <button type="button" class="btn btn-warning btn-xs" data-toggle="offcanvas">Toggle nav</button>
            </p>

            <div class="well">
                
              <!-- Page Label -->
              <div class="h4" style="margin-top: 10px;">
                <span class="label label-default">Sign-in</span>
              </div>

              <!-- FORM  
              ============================================================== -->
              <form id="form_sign-in" action="form_sign-in.php" method="POST" class="form-horizontal" role="form">
                <?php echo $error; ?>
                <div class="form-group">
                  <label for="u_email" class="col-sm-2 control-label">Email</label>
                  <div class="col-sm-10">

                    <input type="email" 
                           class="form-control" 
                           id="u_email" 
                           name="u_email" 
                           value="<?php echo $email; ?>"
                           maxlength = 50 
                           placeholder="Email"
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
                           maxlength = 30
                           placeholder="Password"
                    >

                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-10">
                    <div class="checkbox">
                      <label>
                        <input type="checkbox"> Remember me
                      </label>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-offset-2 col-sm-10">
                    <button name="sign-in" value="sign-in" type="submit" class="btn btn-default">Sign in</button>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-offset-9 col-sm-3">
                    <a class="btn btn-default" href="form_forgot.php" role="button">Forgot Password</a>
                  </div>
                </div>
              </form><!-- End Form  ======================================== -->
            </div>

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
                  <span class="label label-default center-block">Sign-in</span>
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
  </body>
</html>