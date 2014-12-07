<?php
require 'resources/includes/include.global.php';               ## General includes
require_once 'resources/classes/class.FormField.php';          ## For making form field objects
require_once 'resources/classes/class.FormDropDown.php';       ## For making form drop-down objects
require_once 'resources/includes/include.htmlFormButtons.php'; ## For adding the button(s) to the form
require_once 'resources/php/validationTools.php';              ## For validating input
require_once 'resources/php/inputValid.php';                   ## For entering input into the database

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

  ####  Fields for input
  #########################

  //  part_id
  $field_partID = new FormDropDown();
  $field_partID->table = 'Lego_ColorPart';
  $field_partID->field = 'part_id';
  $field_partID->label_text = 'Part ID';
  $field_partID->id = 'partID';
  $field_partID->name = 'partID';

  //  color_id in value but color_name shown to user
  $field_colorID = new FormDropDown();
  $field_colorID->table = 'Lego_ColorPart';
  $field_colorID->field = 'color_id';
  $field_colorID->label_text = 'Color Name';
  $field_colorID->id = 'colorID';
  $field_colorID->name = 'colorID';

  //  colorPart_qty == the quantity of a certain part with a certain color
  $field_ColorPartQty = new FormField();
  $field_ColorPartQty->table = 'Lego_ColorPart';
  $field_ColorPartQty->field = 'colorPart_qty';
  $field_ColorPartQty->label_text = 'ColorPart Quantity';
  $field_ColorPartQty->id = 'colorPart_qty';
  $field_ColorPartQty->name = 'colorPart_qty';
  $field_ColorPartQty->placeholder = '14';
  $field_ColorPartQty->maxlength = 4;
  $field_ColorPartQty->tooltip_text = 'Please enter the Quantity of this part.';

  $formButtons = '';
  $submitType = 'false';
  $validInput = 0;
  $originalKeys = array();

  $keys = array();
  $keys[] = &$field_partID;
  $keys[] = &$field_colorID;

  ////  Main Check for Post.
  if($_SERVER["REQUEST_METHOD"] == "POST") {

    //  Get the original keys back after a post.
    if(isset($_SESSION['originalKeys'])) {
      $originalKeys = unserialize($_SESSION['originalKeys']);
    }

    //  Checks for the submit type and checks that it has not been tampered with.
    if(isset($_POST['submitType'])) {
      $submitType = makeSafer($_POST['submitType']);
      if(!($submitType === 'modify' || $submitType === 'delete' || $submitType === 'addNew')) {
        $submitType = 'false';
      }
    }

    //  Checks to see what type of buttons the page needs.
    if(isset($submitType) && $submitType === 'addNew') {
      $formButtons = formAddRecord();
    } else {
      $formButtons = formModifyDeleteRecord();
    }

    //  An array to hold the posted input.
    $input = array();

    ////  The input fields checks and feedback.

    if(isset($_POST[$field_partID->id])) {                         //  Check if $_POST[$field_partID->id] is set.
      if(!empty($_POST[$field_partID->id])) {                      //  Check if it is empty.
        $input[$field_partID->id] = makeSafer($_POST[$field_partID->id]); //  Trim, strip slashes, and make certain character into their entity form.
        $field_partID->value = $input[$field_partID->id];          //  Set the fields value.
      }
    }

    if(isset($_POST[$field_colorID->id])) {                         //  Check if $_POST[$field_colorID->id] is set.
      if(!empty($_POST[$field_colorID->id])) {                      //  Check if it is empty.
        $input[$field_colorID->id] = makeSafer($_POST[$field_colorID->id]); //  Trim, strip slashes, and make certain character into their entity form.
        $field_colorID->value = $input[$field_colorID->id];        //  Set the fields value.
      }
    }

    if(isset($_POST[$field_ColorPartQty->id])) {                         //  Check if $_POST[$field_ColorPartQty->id] is set.
      if(!empty($_POST[$field_ColorPartQty->id])) {                      //  Check if it is empty.
        $input[$field_ColorPartQty->id] = makeSafer($_POST[$field_ColorPartQty->id]); //  Trim, strip slashes, and make certain character into their entity form.
        $field_ColorPartQty->value = $input[$field_ColorPartQty->id];    //  Set the fields value.

        if(isWholeNumber($input[$field_ColorPartQty->id])) { //  Check that the input matches allowed characters and or character types.
          $field_ColorPartQty->has_success();   //  Shows check mark if valid.
          $validInput++;
        } else {
          $field_ColorPartQty->error = 'Bad Value'; //  Error String is set.
          $field_ColorPartQty->has_error();         //  Shows and 'X' that it is not valid.
        }
      } else {
        $field_ColorPartQty->has_required(); //  Shows an asterisk if the field was required and submitted empty.
      }
    }

    //  Data to be entered into database
    $data = array_merge($field_partID->get_column_Value(),
                        $field_colorID->get_column_Value(),
                        $field_ColorPartQty->get_column_Value());

    //  Check if user is deleting for we do not need valid non key fields to 
    //    delete.  The key will be checked in the whatToDo function so one 
    //    can not delete the wrong thing.
    if($submitType === 'delete') {
      whatToDo($submitType, $keys, $data, $originalKeys);
    }

    //  whatToDo if user is adding new or modifying
    if($validInput === 1 && $submitType !== 'delete') {
      whatToDo($submitType, $keys, $data, $originalKeys);
    }


    //  If keys have been passed in from the forms page the we need to use them
    //    to populate the fields with the correct record.
    if(isset($_POST['keys']) && !empty($_POST['keys'])) {

      //  This is a fancy block to turn my none json string into something that
      //    can be used by json_decode($var)
      $posted_keys = $_POST['keys'];
      $posted_keys = substr_replace($posted_keys,"}",-2);
      $posted_keys = str_replace("'",'"',$posted_keys);
      $posted_keys = json_decode($posted_keys);

      //  Getting a query from the database.
      $results = $db->select("Lego_ColorPart","*","color_id='$posted_keys->color_id' AND part_id='$posted_keys->part_id'");

      //  Saving the original keys for later use.
      $originalKeys = array();
      $originalKeys[] = array('field' => 'part_id', 'value' => $results['part_id']);
      $originalKeys[] = array('field' => 'color_id', 'value' => $results['color_id']);

      //  Making sure we can keep using the original keys.
      $_SESSION['originalKeys'] = serialize($originalKeys);

      //  Setting the fields to the selected values.
      $field_partID->value = $results['part_id'];
      $field_colorID->value = $results['color_id'];
      $field_ColorPartQty->value = $results['colorPart_qty'];

    }
  } else {
    //  Means we are adding a new record.
    $formButtons = formAddRecord();
  }

