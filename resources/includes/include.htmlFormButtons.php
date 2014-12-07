<?php
//  Form Button Repeats


//  name Function
//
//  Use:  
//  Parameter(s):  
//  Returns:  
//
function formModifyDeleteRecord() {
$output = <<<TOSTRING
<div class="form-group">
  <div class="col-sm-offset-3 col-sm-9">
    <button type="submit"
            name="submitType"
            value="modify" 
            class="btn btn-default"
    >Modify</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <button type="button"
            id="bttn_delete"
            name="submitType"
            value="delete" 
            class="btn btn-danger"
    >Delete</button>
  </div>
</div>

TOSTRING;
return $output;
}
//  name Function
//
//  Use:  
//  Parameter(s):  
//  Returns:  
//
function formAddRecord() {
$output = <<<TOSTRING
<div class="form-group">
  <div class="col-sm-offset-3 col-sm-9">
    <button type="submit" 
            name="submitType" 
            value="addNew" 
            class="btn btn-default"
    >Submit</button>
  </div>
</div>

TOSTRING;
return $output;
}

function formToDeleteHidden() {
  $output = <<<TOSTRING
<!-- To be checked by functions on page re-load to check if should be deleted or not -->
<input id="toDelete" type="hidden" name="toDelete" value="false">

TOSTRING;
return $output;
}
function formDeleteScripts() {
  $output = <<<TOSTRING
<script src="../bootbox-4.3.0/bootbox.min.js"></script>
<script>
  $(document).ready(function() {
    $('#bttn_delete').click(function(e) {
      bootbox.confirm("Are you sure?", function(result) {
        if(result) {
          $('#bttn_delete').attr('type','submit');
          $('#bttn_delete').off('click');
          $('#bttn_delete').trigger('click');
        }
      });
    });
  });
</script>

TOSTRING;
return $output;
}
?>