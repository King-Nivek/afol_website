<?php
require 'resources/includes/include.global.php';
require_once 'resources/classes/class.FormField.php';
require_once 'resources/classes/class.FormDropDown.php';
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

  $field_partID = new FormDropDown();
  $field_partID->table = 'Lego_ColorPart';
  $field_partID->field = 'part_id';
  $field_partID->label_text = 'Part ID';
  $field_partID->id = 'partID';
  $field_partID->name = 'partID';

  $field_colorID = new FormDropDown();
  $field_colorID->table = 'Lego_ColorPart';
  $field_colorID->field = 'color_id';
  $field_colorID->label_text = 'Color Name';
  $field_colorID->id = 'colorID';
  $field_colorID->name = 'colorID';

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

  if($_SERVER["REQUEST_METHOD"] == "POST") {

    if(isset($_SESSION['originalKeys'])) {
      $originalKeys = unserialize($_SESSION['originalKeys']);
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

    if(isset($_POST[$field_partID->id])) {                         //  Check if $_POST[$field_partID->id] is set.
      if(!empty($_POST[$field_partID->id])) {                      //  Check if it is empty.
        $input[$field_partID->id] = makeSafer($_POST[$field_partID->id]); //  Trim, strip slashes, and make certain character into their entity form.
        $field_partID->value = $input[$field_partID->id];        //  Set the fields value.
      }
    }

    if(isset($_POST[$field_colorID->id])) {                         //  Check if $_POST[$field_colorID->id] is set.
      if(!empty($_POST[$field_colorID->id])) {                      //  Check if it is empty.
        $input[$field_colorID->id] = makeSafer($_POST[$field_colorID->id]); //  Trim, strip slashes, and make certain character into their entity form.
        $field_colorID->value = $input[$field_colorID->id];        //  Set the fields value.
      }
    }

    if(isset($_POST[$field_ColorPartQty->id])) {                         //  Check if $_POST['setTheme'] is set.
      if(!empty($_POST[$field_ColorPartQty->id])) {                      //  Check if it is empty.
        $input[$field_ColorPartQty->id] = makeSafer($_POST[$field_ColorPartQty->id]); //  Trim, strip slashes, and make certain character into their entity form.
        $field_ColorPartQty->value = $input[$field_ColorPartQty->id];        //  Set the fields value.

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


    $data = array_merge($field_partID->get_column_Value(),
                        $field_colorID->get_column_Value(),
                        $field_ColorPartQty->get_column_Value());

    if($submitType === 'delete') {
      whatToDo($submitType, $keys, $data, $originalKeys);
    }

    if($validInput === 1 && $submitType !== 'delete') {
      whatToDo($submitType, $keys, $data, $originalKeys);
    }


    if(isset($_POST['keys']) && !empty($_POST['keys'])) {

      $posted_keys = $_POST['keys'];
      $posted_keys = substr_replace($posted_keys,"}",-2);
      $posted_keys = str_replace("'",'"',$posted_keys);
      $posted_keys = json_decode($posted_keys);

      $results = $db->select("Lego_ColorPart","*","color_id='$posted_keys->color_id' AND part_id='$posted_keys->part_id'");

      $originalKeys = array();
      $originalKeys[] = array('field' => 'part_id', 'value' => $results['part_id']);
      $originalKeys[] = array('field' => 'color_id', 'value' => $results['color_id']);

      $_SESSION['originalKeys'] = serialize($originalKeys);

      $field_partID->value = $results['part_id'];
      $field_colorID->value = $results['color_id'];
      $field_ColorPartQty->value = $results['colorPart_qty'];

    }
  } else {
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

        $("[data-toggle='tooltip']").tooltip();

        $(<?php echo "'#",$field_partID->id,"'" ?>).multiselect({
          maxHeight: 200,
          buttonWidth: '200px',
          checkboxName: <?php echo "'",$field_partID->id,"'" ?>,
          nonSelectedText: 'Check an option!',
          enableCaseInsensitiveFiltering: true,
          filterPlaceholder: 'Search for something...',
          
        });

        $(<?php echo "'#",$field_colorID->id,"'" ?>).multiselect({
          maxHeight: 200,
          buttonWidth: '200px',
          checkboxName: <?php echo "'",$field_colorID->id,"'" ?>,
          nonSelectedText: 'Check an option!',
          enableCaseInsensitiveFiltering: true,
          filterPlaceholder: 'Search for something...',
          
        });
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
            //  
            $(<?php echo "'#",$field_partID->id,"'" ?>).multiselect('rebuild');
            $(<?php echo "'#",$field_colorID->id,"'" ?>).multiselect('rebuild');

          }
        });
      });
    </script>
  </body>
</html>