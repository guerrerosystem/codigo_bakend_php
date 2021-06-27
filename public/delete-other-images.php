<?php 
include_once('../includes/crud.php');
$db = new Database();
$db->connect();
date_default_timezone_set('Asia/Kolkata');
if(isset($_POST['i']) && isset($_POST['pid'])){
	$i = $_POST['i'];
	$pid = $_POST['pid'];
	$sql = "SELECT other_images FROM products WHERE id =".$pid;
    $db->sql($sql);
    $res = $db->getResult();
    foreach($res as $row)
    	$other_images = $row['other_images']; 
	$other_images = json_decode($other_images); 
	unlink("../".$other_images[$i]); 
	unset($other_images[$i]);
	$other_images= json_encode(array_values($other_images));
	
	
	$sql = "UPDATE `products` set `other_images`='".$other_images."' where id=".$pid;
	if($db->sql($sql))
		echo 1;
	else 
		echo 0;
}
?>