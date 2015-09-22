<?php
session_start();

/* ActiveRecord Library */
require_once("adodb5/adodb-exceptions.inc.php"); 
require_once('adodb5/adodb.inc.php');
require_once('adodb5/adodb-active-record.inc.php');

$conn = NewADOConnection('mysqli');
$conn->Connect("localhost", "root", "", "ippt");
ADOdb_Active_Record::SetDatabaseAdapter ($conn);

function ExecuteQuery($query,$parameters = Array()){

  global $conn;
  
  $returnArray = Array();
  $result = false;
  
  if (empty($query)) return $returnArray;
  try {
    $result = &$conn->Execute($query,$parameters);
  } catch (exception $e) {  
    $returnArray[0] = false;
    $returnArray[1] = $e->getMessage();         
  }
  
  if ($result && $result->RecordCount() >= 0){ 
    $returnArray[0] = true;
    $returnArray[1] = $result;
  } else {
    $returnArray[0] = false;
    $returnArray[1] = 'ErrMSG:'.$conn->ErrorNo().' - '.$conn->ErrorMsg().$query;
  }
  
  return $returnArray;
  
}
?>