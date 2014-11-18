<?php
//  AJAX connection
require_once '../classes/class.DB.php';
require_once '../classes/class.TableTools.php';
//  Table record lists
if(isset($_POST['tableType'])) {
  $db = new DB();
  $table_id = $_POST['tableType'];
  $table_id = trim($table_id);
  $table_id = htmlspecialchars($table_id);
  $table_name = "";
  $form_type = "";

  // $list = new TableTools();
  $num_keys = 0;
  $fullQuery = "false";
  switch ($table_id) {
    case 'Lego_Set':
      $fullQuery = "SELECT set_id,
                           set_id       AS ID,
                           set_name     AS Name,
                           set_category AS Category,
                           set_id
                      FROM Lego_Set;";
      $table_name = "Lego Sets:";
      $num_keys = 1;
      $form_type = "form_sets.php";
      break;

    case 'Lego_Part':
      $fullQuery = "SELECT part_id,
                           part_id          AS ID,
                           part_category    AS Category,
                           part_size        AS Size,
                           part_description AS Description,
                           part_id
                      FROM Lego_Part";
      $table_name = "Lego Parts:";
      $num_keys = 1;
      $form_type = "form_parts.php";
      break;
    
    case 'Lego_Color':
      $fullQuery = "SELECT color_id,
                           color_id   AS ID,
                           color_name AS Name,
                           color_id
                      FROM Lego_Color;";
      $table_name = "Lego Colors:";
      $num_keys = 1;
      $form_type = "form_colors.php";
      break;
    
    case 'Lego_ColorPart':
      $fullQuery = "SELECT lcp.part_id,
                           lcp.color_id,
                           lcp.part_id AS 'Part ID',
                           lcr.color_name AS 'Color Name',
                           lcp.colorPart_qty AS Quantity
                      FROM Lego_ColorPart lcp 
                        INNER JOIN Lego_Color lcr 
                          ON lcp.color_id = lcr.color_id;";
      $table_name = "Lego Color of Parts:";
      $num_keys = 2;
      $form_type = "form_colorpart.php";
      break;    
   
    case 'Lego_SetPart':
      $fullQuery = "SELECT lsp.set_id,
                           lsp.part_id,
                           lsp.color_id,
                           lsp.set_id AS 'Set ID',
                           lst.set_name AS 'Set Name',
                           lsp.part_id AS 'Part ID',
                           lcr.color_name AS 'Color Name',
                           lsp.setPart_qty AS Quantity
                      FROM Lego_SetPart AS lsp 
                        INNER JOIN Lego_Set AS lst
                          ON lst.set_id = lsp.set_id
                        INNER JOIN Lego_Color AS lcr
                          ON lsp.color_id = lcr.color_id;";
      $table_name = "Lego Parts for Sets:";
      $num_keys = 3;
      $form_type = "form_setpart.php";
      break;    
 
    default:
      # code...
      break;
  }
  if($fullQuery != "false") {
    $html = buildTable($table_id, $table_name, $fullQuery, $num_keys);
    $json = array('html' => "$html", 'form_type' => "$form_type");
    echo json_encode($json);
  } else {
    exit;
  }
  
}
function buildTable($table_id, $table_name, $fullQuery, $num_keys) {
    global $db;
    $fields = "*";
    $where = "";
    $output = "";

    $header = array();
    $records = $db->select($table_name, $fields, $where, $fullQuery);
    
    ////  write complete table
    $output .= '<div class="panel panel-primary">'."\n"
            .  '<div class="panel-heading ">'."\n"
            .  '<h3 class="panel-title">'.$table_name.'</h3>'."\n"
            .  '</div>'."\n";

    //  This echo builds the start of the table and the headings.
    $output .= '<div class="table-responsive">'."\n"
            .  '<table class="table table-hover" id="'.$table_id.'">'."\n"
            .  '<thead>'."\n"
            .  '<tr>'."\n";
    //  Build Header Row
    $header = array_keys($records[0]);
    $output .= '<th><span class="glyphicon "></span></th>'."\n";
    for($i = $num_keys; $i < count($header); $i++) {
      $output .= "<th>{$header[$i]}</th>\n";
    }
    $output .= '</tr>'."\n"
            .  ' </thead>'."\n"
            .  '<tbody>'."\n";
  
    // Output the data of each row.

    for($i = 0; $i < count($records); $i++) {
      $key_values = array_splice($records[$i], 0, $num_keys);
      $output .= '<tr class="data-row" id="row'.$i.'" data-key="{';
      
      foreach($key_values as $key => $value) {
        $output .= "'$key':'$value',";
      }
      $output .= '}">'."\n";
      $output .= '<td><span class="glyphicon glyphicon-remove"></span></td>'."\n";
      foreach($records[$i] as $value) {
        $output .= "<td>$value</td>\n";
      }
      $output .= '</tr>'."\n";
      
    }

    //  Close the table tags
    $output .= '</tbody></table></div></div>'."\n";

    $db->mysqli->close();
    return $output;
  }//  End buildTable function  ================================================
?>