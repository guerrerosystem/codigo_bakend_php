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

if(!isset($_POST['accesskey']) || $access_key != $_POST['accesskey']){
    $response['error']= true;
    $response['message']="clave de acceso inválida";
    print_r(json_encode($response));
    return false;
}
if(!verify_token()){
	return false;
}

if(isset($_POST['get_user_data']) && $_POST['get_user_data'] != '') {
    

    // get username and password

    // if username and password is not empty, check in database
    if(isset($_POST['user_id']) && $_POST['user_id'] != '') {
        $id    = $db->escapeString($_POST['user_id']);
        $response = array();
        $sql_query = "SELECT *,(SELECT name FROM area a WHERE a.id=u.area) as area_name,(SELECT name FROM city c WHERE c.id=u.city) as city_name FROM `users` u WHERE u.id=".$id;
        $db->sql($sql_query);
        $result=$db->getResult();
        if ($db->numRows($result) > 0) {
            
            foreach($result as $row) {
                $response['error']     = false;
                $response['user_id'] = $_SESSION['user_id']    = $row['id'];
                $response['name'] /*= $_SESSION['name']*/    = $row['name'];
                $response['email'] /*= $_SESSION['email']*/    = $row['email'];
                $response['mobile'] /*= $_SESSION['user']*/    = $row['mobile'];
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
                $response['apikey']     = $row['apikey'];
                $response['status']     = $row['status'];
                $response['created_at']     = $row['created_at'];
                // $_SESSION['timeout'] = $currentTime + $expired;
            }
    }else{
            $response['error']     = true;
            $response['message']   = "los datos no existens!";
    }
    }else{
            $response['error']     = true;
            $response['message']   = "identificación de usuario requerida";
    }
    print_r(json_encode($response));
}
$db->disconnect(); 
?>