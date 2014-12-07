<?php
require 'resources/includes/include.global.php';

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

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reports</title>

    <!-- Bootstrap -->
    <link href="libraries/custom/custom-bootstrap.css" rel="stylesheet">
    <link href="libraries/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet">

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

            <div class="well">
              <p>Page Main Content</p>
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
                  <span class="label label-default center-block">Reports</span>
                </div>
                
                <!-- Sets -->
                <select class="selectpicker" 
                        title="Sets..."
                        data-width="90px"
                        data-live-search="true"
                        multiple data-selected-text-format="count">
                  <option value="set_01">Set 1</option>
                  <option value="set_02">Set 2</option>
                  <option value="set_03">Set 3</option>
                  <option value="set_04">Set 4</option>
                  <option value="set_05">Set 5</option>
                  <option value="set_06">Set 6</option>
                  <option value="set_07">Set 7</option>
                  <option value="set_08">Set 8</option>
                  <option value="set_09">Set 9</option>
                </select>
                
                <!-- Parts -->
                <select class="selectpicker" 
                        title="Parts..."
                        data-width="90px"
                        data-live-search="true"
                        multiple data-selected-text-format="count">
                  <option value="parts_01">Parts 1</option>
                  <option value="parts_02">Parts 2</option>
                  <option value="parts_03">Parts 3</option>
                  <option value="parts_04">Parts 4</option>
                  <option value="parts_05">Parts 5</option>
                  <option value="parts_06">Parts 6</option>
                  <option value="parts_07">Parts 7</option>
                  <option value="parts_08">Parts 8</option>
                  <option value="parts_09">Parts 9</option>
                </select>
                
                <!-- Colors -->
                <select class="selectpicker" 
                        title="Colors..."
                        data-width="90px"
                        data-live-search="true"
                        multiple data-selected-text-format="count">
                  <option value="color_01">Color 1</option>
                  <option value="color_02">Color 2</option>
                  <option value="color_03">Color 3</option>
                  <option value="color_04">Color 4</option>
                  <option value="color_05">Color 5</option>
                  <option value="color_06">Color 6</option>
                  <option value="color_07">Color 7</option>
                  <option value="color_08">Color 8</option>
                  <option value="color_09">Color 9</option>
                </select>
                
                <!-- Types -->
                <select class="selectpicker" 
                        title="Types..."
                        data-width="90px"
                        data-live-search="true"
                        multiple data-selected-text-format="count">
                  <option value="type_01">Type 1</option>
                  <option value="type_02">Type 2</option>
                  <option value="type_03">Type 3</option>
                  <option value="type_04">Type 4</option>
                  <option value="type_05">Type 5</option>
                  <option value="type_06">Type 6</option>
                  <option value="type_07">Type 7</option>
                  <option value="type_09">Type 9</option>
                  <option value="type_08">Type 8</option>
                </select>
                
                <!-- Sizes -->
                <select class="selectpicker" 
                        title="Sizes..."
                        data-width="90px"
                        data-live-search="true"
                        multiple data-selected-text-format="count">
                  <option value="size_01">Size 1</option>
                  <option value="size_02">Size 2</option>
                  <option value="size_03">Size 3</option>
                  <option value="size_04">Size 4</option>
                  <option value="size_05">Size 5</option>
                  <option value="size_06">Size 6</option>
                  <option value="size_07">Size 7</option>
                  <option value="size_08">Size 8</option>
                  <option value="size_09">Size 9</option>
                </select>

                <div  style="padding-top: 10px;"></div>
                <div class="btn-group">
                  <!-- Clear all -->
                  <button type="button" class="btn btn-warning">Clear All</button>
                </div>
                <div  style="padding-top: 10px;"></div>
                <div class="btn-group">
                  <!-- Submit -->
                  <button type="button" class="btn btn-success">Submit</button>
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
    <script src="libraries/bootstrap/dist/js/bootstrap.js"></script>
    <script src="libraries/bootstrap-select/dist/js/bootstrap-select.js"></script>
    <script src="resources/js/offcanvas.js"></script>
    <script>
      $(document).ready(function() {
        $('.selectpicker').selectpicker();
    });
    </script>
  </body>
</html>