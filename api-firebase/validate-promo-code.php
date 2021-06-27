<?php session_start();
header('Access-Control-Allow-Origin: *');
include '../includes/crud.php';
include '../includes/variables.php';
include_once('verify-token.php');
$db=new Database();
$db->connect();
include_once('../includes/custom-functions.php');
$fn = new custom_functions;
$config = $fn->get_configurations();
	if(isset($config['system_timezone']) && isset($config['system_timezone_gmt'])){
		date_default_timezone_set($config['system_timezone']);
		$db->sql("SET `time_zone` = '".$config['system_timezone_gmt']."'");
	}else{
	date_default_timezone_set('Asia/Kolkata');
	$db->sql("SET `time_zone` = '+05:30'");
}

$response = array();
$accesskey = isset($_POST['accesskey']) && $_POST['accesskey']!=''?$_POST['accesskey']:'';
// echo $access_key;
if(empty($accesskey)){
    $response['error']= true;
	$response['message']="clave de acceso requerida";
	print_r(json_encode($response));
	return false;
}
if($access_key != $accesskey){
	$response['error']= true;
	$response['message']="clave de acceso inválida";
	print_r(json_encode($response));
	return false;
}
if(!verify_token()){
    return false;
}
	

if (isset($_POST['validate_promo_code']) && $_POST['validate_promo_code'] == 1) {

	if ((isset($_POST['user_id']) && $_POST['user_id'] != '') && (isset($_POST['promo_code']) && $_POST['promo_code'] != '') && (isset($_POST['total']) && $_POST['total'] != '') ) {
    	$user_id = $db->escapeString($_POST['user_id']);
        $promo_code = $db->escapeString($_POST['promo_code']);
        $total = $db->escapeString($_POST['total']);
        $response=$fn->validate_promo_code($user_id,$promo_code,$total);
        print_r(json_encode($response));
        return false;
	}else{
	$response['error'] = true;
	$response['message'] = "Ingrese la identificación de usuario, el código de promoción y el total.";
	echo json_encode($response);
	return false;
	}
}

?>