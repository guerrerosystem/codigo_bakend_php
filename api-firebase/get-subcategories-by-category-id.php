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
		catrgory_id:28 */
	if(!verify_token()){
		return false;
	}
	if(isset($_POST['accesskey']) && isset($_POST['category_id'])) {
	    
		$access_key_received = $_POST['accesskey'];	
		$category_id = $db->escapeString($_POST['category_id']);	
		if($access_key_received == $access_key){
			// get all category data from category table
			$sql_query = "SELECT * 
				FROM subcategory WhERE category_id='".$category_id."'
				ORDER BY id ASC ";
			$db->sql($sql_query);
			$res=$db->getResult();
			$db->disconnect(); 
			// $categories = array();
			if (!empty($res)) {
				for($i=0;$i<count($res);$i++){
					// $categories[] = array('category'=>$i);
					$res[$i]['image'] = (!empty($res[$i]['image']))?DOMAIN_URL.''.$res[$i]['image']:'';
				}
				$response['error'] = "false";
			$response['data'] = $res;
		}else{
			$response['error'] = "true";
			$response['message'] = "Datos no encontrados!";
		}
		print_r(json_encode($response));
			// foreach($res as $row) {
			// 	$categories[] = array('category'=>$row);
			// }
			// create json output
			// $output = json_encode(array('data' => $categories));
		}else{
			die('accesskey is incorrect.');
		}
	} else {
		die('accesskey and category id are required.');
	}
	//Output the output.
	// echo $output;
?>