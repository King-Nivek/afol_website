<?php
//  All input is valid now do this...

function whatToDo($submitType, &$keys, $data, $originalKeys) {
  global $db;
  $where;
  $originalwhere;
  $count;
  $check;
  $fields;
  $isGood = true;
  $usernameError = '';
  $emailError = '';


  if($submitType === 'addNew') {
    $originalKeys = array();
    $originalKeys[] = array('field' => '', 'value' => '');
    $originalKeys[] = array('field' => '', 'value' => '');
    $originalKeys[] = array('field' => '', 'value' => '');
  }

  if(count($keys) == 1) {
    ////  If One Key
    $count = 1;

    if ($originalKeys[0]['field'] === 'user_id') {

      $results = $db->select($keys[0]['user_id']->table, "*", "user_username = '{$keys[0]['user_username']->value}'");
      if(!empty($results['user_username'])  && $results['user_id'] !== $keys[0]['user_id']->value) {
        $usernameError = "This Username is already in use!\n";
        $keys[0]['user_username']->has_warning();
        $isGood = false;
      }

      $results = $db->select($keys[0]['user_id']->table, "*", "user_email = '{$keys[0]['user_email']->value}'");
      if(!empty($results['user_email']) && $results['user_id'] !== $keys[0]['user_id']->value) {
        $emailError = "This E-mail is already in use!\n";
        $keys[0]['user_email']->has_warning();
        $isGood = false;
      }

      if($isGood) {
        $keys = array(0 => &$keys[0]['user_id']);

      } else {
        $keys[0]['user_username']->error = $usernameError;
        $keys[0]['user_email']->error = $emailError;
        return;
      }
    }

    $fields = array($keys[0]->field => "{$keys[0]->value}");

    $where = "{$keys[0]->field} = '{$keys[0]->value}'";

    $originalwhere = "{$originalKeys[0]['field']} = '{$originalKeys[0]['value']}'";

    $check = ($originalKeys[0]['value'] == $keys[0]->value);

  } elseif (count($keys) == 2) {
    ////  If Two Keys
    $count = 2;

    $fields = array($keys[0]->field => "{$keys[0]->value}",
                    $keys[1]->field => "{$keys[1]->value}");

    $where = "{$keys[0]->field} = '{$keys[0]->value}'"
           . " AND {$keys[1]->field} = '{$keys[1]->value}'";

    $originalwhere = "{$originalKeys[0]['field']} = '{$originalKeys[0]['value']}'"
                   . " AND {$originalKeys[1]['field']} = '{$originalKeys[1]['value']}'";

    $check = ($originalKeys[0]['value'] === $keys[0]->value
           && $originalKeys[1]['value'] === $keys[1]->value);
  
  } elseif (count($keys) == 3) {
    ////  If Three Keys
    $count = 3;

    $fields = array($keys[0]->field => "{$keys[0]->value}",
                    $keys[1]->field => "{$keys[1]->value}",
                    $keys[2]->field => "{$keys[2]->value}");

    $where = "{$keys[0]->field} = '{$keys[0]->value}'"
           . " AND {$keys[1]->field} = '{$keys[1]->value}'"
           . " AND {$keys[2]->field} = '{$keys[2]->value}'";

    $originalwhere = "{$originalKeys[0]['field']} = '{$originalKeys[0]['value']}'"
                   . " AND {$originalKeys[1]['field']} = '{$originalKeys[1]['value']}'"
                   . " AND {$originalKeys[2]['field']} = '{$originalKeys[2]['value']}'";

    $check = ($originalKeys[0]['value'] === $keys[0]->value
           && $originalKeys[1]['value'] === $keys[1]->value
           && $originalKeys[2]['value'] === $keys[2]->value);
  }

  
  switch ($submitType) {
    case 'modify':
      # code...
      if($check) {
        $db->modify($keys[0]->table, $data, $where);
        unset($_SESSION['originalKeys']);
        header("Location: forms.php");

      } else {
        $results = $db->select($keys[0]->table, "*", $where);
        
        if(empty($results["{$keys[0]->field}"])) {

          if($count > 1) {
            $sql = "UPDATE {$keys[0]->table} SET {$keys[0]->field} = '{$db->mysqli->real_escape_string($keys[0]->value)}',"
                                              ." {$keys[1]->field} = '{$db->mysqli->real_escape_string($keys[1]->value)}'";
            if($count === 3) {
                                      $sql .= ", {$keys[2]->field} = '{$db->mysqli->real_escape_string($keys[2]->value)}'";
            }
            $sql .= "WHERE $originalwhere";
            $db->mysqli->query($sql);
            
          }

          if($count == 1) {
            $db->modify($keys[0]->table, $fields, $originalwhere);
          }
          $db->modify($keys[0]->table, $data, $where);
          unset($_SESSION['originalKeys']);
          header("Location: forms.php");
        
        } else {
          warnings($keys, $count);
        }
      }
      break;
    
    case 'delete':
      # code...
      if($check) {
        $db->delete($keys[0]->table, $where);
        unset($_SESSION['originalKeys']);
        header("Location: forms.php");
      } else {
         warnings($keys, $count);
      }

      break;
    
    case 'addNew':
      # code...
      $results = $db->select($keys[0]->table, "*", $where);
      if(empty($results["{$keys[0]->field}"])) {
        $db->insert($keys[0]->table, $data);
        unset($_SESSION['originalKeys']);
        header("Location: forms.php");
      } else {
        warnings($keys, $count);
      }

      break;
    
    default:
      # code...
      break;
  }

}

function warnings(&$keys, $count) {
  if($count === 1) {
    $keys[0]->error = "Warning id already in use!";
    $keys[0]->has_warning();

  } elseif ($count === 2) {
    $keys[0]->error = "Warning this Part and Color combination is already in use!";

  } elseif ($count === 3) {
    $keys[0]->error = "Warning this Set, Part and Color combination is already in use!";

  } else {
    $keys[0]->error = "Warning id already in use!";
    $keys[0]->has_warning();
  }
}
?>