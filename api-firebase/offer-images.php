<?php
header('Access-Control-Allow-Origin: *');
session_start();
include '../includes/crud.php';
include_once('../includes/variables.php');
include_once('../includes/custom-functions.php');  
$fn = new custom_functions;
$permissions = $fn->get_permissions($_SESSION['id']);
include_once('verify-token.php');
$db = new Database();
$db->connect();
include 'send-email.php';
$response = array();

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
// if(!isset($_POST['ajax-call']) && $_POST['ajax-call']!=1){
//     if(!verify_token()){
//         return false;
//     }
// }

if ((isset($_POST['add-image'])) && ($_POST['add-image'] == 1)) {
	if($permissions['new_offers']['create']==0){
		$response["message"] = "<p class='alert alert-danger'>No tienes permiso para crear nuevas ofertas.</p>";
		echo json_encode($response);
		return false;
	}
	$image = $_FILES['image']['name'];
	$image_error = $_FILES['image']['error'];
	$image_type = $_FILES['image']['type'];
	
	// create array variable to handle error
	$error = array();
	// common image file extensions
	$allowedExts = array("gif", "jpeg", "jpg", "png");
	
	// get image file extension
	error_reporting(E_ERROR | E_PARSE);
	$extension = end(explode(".", $_FILES["image"]["name"]));
	if($image_error > 0){
		$error['image'] = " <span class='label label-danger'>No subio</span>";
	}else if(!(($image_type == "image/gif") || 
		($image_type == "image/jpeg") || 
		($image_type == "image/jpg") || 
		($image_type == "image/x-png") ||
		($image_type == "image/png") || 
		($image_type == "image/pjpeg")) &&
		!(in_array($extension, $allowedExts))){
			$error['image'] = " <span class='label label-danger'>¡El tipo de imagen debe ser jpg, jpeg, gif o png!</span>";
	}
	if( empty($error['image']) ){
		
		$mt = explode(' ', microtime());
		$microtime = ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));
		$file = preg_replace("/\s+/", "_", $_FILES['image']['name']);
		
		$image = $microtime.".".$extension;
		
		if (!is_dir($target_path)) {
            mkdir('../upload/offers/', 0777, true);
        }
		$upload = move_uploaded_file($_FILES['image']['tmp_name'], '../upload/offers/'.$image);
		
		
		$upload_image = 'upload/offers/'.$image;
		$sql = "INSERT INTO `offers`(`image`) VALUES ('$upload_image')";
		$db->sql($sql);
		$res = $db->getResult();
		$sql="SELECT id FROM `offers` ORDER BY id DESC";
		$db->sql($sql);
		$res = $db->getResult();
		$response["message"] = "<p class='alert alert-success'>Imagen subida correctamente</p>";
		$response["id"] = $res[0]['id'];
	}else{
		$response["message"] = "<p class='alert alert-danger'>No se pudo cargar la imagen. Inténtalo de nuevo</p>";
	}
	echo json_encode($response);
}
if(isset($_GET['type']) && $_GET['type'] != '' && $_GET['type'] == 'delete-offer') {
	if($permissions['new_offers']['delete']==0){
		echo 2;
		return false;
	}
    
    
    $id		= $_GET['id'];
    $image 	= $_GET['image'];
	
	if(!empty($image))
		unlink('../'.$image);
	
	$sql = 'DELETE FROM `offers` WHERE `id`='.$id;
	if($db->sql($sql)){
		echo 1;
	}else{
		echo 0;
	}
}
if(isset($_POST['get-offer-images'])) {
    if(!verify_token()){
        return false;
    }
	$sql = 'select * from offers order by id desc';
	$db->sql($sql);
	$result =$db->getResult();
	$response = $temp = $temp1 = array();
	if(!empty($result)){
    	$response['error'] = false;
    	foreach($result as $row){
    		$temp['image'] = DOMAIN_URL.$row['image'];
    		$temp1[] = $temp;
    	}
    	$response['data'] = $temp1;
	}else{
	    $response['error'] = true;
	    $response['message'] = "Aún no se han subido imágenes de oferta!";
	}
	print_r(json_encode($response));
}
?>