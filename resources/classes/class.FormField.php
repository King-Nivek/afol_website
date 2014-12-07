<?php
//  FormField class
/*
The Work Horse of the forms.

  - Added a parameter for:
     - public $table  --  to hold the name of the table it is associated with.
     - public $field  --  to hold the name of the table column it is associated with.
     - private $state  --  an array to hold the words the will be used to give feedback to the user, used in conjunction with the has_* functions
     - private $hasFeedback  --  used in conjunction with the has_* functions to give user feedback.

  - Added lines to the toString function give the user feedback when it is needed.

  - Added function to give different feedback to the user:
     - has_success
     - has_error
     - has_required
     - has_warning
*/
     
class FormField {
  public $table;
  public $field;
  private $state = array('','');
  private $hasFeedback = '';
  public $label_text = 'default';
  public $id = 'default';
  public $type = 'text';
  public $name = 'default';
  public $value = '';
  public $placeholder = 'default';
  public $maxlength = '30';
  public $tooltip_text = 'default';
  public $error = '';

  //  name Function
  //
  //  Use:  
  //  Parameter(s):  
  //  Returns:  
  //
  public function toString() {
    $statusID = $this->id.'Status';

    $output = <<<TOSTRING
<div class="form-group $this->hasFeedback">
  <label for="$this->id" class="col-sm-3 control-label">$this->label_text:</label>
  <div class="col-sm-6">
    <input
      type="$this->type"
      class="form-control"
      id="$this->id"
      name="$this->name"
      value="$this->value"
      placeholder="$this->placeholder"
      maxlength="$this->maxlength"
      data-toggle="tooltip"
      data-placement="bottom"
      data-delay="{ 'show': 100, 'hide': 100 }"
      data-original-title="$this->tooltip_text"
    ><span class="glyphicon {$this->state[0]} form-control-feedback" aria-hidden="true"></span>
     <span id="$statusID" class="sr-only">{$this->state[1]}</span>
     <span class="help-block">$this->error</span>
  </div>
</div>
TOSTRING;
  
  return $output;
  }

  public function has_success() {
    $this->hasFeedback = 'has-feedback';
    $this->state = array('glyphicon-ok', '(success)');
    $this->error = '';
  }

  public function has_error() {
    $this->hasFeedback = 'has-feedback';
    $this->state = array('glyphicon-remove', '(error)');
  }

  public function has_required() {
    $this->hasFeedback = 'has-feedback';
    $this->state = array('glyphicon-asterisk', '(required)');
    $this->error = '';
  }

  public function has_warning() {
    $this->hasFeedback = 'has-feedback';
    $this->state = array('glyphicon-warning-sign', '(warning)');
  }

  public function get_column_Value() {
    return array($this->field => $this->value);
  }
}
?>
      