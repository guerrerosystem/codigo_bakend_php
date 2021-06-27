<?php 
header('Access-Control-Allow-Origin: *');
require_once '../includes/functions.php';
include_once('../includes/variables.php');
include_once('verify-token.php');

	
	$response = array(); 
	/* accesskey:90336
		user_id:5 
		token:227 */
	$accesskey=$_POST['accesskey'];

	if($access_key != $accesskey){
		$response['error']= true;
		$response['message']="clave de acceso inválida";
		print_r(json_encode($response));
		return false;
	}
	if(!verify_token()){
        return false;
    }
	
	if(isset($_POST['token']) && isset($_POST['user_id'])){

		$token = $_POST['token'];
		$user_id = $_POST['user_id'];

		$fn = new functions; 

		$result = $fn->registerDevice($user_id,$token);

		if($result == 0){
			$response['error'] = false; 
			$response['message'] = 'Dispositivo registrado con éxito';
		}elseif($result == 2){
			$response['error'] = true; 
			$response['message'] = 'Dispositivo ya registrado';
		}else{
			$response['error'] = true;
			$response['message']='Dispositivo no registrado';
		}
	}else{
		$response['error']=true;
		$response['message']='Solicitud no válida...';
	}

	echo json_encode($response);
?>