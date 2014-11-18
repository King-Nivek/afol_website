<?php
//  FormField class

class FormField {
  
  public $label_text = 'default';
  public $id = 'default';
  public $type = 'text';
  public $name = 'default';
  public $value = '';
  public $placeholder = 'default';
  public $maxlength = '30';
  public $tooltip_text = 'default';
  public $invalid_error = '';

  public function printField() {
      echo '<div class="form-group">',
              '<label for="'.$this->id.'" class="col-sm-3 control-label">'.$this->label_text.':</label>',
                '<div class="col-sm-6">',
                '<input ',  
                  'type="'.$this->type.'"',
                  'class="form-control"',
                  'id="'.$this->id.'"',
                  'name="'.$this->name.'"',
                  'value="'.$this->value.'"',
                  'placeholder="'.$this->placeholder.'"',
                  'maxlength="'.$this->maxlength.'"',
                  'data-toggle="tooltip"',
                  'data-placement="bottom"',
                  "data-delay='{ \"show\": 100, \"hide\": 100 }'",
                  'data-original-title="'.$this->tooltip_text.'"',
                '><span class="help-block">'.$this->invalid_error.'</span>',
              '</div>',
            '</div>';
  }
}
?>