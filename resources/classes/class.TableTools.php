<?php

class TableTools {


  //  buildTable  --  Used in index.php
  //
  //  Builds the whole html table, listing all records from the contacts Table.
  //    Use to insert the table into the html at a location.
  //  Parameters:   none
  //  Return:   Nothing
  //  Uses Global Variable:  mysqli:$db
  public function buildTable($table, $fullQuery = "") {
    global $db;
    $fields = "*";
    $where = "";
    $records = $db->select($table, $fields, $where, $fullQuery);
    
    echo '<div class="panel panel-primary">',
         '<div class="panel-heading ">',
         '<h3 class="panel-title">'.$table.'</h3>',
         '</div>';
    ////  write complete table

    //  This echo builds the start of the table and the headings.
    echo '<div class="table-responsive">',
         '<table class="table table-hover">',
         '<thead>',
         '<tr>';
    foreach ($records[0] as $key => $value) {
      echo "<th>$key</th>";
    }
    echo '</tr>',
         ' </thead>';

    //  If there are records...
 
    echo '<tbody>';
    // Output the data of each row.
    for($i = 0; $i < count($records); $i++) {
      echo '<tr>';
      foreach($records[$i] as $value) {
        echo "<td> $value</td>";
      }
      echo '</tr>';
    }

    //  Close the table tags
    echo '</tbody></table></div></div>';

    $db->mysqli->close();
  }//  End buildTable function  ==================================================
}
?>