<?php
$db = new mysqli('localhost', 'c2230a03', 'c2230a03', 'c2230a03proj');
 
if (mysqli_connect_errno()) {
 //echo 'Error: Could not connect to the database..';
 exit;
}
 
if (!empty($_SERVER['REMOTE_ADDR'])) {
 $ip = $_SERVER['REMOTE_ADDR'];
 $host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
 $referrer = $_SERVER['HTTP_REFERER'];
 $agent = $_SERVER["HTTP_USER_AGENT"];
 $date = date("Y-m-d",time());
 $time = date("H:i:s",time());
 
 $sql = "INSERT INTO stats (ip,host,referrer,agent,date,time) 
 VALUES ('$ip','$host','$referrer','$agent', '$date','$time');";
 
 if (!$db->query($sql)) {
 //echo $mysqli->error;
 }
  
 $db->close();
}

header( 'Content-type: image/gif' );
echo chr(71).chr(73).chr(70).chr(56).chr(57).chr(97).
chr(1).chr(0).chr(1).chr(0).chr(128).chr(0).
chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).
chr(33).chr(249).chr(4).chr(1).chr(0).chr(0).
chr(0).chr(0).chr(44).chr(0).chr(0).chr(0).chr(0).
chr(1).chr(0).chr(1).chr(0).chr(0).chr(2).chr(2).
chr(68).chr(1).chr(0).chr(59);
?>