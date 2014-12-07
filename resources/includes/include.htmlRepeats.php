<?php
//  Repeats beginning and end of files


//  Function List                          //
//=========================================//
//  html_header($title, $extraCss = "\n")  //
//  html_containerStart()                  //
//  html_column1End()                      //
//  html_menuStart()                       //
//  html_sideButtons($label, $buttons)     //
//  html_Ending()                          //
//=========================================//


//  name Function
//
//  Use:  From Doctype to end of HEAD
//  Parameter(s):  
//  Returns:  
//
function html_header($title, $extraCss = "\n") {
$output = <<<TOSTRING
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>$title</title>

    <!-- Bootstrap -->
    <link href="libraries/custom/custom-bootstrap.css" rel="stylesheet">

    $extraCss

    <!-- Custom styles for this template -->
    <link href="resources/css/offcanvas.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

TOSTRING;
return $output;
}


//  name Function
//
//  Use:  From start of BODY to start of DIV class well
//  Parameter(s):  
//  Returns:  
//
function html_containerStart() {
$output = <<<TOSTRING
  <body>
    <div class="container">
      <div class="row row-offcanvas row-offcanvas-right">
        <div class="col-xs-12 col-sm-12">
          
          <div class="col-xs-12 col-sm-9 "><!--  column 1  -->

            <p class="pull-right visible-xs">
              <button type="button" class="btn btn-warning btn-xs" data-toggle="offcanvas">Toggle nav</button>
            </p>

            <div class="well">

TOSTRING;
return $output;
}

/* -- Main body of content goes here
              <h3>Welcome
              <?php echo " $user->userName!"; ?></h3>
              <p>Adult Fans of Lego Rejoice for your bricks are going to be tracked very soon.</p>
*/

//  name Function
//
//  Use:  From End of DIV class well to End of Container Column 1
//  Parameter(s):  
//  Returns:  
//
function html_column1End() {
$output = <<<TOSTRING
            </div>

          </div><!--============================================================ End column 1  -->

TOSTRING;
return $output;
}

//  name Function
//
//  Use:  From Start of Container column 2 to end of Menu Dropdown
//  Parameter(s):  
//  Returns:  
//
function html_menuStart() {
$output = <<<TOSTRING
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
TOSTRING;
return $output;
}

//  name Function
//
//  Use:  From side bar buttons start to Side bar buttons end
//  Parameter(s):  
//  Returns:  
//
function html_sideButtons($label, $buttons) {
$output = <<<TOSTRING
                <!-- Page Label -->
                <div class="h3" style="margin-top: 10px;">
                  <span class="label label-default center-block">$label</span>
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

TOSTRING;
return $output;
}

//  name Function
//
//  Use:  Closes all DIVs still open and scripts for jquery, bootstrap, and sidebar javascripts.
//          Does not close BODY or HTML tags so more scripts can be added to page
//  Parameter(s):  
//  Returns:  
//
function html_Ending() {
$output = <<<TOSTRING
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

TOSTRING;
return $output;
}
?>