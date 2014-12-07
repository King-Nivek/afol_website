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

  $field_partID = new FormField();
  $field_partID->table = 'Lego_Part';
  $field_partID->field = 'part_id';
  $field_partID->label_text = 'Part ID';
  $field_partID->id = 'partID';
  $field_partID->name = 'partID';
  $field_partID->placeholder = '2412b';
  $field_partID->maxlength = 15;
  $field_partID->tooltip_text = 'Please enter the LDraw Part number.';

  $field_partCategory = new FormField();
  $field_partCategory->table = 'Lego_Part';
  $field_partCategory->field = 'part_category';
  $field_partCategory->label_text = 'Part Category';
  $field_partCategory->id = 'partCategory';
  $field_partCategory->name = 'partCategory';
  $field_partCategory->placeholder = 'Tile';
  $field_partCategory->maxlength = 40;
  $field_partCategory->tooltip_text = 'Please enter the LDraw Part Category.';

  $field_partSize = new FormField();
  $field_partSize->table = 'Lego_Part';
  $field_partSize->field = 'part_size';
  $field_partSize->label_text = 'Part Size';
  $field_partSize->id = 'partSize';
  $field_partSize->name = 'partSize';
  $field_partSize->placeholder = '1 x 2';
  $field_partSize->maxlength = 20;
  $field_partSize->tooltip_text = 'Please enter the LDraw Part Size.';

  $field_partDescription = new FormField();
  $field_partDescription->table = 'Lego_Part';
  $field_partDescription->field = 'part_description';
  $field_partDescription->label_text = 'Part Description';
  $field_partDescription->id = 'partDescription';
  $field_partDescription->name = 'partDescription';
  $field_partDescription->placeholder = 'Tile 1 x 2 Grill with Bottom Groove';
  $field_partDescription->maxlength = 255;
  $field_partDescription->tooltip_text = 'Please enter the LDraw Part Description.';
  
  $formButtons = '';
  $submitType = 'false';
  $validInput = 0;
  $validPartSize = false;
  $originalKeys = array();

  $keys = array();
  $keys[] = &$field_partID;

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

    if(isset($_POST[$field_partID->id])) {                       //  Check if $_POST['partID'] is set.
      if(!empty($_POST[$field_partID->id])) {                    //  Check if it is empty.
        $input[$field_partID->id] = makeSafer($_POST[$field_partID->id]); //  Trim, strip slashes, and make certain character into their entity form.
        $field_partID->value = $input[$field_partID->id];        //  Set the fields value.

        if(isPartID($input[$field_partID->id])) {  //  Check that the input matches allowed characters and or character types.
          $field_partID->has_success();   //  Shows check mark if valid.
          $validInput++;
        } else {
          $field_partID->error = 'Bad Value'; //  Error String is set.
          $field_partID->has_error();         //  Shows and 'X' that it is not valid.
        }
      } else {
        $field_partID->has_required(); //  Shows an asterisk if the field was required and submitted empty.
      }
    }

    if(isset($_POST[$field_partCategory->id])) {                             //  Check if $_POST['partCategory'] is set.
      if(!empty($_POST[$field_partCategory->id])) {                          //  Check if it is empty.
        $input[$field_partCategory->id] = makeSafer($_POST[$field_partCategory->id]); //  Trim, strip slashes, and certain character into their entity form.
        $field_partCategory->value = $input[$field_partCategory->id];        //  Set the fields value.

        if(isPartCategory($input[$field_partCategory->id])) {  //  Check that the input matches allowed characters and or character types.
          $field_partCategory->has_success();         //  Shows check mark if valid.
          $validInput++;
        } else {
          $field_partCategory->error = 'Bad Value'; //  Error String is set.
          $field_partCategory->has_error();         //  Shows and 'X' that it is not valid.
        }
      } else {
        $field_partCategory->has_required(); //  Shows an asterisk if the field was required and submitted empty.
      }
    }

    if(isset($_POST[$field_partSize->id])) {                         //  Check if $_POST['partSize'] is set.
      if(!empty($_POST[$field_partSize->id])) {                      //  Check if it is empty.
        $input[$field_partSize->id] = makeSafer($_POST[$field_partSize->id]); //  Trim, strip slashes, and make certain character into their entity form.
        $field_partSize->value = $input[$field_partSize->id];        //  Set the fields value.

        if(isPartSize($input[$field_partSize->id])) {  //  Check that the input matches allowed characters and or character types.
          $field_partSize->has_success();   //  Shows check mark if valid.
          $validPartSize = true;
        } else {
          $field_partSize->error = 'Bad Value'; //  Error String is set.
          $field_partSize->has_error();         //  Shows and 'X' that it is not valid.
        }
      } else {
        $validPartSize = true;
      }
    }

    if(isset($_POST[$field_partDescription->id])) {                                //  Check if $_POST['partDescription'] is set.
      if(!empty($_POST[$field_partDescription->id])) {                             //  Check if it is empty.
        $input[$field_partDescription->id] = makeSafer($_POST[$field_partDescription->id]); //  Trim, strip slashes, and certain character into their entity form.
        $field_partDescription->value = $input[$field_partDescription->id];        //  Set the fields value.

        if(isPartDescription($input[$field_partDescription->id])) {  //  Check that the input matches allowed characters and or character types.
          $field_partDescription->has_success();            //  Shows check mark if valid.
          $validInput++;
        } else {
          $field_partDescription->error = 'Bad Value';  //  Error String is set.
          $field_partDescription->has_error();                  //  Shows and 'X' that it is not valid.
        }
      } else { 
        $field_partDescription->has_required();  //  Shows an asterisk if the field was required and submitted empty.
      }
    }

    $data = array_merge($field_partID->get_column_Value(),
                        $field_partCategory->get_column_Value(),
                        $field_partSize->get_column_Value(),
                        $field_partDescription->get_column_Value());


    if($submitType === 'delete') {
      whatToDo($submitType, $keys, $data, $originalKeys);
    }

    if($validInput === 3 && $submitType !== 'delete' && $validPartSize === true) {
      whatToDo($submitType, $keys, $data, $originalKeys);
    }

    if(isset($_POST['keys']) && !empty($_POST['keys'])) {
      $posted_keys = $_POST['keys'];
      $posted_keys = substr_replace($posted_keys,"}",-2);
      $posted_keys = str_replace("'",'"',$posted_keys);
      $posted_keys = json_decode($posted_keys);

      $results = $db->select("Lego_Part","*","part_id='$posted_keys->part_id'");

      $originalKeys = array();
      $originalKeys[] = array('field' => 'part_id', 'value' => $results['part_id']);

      $_SESSION['originalKeys'] = serialize($originalKeys);

      $field_partID->value = $results['part_id'];
      $field_partCategory->value = $results['part_category'];
      $field_partSize->value = $results['part_size'];
      $field_partDescription->value = $results['part_description'];
    }

  } else {
      $formButtons = formAddRecord();
  }

  echo html_header("Part form"),
       html_containerStart() ;
?>
              <form id="theForm" class="form-horizontal" role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                
                <div class="panel panel-primary">

                  <div class="panel-heading">
                    <h3 class="panel-title">Form: Parts</h3>
                  </div>

                  <div class="panel-body">
                    <?php 
                      echo $field_partID->toString(),"\n",
                           $field_partCategory->toString(),"\n",
                           $field_partSize->toString(),"\n",
                           $field_partDescription->toString(),"\n",
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