$extraCss = <<<EXTRACSS
    <!-- Bootstrap-Multiselect -->
    <link rel="stylesheet" href="libraries/bootstrap-multiselect/dist/css/bootstrap-multiselect.css" type="text/css"/>

EXTRACSS;

echo html_header("ColorPart form", $extraCss),
     html_containerStart() ;
?>
              <form id="theForm" class="form-horizontal" role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">

                <div class="panel panel-primary" style="overflow:visible;">

                  <div class="panel-heading">
                    <h3 class="panel-title">Form: ColorParts</h3>
                  </div>

                  <div class="panel-body">

                    <?php echo $field_partID->toString(),"\n",
                               $field_colorID->toString(),"\n",
                               $field_ColorPartQty->toString(),"\n",
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
    <script type="text/javascript" src="libraries/bootstrap-multiselect/dist/js/bootstrap-multiselect.js"></script>
    <script>
      $(function() {
        //  Tool tip worker.
        $("[data-toggle='tooltip']").tooltip();

        //  Making the part_id drop-down menu
        $(<?php echo "'#",$field_partID->id,"'" ?>).multiselect({
          maxHeight: 200,
          buttonWidth: '200px',
          checkboxName: <?php echo "'",$field_partID->id,"'" ?>,
          nonSelectedText: 'Check an option!',
          enableCaseInsensitiveFiltering: true,
          filterPlaceholder: 'Search for something...',
          
        });

        //  Making the color_id drop-down menu
        $(<?php echo "'#",$field_colorID->id,"'" ?>).multiselect({
          maxHeight: 200,
          buttonWidth: '200px',
          checkboxName: <?php echo "'",$field_colorID->id,"'" ?>,
          nonSelectedText: 'Check an option!',
          enableCaseInsensitiveFiltering: true,
          filterPlaceholder: 'Search for something...',
          
        });

        //  Setting the passed in selected item as the values for the drop-downs
        $(<?php echo "'#",$field_partID->id,"'" ?>).multiselect('select', <?php echo "'",$field_partID->value,"'" ?>);
        $(<?php echo "'#",$field_colorID->id,"'" ?>).multiselect('select', <?php echo "'",$field_colorID->value,"'" ?>);

        //  Modified example from "http://learn.jquery.com/ajax/jquery-ajax-methods/"
        // Using the core $.ajax() method
        $.ajax({
          // the URL for the request
          url: "resources/php/formDropdownData.php",
          // the data to send (will be converted to a query string)
          data: {
            color: 'Lego_Color',
            part: 'Lego_Part'
          },
          // whether this is a POST or GET request
          type: "POST",
          // the type of data we expect back
          dataType : "json",
          // code to run if the request succeeds;
          // the response is passed to the function
          success: function( data ) {
            //  Filling the drop-downs with their data and pre-selecting the passed in items values
            $(<?php echo "'#",$field_partID->id,"'" ?>).multiselect('dataprovider', data.part);
            $(<?php echo "'#",$field_partID->id,"'" ?>).multiselect('select', <?php echo "'",$field_partID->value,"'" ?>);
            $(<?php echo "'#",$field_colorID->id,"'" ?>).multiselect('dataprovider', data.color);
            $(<?php echo "'#",$field_colorID->id,"'" ?>).multiselect('select', <?php echo "'",$field_colorID->value,"'" ?>);

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
            //  Rebuilding the drop-downs so they can be used with the new data.
            $(<?php echo "'#",$field_partID->id,"'" ?>).multiselect('rebuild');
            $(<?php echo "'#",$field_colorID->id,"'" ?>).multiselect('rebuild');

          }
        });
      });
    </script>
  </body>
</html>