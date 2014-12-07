<?php
require 'resources/includes/include.global.php';
//require_once 'resources/classes/class.TableTools.php';


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
/*
// This function is only available to administrators.
if ($user->privilage != 'A') {
  header("Location: form_sign-in.php");
}
*/
$toID = "";
$toUser = null;

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forms</title>

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

            <div class="well">
              <div id="tableRecords">
               <p>Choose a Record Listing from the Left.</p>
              </div>

              <form id="form_type" action="" method="POST">
                <input id="keys" type="hidden" name="keys" value="keys">
              </form>

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
                  <span class="label label-default center-block">Forms</span>
                </div>
                                    
                <div  style="padding-top: 10px;"></div>

                <?php if ($user->privilage === 'A') { /* These are Checks to see if user is an admin. */?>
                <!-- Users -->
                <div class="btn-group">
                  <button class="btn btn-default" name="tableType" value="User" id="bttn_users" role="button">Users</button>
                  <!-- <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="form_sets.php">Add New</a></li>
                  </ul> -->
                </div>
                <?php } ?>

                <!-- Sets -->
                <div class="btn-group">
                  <button class="btn btn-default" name="tableType" value="Lego_Set" id="bttn_sets" role="button">Sets</button>
                  <?php if ($user->privilage === 'A') { ?>
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="form_sets.php">Add New</a></li>
                  </ul>
                  <?php } ?>
                </div>

                <?php if ($user->privilage !== 'A') { ?> <br> <?php } ?>
                <!-- Parts -->
                <div class="btn-group">
                  <button class="btn btn-default" name="tableType" value="Lego_Part" id="bttn_parts" role="button">Parts</button>
                  <?php if ($user->privilage === 'A') { ?>
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="form_parts.php">Add New</a></li>
                  </ul>
                  <?php } ?>
                </div>

                <!-- Colors -->
                <div class="btn-group">
                  <button class="btn btn-default" name="tableType" value="Lego_Color" id="bttn_color" role="button">Colors</button>
                  <?php if ($user->privilage === 'A') { ?>
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="form_colors.php">Add New</a></li>
                  </ul>
                  <?php } ?>
                </div>

                <!-- ColorParts -->
                <div class="btn-group">
                  <button class="btn btn-default" name="tableType" value="Lego_ColorPart" id="bttn_colorPart" role="button">ColorParts</button>
                  <?php if ($user->privilage === 'A') { ?>
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="form_colorpart.php">Add New</a></li>
                  </ul>
                  <?php } ?>
                </div>

                <!-- SetParts -->
                <div class="btn-group">
                  <button class="btn btn-default" name="tableType" value="Lego_SetPart" id="bttn_setPart" role="button">SetParts</button>
                  <?php if ($user->privilage === 'A') { ?>
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="form_setpart.php">Add New</a></li>
                  </ul>
                  <?php } ?>
                </div>

              </div><!--======================================================== End sidebar menu items    -->
            </div><!--========================================================== End well                  -->
          </div><!--============================================================ End column 2              -->
        </div><!--============================================================== End Overall Column sizes  -->
      </div><!--================================================================ End row                   -->
    </div><!--================================================================== End container             -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="libraries/jquery/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="libraries/bootstrap/dist/js/bootstrap.js"></script>
    <script src="resources/js/offcanvas.js"></script>
    
    <script>
      $(function() {

        //  Modified example from "http://learn.jquery.com/ajax/jquery-ajax-methods/"
        $("button[name='tableType']").click(function(){
          // Using the core $.ajax() method
          $.ajax({
            // the URL for the request
            url: "resources/php/formsHelper.php",
            // the data to send (will be converted to a query string)
            data: {
              tableType: $(this).val()
            },
            // whether this is a POST or GET request
            type: "POST",
            // the type of data we expect back
            dataType : "json",
            // code to run if the request succeeds;
            // the response is passed to the function
            success: function( data ) {
              //  Putting in the panel and table for the data selected.
              $("#tableRecords").html(data['html']);
              $('#form_type').attr('action', data.form_type.toString());
            },
            // code to run if the request fails; the raw request and
            // status codes are passed to the function
            error: function( xhr, status, errorThrown ) {
            alert( "Sorry, there was a problem!" );
            console.log( "Error: " + errorThrown );
            console.log( "Status: " + status );
            console.dir( xhr );
            },
            // code to run regardless of success or failure
            complete: function( xhr ) {
              $('tr.data-row > td:gt(0)').click(function(data) {
                //  Go to modify page and set values to selected row
                $('#keys').val($(this).parent().data('key'));
                <?php if ($user->privilage === 'A') { ?>
                $('#form_type').submit();
                <?php } ?>
              });
            }
          });
        });
      });
    </script>
  </body>
</html>