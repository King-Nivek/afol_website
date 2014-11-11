<?php

  //  Class DB
  //
  //  Use:
  //  Parameter(s): String:$host, String:$user, String:$password, String:$dbName
  //

  class DB {

    protected $host     = "localhost";
    protected $user     = "c2230a03";
    protected $password = "c2230a03";
    protected $dbName   = "c2230a03proj";

    public $mysqli;

    /////  Constructors
    ///////////////////////////////////
    
    //  __construct Function
    //
    //  Use:  construct a new MySQLi object
    //  Parameter(s):  
    //  Returns:  
    //
    public function __construct() {
      $this->mysqli = new mysqli($this->host, 
                                 $this->user, 
                                 $this->password, 
                                 $this->dbName
                                );
      
      if($this->mysqli->connect_errno) {
        die($sql . ":\n" . $this->mysqli->connect_error);
      }
    }//  End __construct  ======================================================

    /////  Functions
    ///////////////////////////////////

    //  buildAssocArray Function
    //
    //  Use:  
    //  Parameter(s):  
    //  Returns:  
    //
    public function buildAssocArray($result) {
      $result_array = array();
      
      if($result->num_rows > 1) {
        while($row = $result->fetch_assoc()) {
          array_push($result_array, $row);
        }
        return $result_array;
      } else {
        return $result->fetch_assoc();
      }
    }//  End buildAssocArray  ==================================================

    //  select Function
    //
    //  Use:  
    //  Parameter(s):  
    //  Returns:  
    //
    public function select($table, $fields, $where) {
      $sql = "SELECT   $fields
                FROM   $table 
                WHERE  $where";

      $result = $this->mysqli->query($sql);

      if($this->mysqli->errno) {
        die($sql . ":\n" . $this->mysqli->error);
      }

      return $this->buildAssocArray($result);
    }//  End select  ===========================================================

    //  insert Function
    //
    //  Use:  
    //  Parameter(s):  
    //  Returns:  
    //
    public function insert($table, $data) {
      
      if(is_array($data)) {
        
        $columns = "";
        $values = "";
      
        foreach ($data as $column => $value) {
          $columns .= ($columns == "") ? "" : ", ";
          $columns .= $column;
          $values  .= ($values == "") ? "" : ", ";
          $values  .= $value;
        }
        $sql = "INSERT INTO $table ($columns) 
                  VALUES ($values)";

        $this->mysqli->query($sql) or die($this->mysqli->error .":\n\n $sql");
        
        return $this->mysqli->insert_id;

      } else {
        $sql = "INSERT INTO $table 
                  VALUES($data)";

        $this->mysqli->query($sql) or die($this->mysqli->error .":\n\n $sql");

        return $this->mysqli->insert_id;
      }
    }//  End insert  ===========================================================

    //  modify Function
    //
    //  Use:  
    //  Parameter(s):  
    //  Returns:  
    //
    public function modify($table, $data, $where) {
      foreach($data as $column => $value) {
        $sql = "UPDATE $table SET $column = $value WHERE $where";
        $this->mysqli->query($sql) or die($this->mysqli->error . ":\n\n" . $sql);
      }
      return true;
    }//  End modify  ============================================================

    //  delete Function
    //
    //  Use:  
    //  Parameter(s):  
    //  Returns:  
    //
    public function delete($table, $where) {
      $sql = "DELETE FROM $table
                WHERE $where";
      return ($this->mysqli->query($sql));
    }//  End delete  ============================================================

  }//  End CLASS  ===============================================================
?>