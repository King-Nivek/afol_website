<?php
require 'resources/includes/include.global.php';
require_once 'resources/classes/class.FormField.php';

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

  $field_setID = new FormField();
  $field_setID->label_text = 'Set ID';
  $field_setID->id = 'setID';
  $field_setID->name = 'setID';
  $field_setID->placeholder = '70702-1';
  $field_setID->tooltip_text = 'Please enter the Lego Set number.';

  $field_setName = new FormField();
  $field_setName->label_text = 'Set Name';
  $field_setName->id = 'setName';
  $field_setName->name = 'setName';
  $field_setName->placeholder = 'Warp Stinger';
  $field_setName->tooltip_text = 'Please enter the Lego Set name.';

  $field_setTheme = new FormField();
  $field_setTheme->label_text = 'Set Theme';
  $field_setTheme->id = 'setTheme';
  $field_setTheme->name = 'setTheme';
  $field_setTheme->placeholder = 'Galaxy Squad';
  $field_setTheme->tooltip_text = 'Please enter the Lego Set Theme name.';
  
  // print_r($_POST['keys']);
  if($_SERVER["REQUEST_METHOD"] == "POST") {
    // echo "in server<br>\n";
    if(isset($_POST['keys'])) {
      // echo "in post<br>\n";
      $posted_keys = $_POST['keys'];
      $posted_keys = substr_replace($posted_keys,"}",-2);
      $posted_keys = str_replace("'",'"',$posted_keys);
      $posted_keys = json_decode($posted_keys);
      // print_r($posted_keys);
      $results = $db->select("Lego_Set","*","set_id='$posted_keys->set_id'");
      // print_r($results);
      $field_setID->value = $results['set_id'];
      $field_setName->value = $results['set_name'];
      $field_setTheme->value = $results['set_category'];

    }
  }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Set Form</title>

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

            <div class="well">
              <form class="form-horizontal" role="form">
                
                <div class="panel panel-primary">

                  <div class="panel-heading">
                    <h3 class="panel-title">Form: Sets</h3>
                  </div>

                  <div class="panel-body">
                    <?php 
                      echo $field_setID->printField(),"\n",
                           $field_setName->printField(),"\n",
                           $field_setTheme->printField(),"\n"; 
                    ?>
                    <div class="form-group">
                      <div class="col-sm-offset-3 col-sm-9">
                        <button type="button"
                                name="submit"
                                value="modify" 
                                class="btn btn-default"
                        >Modify</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <button type="button"
                                name="submit"
                                value="delete" 
                                class="btn btn-danger"
                        >Delete</button>
                      </div>
                    </div>
                  </div>
                </div><!-- End panel  -->
              </form>
            </div>
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
                  <span class="label label-default center-block">Forms</span>
                </div>
                
                <div  style="padding-top: 10px;"></div>
                
                <!-- Sets -->
                <div class="btn-group">
                  <a class="btn btn-default" href="forms.php" role="button">Forms Home</a>
                </div>
                <!-- 
                <!-- Colors - ->
                <div class="btn-group">
                  <a class="btn btn-default" href="form_parts.php" role="button">Parts</a>
                </div>
                
                <!-- Parts - ->
                <div class="btn-group">
                  <a class="btn btn-default" href="form_colors.php" role="button">Colors</a>
                </div>
                
                <!-- ColorParts - ->
                <div class="btn-group">
                  <a class="btn btn-default" href="form_colorpart.php" role="button">ColorParts</a>
                </div>
                
                <!-- SetParts - ->
                <div class="btn-group">
                  <a class="btn btn-default" href="form_setpart.php" role="button">SetParts</a>
                </div>
 -->
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