<?php


 include "includes/crud.php";
 $db=new Database();
 $db->connect();
 $query="select gcm_regid from gcm_users ";
 $db->sql($query);
 $result=$db->getResult();
 $counter=0;
 foreach($result as $row){
 	$gcm_array[]=$row['gcm_regid'];
 	$counter++;
 }
$partition=array_chunk($gcm_array,1000);
$message = $_GET["message"];    
    include 'GCM.php';   
    $gcm = new GCM();
    //$registatoin_ids =  $rr;
    $message = array("price" => $message);
foreach($partition as $val){ 
    $result = $gcm->send_notification( $val , $message);
} 
?>