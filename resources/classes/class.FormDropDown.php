<?php

//  FormDropDown class

class FormDropDown {
  public $table;
  public $field;

  public $label_text = 'default';
  public $id = 'default';
  public $name = 'default';
  public $value = '';
  public $error = '';

  //  name Function
  //
  //  Use:  
  //  Parameter(s):  
  //  Returns:  
  //
  public function toString() {

    $output = <<<TOSTRING
<div class="form-group">
  <label for="$this->id" class="col-sm-3 control-label">$this->label_text:</label>
  <div class="col-sm-6">
    <div class="btn-group">
      <select id="$this->id"></select>
    </div>
    <span class="help-block">$this->error</span>
  </div>
</div>

TOSTRING;
  
  return $output;
  }

  public function get_column_Value() {
    return array($this->field => $this->value);
  }

}

?>
      