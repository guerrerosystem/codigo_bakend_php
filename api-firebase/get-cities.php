<?php
    header('Access-Control-Allow-Origin: *');
	header("Content-Type: application/json");
    header("Expires: 0");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
	
	include_once('../includes/crud.php');
	$db=new Database();
	$db->connect(); 
	include_once('../includes/variables.php');
	include_once('verify-token.php');
	/* accesskey:90336 
		city_id:24 */
	if(!verify_token()){
		return false;
	}
	if(isset($_POST['accesskey'])) {
		$access_key_received = $_POST['accesskey'];
		$city_id = (isset($_POST['city_id']))?$db->escapeString($_POST['city_id']):"";
		if($access_key_received == $access_key){
			if (empty($city_id)) {
				$sql = "SELECT * FROM city ORDER BY id ASC";
	
			}else{
			// get all category data from category table
				$sql = "SELECT * FROM city WHERE id = '".$city_id."'";	
			}
			$db->sql($sql);
			$res=$db->getResult();
			// $cities = array();
			// foreach($res  as $row) {
			// 	$cities[] = array('City'=>$row);
			// }			
			// // create json output
			if(!empty($res)){
				$response['error'] = false;
				$response['data'] = $res;
			}else{
				$response['error'] = true;
				$response['message'] = "Datos no encontrados!";
			}
			$output = json_encode($response);
			// print_r(json_encode($res));

		}else{
			die('la clave de acceso es incorrecta.');
		}
	} else {
		die('Se requiere clave de acceso.');
	}
	//Output the output.
	echo $output;
	$db->disconnect(); 
?>