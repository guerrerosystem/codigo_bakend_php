<?php 
/*login*/
    header('Access-Control-Allow-Origin: *');
    header("Content-Type: application/json");
    header("Expires: 0");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    
session_start();
include '../includes/crud.php';
include_once('../includes/variables.php');
include_once('verify-token.php');
    $db = new Database();
    $db->connect();
    date_default_timezone_set('Asia/Kolkata');
   /* accesskey:90336
    mobile:9974692496
    password:36652
    status:1   // 1 - Active & 0 Deactive */
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

if(isset($_POST['mobile']) && $_POST['mobile'] != '' && isset($_POST['password']) && $_POST['password'] != '') {

    // get username and password
    $mobile    = $db->escapeString($_POST['mobile']);
    $password    = $db->escapeString($_POST['password']);
    // set time for session timeout
    // $currentTime = time() + 25200;
    // $expired     = 3600;
	$response = array();
    // if username and password is not empty, check in database
    if (!empty($mobile) && !empty($password)) {
        // change username to lowercase
        // $mobile  = strtolower($mobile);
        // encript password to sha256
        $password  = md5($password);
        // get data from user table
        $sql_query = "SELECT *,(SELECT name FROM area a WHERE a.id=u.area) as area_name,(SELECT name FROM city c WHERE c.id=u.city) as city_name FROM `users` u WHERE `mobile` = '".$mobile."' AND `password` ='".$password."'";
        $db->sql($sql_query);
        $result=$db->getResult();
		if ($db->numRows($result) > 0) {
			
			$fcm_id = (isset($_POST['fcm_id']) && !empty($_POST['fcm_id']))?$db->escapeString($_POST['fcm_id']):"";
			if(!empty($fcm_id)){
			    $sql = "update users set `fcm_id` ='".$fcm_id."' where id = ".$result[0]['id'];
			    $db->sql($sql);
			}
			
			foreach($result as $row) {
				$response['error']     = false;
				$response['user_id'] = /*$_SESSION['user_id'] */  $row['id'];
				$response['name'] /*= $_SESSION['name']*/    = $row['name'];
				$response['email'] /*= $_SESSION['email']*/    = $row['email'];
				$response['mobile'] /*= $_SESSION['user']*/    = $row['mobile'];
				$response['country_code'] = $row['country_code'];
				$response['dob'] = $row['dob'];
				$response['balance'] = $row['balance'];
				$response['city_id'] = !empty($row['city'])?$row['city']:'';
				$response['city_name'] = !empty($row['city_name'])?$row['city_name']:'';
				$response['area_id'] = !empty($row['area'])?$row['area']:'';
				$response['area_name'] = !empty($row['area_name'])?$row['area_name']:'';
				$response['street']     = $row['street'];
				$response['pincode']     = $row['pincode'];
				$response['referral_code']     = $row['referral_code'];
				$response['friends_code']     = $row['friends_code'];
				$response['latitude']     = (!empty($row['latitude']))?$row['latitude']:'0';
				$response['longitude']     = (!empty($row['longitude']))?$row['longitude']:'0';
				$response['apikey']     = $row['apikey'];
				$response['status']     = $row['status'];
				$response['created_at']     = $row['created_at'];
				// $_SESSION['timeout'] = $currentTime + $expired;
            }
			$response['message'] = "Logueado exitosamente.";
			// echo json_encode($response);
		}else{
			$response['error']     = true;
			$response['message']   = "Móvil o contraseña no válidos!";
			// echo json_encode($response);
		}
    }
    print_r(json_encode($response));
} else{
    // check whether $username is empty or not
    // if (empty($username) or ($password)) {
        $response['message'] = "El móvil y la contraseña deben llenarse";
        // echo json_encode($response);
    // }
    // check whether $password is empty or not
    // if (empty($password)) {
    //     $response['message'] = "Password should be filled";
    //     // echo json_encode($response);
    // }
        print_r(json_encode($response));

}
$db->disconnect();
?>