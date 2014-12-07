<?php
////  Used by "form_colorpart.php" and "form_setpart.php"

require_once '../classes/class.DB.php';
require_once '../../libraries/array_column/array_column.php';

$db = new DB();
//  data arrays
$outputData  = array();

if(isset($_POST['set']) && $_POST['set'] === 'Lego_Set') {
  $tableSet = htmlspecialchars(trim($_POST['set']));
  $setFields = 'set_id';

  $records = $db->select($tableSet, $setFields);

  $IdValues = array_column($records, $setFields);

  $set_array   = array();
  foreach ($IdValues as $value) {
    $item = array();
    $item['label'] = $value;
    $item['title'] = $value;
    $item['value'] = $value;
    array_push($set_array, $item);
    unset($item);
  }
  $outputData['set'] = $set_array;
  unset($records, $IdValues, $set_array);
}

if(isset($_POST['part']) && $_POST['part'] === 'Lego_Part') {
  $tablePart = htmlspecialchars(trim($_POST['part']));
  $partFields = 'part_id';

  $records = $db->select($tablePart, $partFields);

  $IdValues = array_column($records, $partFields);

  $part_array  = array();
  foreach ($IdValues as $value) {
    $item = array();
    $item['label'] = $value;
    $item['title'] = $value;
    $item['value'] = $value;
    array_push($part_array, $item);
    unset($item);
  }
  $outputData['part'] = $part_array;
  unset($records, $IdValues, $part_array);
}

if(isset($_POST['color']) && $_POST['color'] === 'Lego_Color') {
  $tableColor = htmlspecialchars(trim($_POST['color']));
  $colorFields = 'color_id, color_name';

  $records = $db->select($tableColor, $colorFields);

  $IdValues = array_column($records, 'color_id');
  $nameValues = array_column($records, 'color_name');

  $color_array = array();
  for($i = 0; $i < count($IdValues); $i++) {
    $item = array();
    $item['label'] = $nameValues[$i];
    $item['title'] = $nameValues[$i];
    $item['value'] = $IdValues[$i];
    array_push($color_array, $item);
    unset($item);
  }
  $outputData['color'] = $color_array;
  unset($records, $IdValues, $nameValues, $color_array);
}

echo json_encode($outputData);

?>