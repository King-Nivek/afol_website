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

  $field_colorID = new FormField();
  $field_colorID->table = 'Lego_Color';
  $field_colorID->field = 'color_id';
  $field_colorID->label_text = 'Color ID';
  $field_colorID->id = 'colorID';
  $field_colorID->name = 'colorID';
  $field_colorID->placeholder = '97';
  $field_colorID->maxlength = 3;
  $field_colorID->tooltip_text = 'Please enter the BrickLink Color number.';

  $field_colorName = new FormField();
  $field_colorName->table = 'Lego_Color';
  $field_colorName->field = 'color_name';
  $field_colorName->label_text = 'Color Name';
  $field_colorName->id = 'colorName';
  $field_colorName->name = 'colorName';
  $field_colorName->placeholder = 'Tile';
  $field_colorName->maxlength = 40;
  $field_colorName->tooltip_text = 'Please enter the BrickLink Color Name.';

  $formButtons = '';
  $submitType = 'false';
  $validInput = 0;
  $originalKeys = array();

  $keys = array();
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

    if(isset($_POST[$field_colorID->id])) {                        //  Check if $_POST['colorID'] is set.
      if(!empty($_POST[$field_colorID->id])) {                     //  Check if it is empty.
        $input[$field_colorID->id] = makeSafer($_POST[$field_colorID->id]); //  Trim, strip slashes, and make certain character into their entity form.
        $field_colorID->value = $input[$field_colorID->id];        //  Set the fields value.

        if(isWholeNumber($input[$field_colorID->id])) {  //  Check that the input matches allowed characters and or character types.
          $field_colorID->has_success();        //  Shows check mark if valid.
          $validInput++;
        } else {
          $field_colorID->error = 'Bad Value';  //  Error String is set.
          $field_colorID->has_error();                  //  Shows and 'X' that it is not valid.
        }
      } else {
        $field_colorID->has_required();  //  Shows an asterisk if the field was required and submitted empty.
      }
    }

    if(isset($_POST[$field_colorName->id])) {                          //  Check if $_POST['colorName'] is set.
      if(!empty($_POST[$field_colorName->id])) {                       //  Check if it is empty.
        $input[$field_colorName->id] = makeSafer($_POST[$field_colorName->id]); //  Trim, strip slashes, and make certain character into their entity form.
        $field_colorName->value = $input[$field_colorName->id];        //  Set the fields value.

        if(isColorName($input[$field_colorName->id])) {  //  Check that the input matches allowed characters and or character types.
          $field_colorName->has_success();      //  Shows check mark if valid.
          $validInput++;
        } else {
          $field_colorName->error = 'Bad Value';  //  Error String is set.
          $field_colorName->has_error();                  //  Shows and 'X' that it is not valid.
        }
      } else {
        $field_colorName->has_required();  //  Shows an asterisk if the field was required and submitted empty.
      }
    }


    $data = array_merge($field_colorID->get_column_Value(),
                        $field_colorName->get_column_Value());

    if($submitType === 'delete') {
      whatToDo($submitType, $keys, $data, $originalKeys);
    }

    if($validInput === 2 && $submitType !== 'delete') {
      whatToDo($submitType, $keys, $data, $originalKeys);
    }

    if(isset($_POST['keys']) && !empty($_POST['keys'])) {

      $posted_keys = $_POST['keys'];
      $posted_keys = substr_replace($posted_keys,"}",-2);
      $posted_keys = str_replace("'",'"',$posted_keys);
      $posted_keys = json_decode($posted_keys);

      $results = $db->select("Lego_Color","*","color_id='$posted_keys->color_id'");

      $originalKeys = array();
      $originalKeys[] = array('field' => 'color_id', 'value' => $results['color_id']);

      $_SESSION['originalKeys'] = serialize($originalKeys);

      $field_colorID->value = $results['color_id'];
      $field_colorName->value = $results['color_name'];

    }
  } else {
      $formButtons = formAddRecord();
  }

echo html_header("Color form"),
     html_containerStart() ;
?>
              <form id="theForm" class="form-horizontal" role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                
                <div class="panel panel-primary">

                  <div class="panel-heading">
                    <h3 class="panel-title">Form: Colors</h3>
                  </div>

                  <div class="panel-body">
                    <?php 
                      echo $field_colorID->toString(),"\n",
                           $field_colorName->toString(),"\n",
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