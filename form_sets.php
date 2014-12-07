<?php
// to see comments that would be very similar to this pages look at "form_colorpart.php"

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

  $field_setID = new FormField();
  $field_setID->table = 'Lego_Set';
  $field_setID->field = 'set_id';
  $field_setID->label_text = 'Set ID';
  $field_setID->id = 'setID';
  $field_setID->name = 'setID';
  $field_setID->placeholder = '70702-1';
  $field_setID->maxlength = 10;
  $field_setID->tooltip_text = 'Please enter the Lego Set number.';

  $field_setName = new FormField();
  $field_setName->table = 'Lego_Set';
  $field_setName->field = 'set_name';
  $field_setName->label_text = 'Set Name';
  $field_setName->id = 'setName';
  $field_setName->name = 'setName';
  $field_setName->placeholder = 'Warp Stinger';
  $field_setName->maxlength = 50;
  $field_setName->tooltip_text = 'Please enter the Lego Set name.';

  $field_setTheme = new FormField();
  $field_setTheme->table = 'Lego_Set';
  $field_setTheme->field = 'set_category';
  $field_setTheme->label_text = 'Set Theme';
  $field_setTheme->id = 'setTheme';
  $field_setTheme->name = 'setTheme';
  $field_setTheme->placeholder = 'Galaxy Squad';
  $field_setTheme->maxlength = 40;
  $field_setTheme->tooltip_text = 'Please enter the Lego Set Theme name.';
  
  $formButtons = '';
  $submitType = 'false';
  $validInput = 0;
  $originalKeys = array();

  $keys = array();
  $keys[] = &$field_setID;

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

    if(isset($_POST[$field_setID->id ])) {                      //  Check if $_POST['setID'] is set.
      if(!empty($_POST[$field_setID->id ])) {                   //  Check if it is empty.
        $input[$field_setID->id ] = makeSafer($_POST[$field_setID->id ]); //  Trim, strip slashes, and make certain character into their entity form.
        $field_setID->value = $input[$field_setID->id ];        //  Set the fields value.

        if(isSetID($input[$field_setID->id ])) {  //  Check that the input matches allowed characters and or character types.
          $field_setID->has_success();  //  Shows check mark if valid.
          $validInput++;
        } else {
          $field_setID->error = 'Bad Value';  //  Error String is set.
          $field_setID->has_error();          //  Shows and 'X' that it is not valid.
        }
      } else {
        $field_setID->has_required();  //  Shows an asterisk if the field was required and submitted empty.
      }
    }

    if(isset($_POST[$field_setName->id])) {                        //  Check if $_POST['setName'] is set.
      if(!empty($_POST[$field_setName->id])) {                     //  Check if it is empty.
        $input[$field_setName->id] = makeSafer($_POST[$field_setName->id]); //  Trim, strip slashes, and make certain character into their entity form.
        $field_setName->value = $input[$field_setName->id];        //  Set the fields value.

        if(isSetName($input[$field_setName->id])) {  //  Check that the input matches allowed characters and or character types.
          $field_setName->has_success();    //  Shows check mark if valid.
          $validInput++;
        } else {
          $field_setName->error = 'Bad Value';  //  Error String is set.
          $field_setName->has_error();          //  Shows and 'X' that it is not valid.
        }
      } else {
        $field_setName->has_required();  //  Shows an asterisk if the field was required and submitted empty.
      }
    }

    if(isset($_POST[$field_setTheme->id])) {                         //  Check if $_POST['setTheme'] is set.
      if(!empty($_POST[$field_setTheme->id])) {                      //  Check if it is empty.
        $input[$field_setTheme->id] = makeSafer($_POST[$field_setTheme->id]); //  Trim, strip slashes, and make certain character into their entity form.
        $field_setTheme->value = $input[$field_setTheme->id];        //  Set the fields value.

        if(isSetName($input[$field_setTheme->id])) { //  Check that the input matches allowed characters and or character types.
          $field_setTheme->has_success();   //  Shows check mark if valid.
          $validInput++;
        } else {
          $field_setTheme->error = 'Bad Value'; //  Error String is set.
          $field_setTheme->has_error();                 //  Shows and 'X' that it is not valid.
        }
      } else {
        $field_setTheme->has_required(); //  Shows an asterisk if the field was required and submitted empty.
      }
    }

    $data = array_merge($field_setID->get_column_Value(),
                        $field_setName->get_column_Value(),
                        $field_setTheme->get_column_Value());

    if($submitType === 'delete') {
      whatToDo($submitType, $keys, $data, $originalKeys);
    }

    if($validInput === 3 && $submitType !== 'delete') {
      whatToDo($submitType, $keys, $data, $originalKeys);
    }


    if(isset($_POST['keys']) && !empty($_POST['keys'])) {

      $posted_keys = $_POST['keys'];
      $posted_keys = substr_replace($posted_keys,"}",-2);
      $posted_keys = str_replace("'",'"',$posted_keys);
      $posted_keys = json_decode($posted_keys);

      $results = $db->select("Lego_Set","*","set_id='$posted_keys->set_id'");
      
      $originalKeys = array();
      $originalKeys[] = array('field' => 'set_id', 'value' => $results['set_id']);

      $_SESSION['originalKeys'] = serialize($originalKeys);

      $field_setID->value = $results['set_id'];
      $field_setName->value = $results['set_name'];
      $field_setTheme->value = $results['set_category'];
    }

  } else {
      $formButtons = formAddRecord();
  }

echo html_header("Set form"),
     html_containerStart() ;
?>
              <form id="theForm" class="form-horizontal" role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                
                <div class="panel panel-primary">

                  <div class="panel-heading">
                    <h3 class="panel-title">Form: Sets</h3>
                  </div>

                  <div class="panel-body">
                    <?php 
                      echo $field_setID->toString(),"\n",
                           $field_setName->toString(),"\n",
                           $field_setTheme->toString(),"\n",
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