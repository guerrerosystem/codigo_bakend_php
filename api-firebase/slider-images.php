<?php 
session_start();
include '../includes/crud.php';
include_once('../includes/variables.php');
include_once('../includes/custom-functions.php');


header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
//header("Content-Type: multipart/form-data");
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('Asia/Kolkata');


$fn = new custom_functions;
$permissions = $fn->get_permissions($_SESSION['id']);
include_once('verify-token.php');
$db = new Database();
$db->connect();
include 'send-email.php';
$response = array();
// print_r($_GET['accesskey']);

if(!isset($_POST['accesskey'])){
    if(!isset($_GET['accesskey'])){
        $response['error'] = true;
    	$response['message'] = "La clave de acceso no es válida o no se pasó!";
    	print_r(json_encode($response));
    	return false;
    }
}

if(isset($_POST['accesskey'])){
    $accesskey = $_POST['accesskey'];
}else{
    $accesskey = $_GET['accesskey'];
}

if ($access_key != $accesskey) {
	$response['error'] = true;
	$response['message'] = "clave de acceso inválida!";
	print_r(json_encode($response));
	return false;
}

if ((isset($_POST['add-image'])) && ($_POST['add-image'] == 1)) {
	if($permissions['home_sliders']['create']==0){
		$response["message"] = "<p class='alert alert-danger'>No tienes permiso para crear el control deslizante de inicio.</p>";
		echo json_encode($response);
		return false;
	}
    // print_r($_POST);
	$image = $_FILES['image']['name'];
	$image_error = $_FILES['image']['error'];
	$image_type = $_FILES['image']['type'];
	$type = $db->escapeString($_POST['type']);
	$id = ($type != 'default')?$_POST[$type]:"0";
// 	echo $id;
	
	// create array variable to handle error
	$error = array();
	// common image file extensions
	$allowedExts = array("gif", "jpeg", "jpg", "png");
	
	// get image file extension
	error_reporting(E_ERROR | E_PARSE);
	$extension = end(explode(".", $_FILES["image"]["name"]));
	if($image_error > 0){
		$error['image'] = " <span class='label label-danger'>No subido!</span>";
	}else if(!(($image_type == "image/gif") || 
		($image_type == "image/jpeg") || 
		($image_type == "image/jpg") || 
		($image_type == "image/x-png") ||
		($image_type == "image/png") || 
		($image_type == "image/pjpeg")) &&
		!(in_array($extension, $allowedExts))){
			$error['image'] = " <span class='label label-danger'>Tipo de imagen debe jpg, jpeg, gif o png!</span>";
	}
	if( empty($error['image']) ){
		// create random image file name
		$mt = explode(' ', microtime());
		$microtime = ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));
		$file = preg_replace("/\s+/", "_", $_FILES['image']['name']);
		
		$image = $microtime.".".$extension;
		// upload new image
		$upload = move_uploaded_file($_FILES['image']['tmp_name'], '../upload/slider/'.$image);
		
		// insert new data to menu table
		$upload_image = 'upload/slider/'.$image;
		$sql = "INSERT INTO `slider`(`image`,`type`, `type_id`) VALUES ('$upload_image','".$type."','".$id."')";
// 		echo $sql;
		// echo "a";
		// echo $sql;
		$db->sql($sql);
		$res = $db->getResult();
		$sql="SELECT id FROM `slider` ORDER BY id DESC";
		$db->sql($sql);
		$res = $db->getResult();
		$response["message"] = "<span class='label label-success'>Imagen subida correctamente!</span>";
		$response["id"] = $res[0]['id'];
	}else{
		$response["message"] = "<span class='label label-daner'>No se pudo cargar la imagen. Inténtalo de nuevo!</span>";
	}
	echo json_encode($response);
}
if(isset($_GET['type']) && $_GET['type'] != '' && $_GET['type'] == 'delete-slider') {
	if($permissions['home_sliders']['delete']==0){
		echo 2;
		return false;
	}
    
    // print_r($_GET);
    $id		= $_GET['id'];
    $image 	= $_GET['image'];
	
	if(!empty($image))
		unlink('../'.$image);
	
	$sql = 'DELETE FROM `slider` WHERE `id`='.$id;
	if($db->sql($sql)){
		echo 1;
	}else{
		echo 0;
	}
}
if(isset($_POST['get-slider-images'])) {
    if(!verify_token()){
        return false;
    }
	$sql = 'select * from slider order by id desc';
	$db->sql($sql);
	$result =$db->getResult();
	$response = $temp = $temp1 = array();
	if(!empty($result)){
    	$response['error'] = false;
    	foreach($result as $row){
    		$name = "";
    		if($row['type'] == 'category'){
    		    $sql = 'select `name` from category where id = '.$row['type_id'].' order by id desc';
    		    $db->sql($sql);
    		    $result1 = $db->getResult();
    		    $name = (!empty($result1[0]['name']))?$result1[0]['name']:"";
    		}
    		if($row['type'] == 'product'){
    		    $sql = 'select `name` from products where id = '.$row['type_id'].' order by id desc';
    		    $db->sql($sql);
    		    $result1 = $db->getResult();
    		    $name = (!empty($result1[0]['name']))?$result1[0]['name']:"";
    		}
    		
    		$temp['type'] = $row['type'];
    		$temp['type_id'] = $row['type_id'];
    		$temp['name'] = $name;
    		$temp['image'] = DOMAIN_URL.$row['image'];
    		$temp1[] = $temp;
    	}
    	$response['data'] = $temp1;
	}else{
	    $response['error'] = true;
	    $response['message'] = "Aún no se han subido imágenes deslizantes!";
	}
	print_r(json_encode($response));
}
?>