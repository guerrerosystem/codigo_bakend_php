<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
    
include('../includes/crud.php');
include('../includes/variables.php');
include_once('verify-token.php');
$db = new Database();
$db->connect();
$response = array();

if(!isset($_POST['accesskey']) || $access_key != $_POST['accesskey']){
    $response['error']= true;
    $response['message'] = "clave de acceso inválida";
    print_r(json_encode($response));
    return false;
}
if(!verify_token()){
    return false;
}
$settings = $setting = array();

if(isset($_POST['settings']) ) {
    if(isset($_POST['get_payment_methods'])){
        $sql = "select value from `settings` where `variable`='payment_methods'";
        $db->sql($sql);
        $res = $db->getResult();
        if(!empty($res)){
            $settings['error'] = false;
            $settings['payment_methods'] = json_decode($res[0]['value']);
            print_r(json_encode($settings));
        }else{
            $settings['error'] = true;
            $settings['settings'] = "No se encontraron configuraciones!";
            $settings['message'] = "Algo salió mal!";
            print_r(json_encode($settings));
        }
    }if(isset($_POST['get_privacy'])){
        $sql = "select value from `settings` where variable='privacy_policy'";
        $db->sql($sql);
        $res = $db->getResult();
        if(!empty($res)){
            $settings['error'] = false;
            $settings['privacy'] = $res[0]['value'];
            print_r(json_encode($settings));
            
        }else{
            $settings['error'] = true;
            $settings['settings'] = "No se encontraron configuraciones!";
            $settings['message'] = "Algo salió mal!";
            print_r(json_encode($settings));
        }
    }
    if(isset($_POST['get_terms'])){
        $sql = "select value from `settings` where variable='terms_conditions'";
        $db->sql($sql);
        $res = $db->getResult();
        if(!empty($res)){
            $settings['error'] = false;
            $settings['terms'] = $res[0]['value'];
            print_r(json_encode($settings));
            
        }else{
            $settings['error'] = true;
            $settings['settings'] = "No se encontraron configuraciones!";
            $settings['message'] = "Algo salió mal!";
            print_r(json_encode($settings));
        }
    }
    if(isset($_POST['get_logo'])){
        $sql = "select value from `settings` where variable='Logo' OR variable='logo'";
        $db->sql($sql);
        $res = $db->getResult();
        if(!empty($res)){
            $settings['error'] = false;
            $settings['logo'] = DOMAIN_URL.$res[0]['value'];
            print_r(json_encode($settings));
            
        }else{
            $settings['error'] = true;
            $settings['settings'] = "No se encontraron configuraciones!";
            $settings['message'] = "Algo salió mal!";
            print_r(json_encode($settings));
            
        }
    }
    if(isset($_POST['get_contact'])){
        $sql = "select value from `settings` where variable='contact_us'";
        $db->sql($sql);
        $res = $db->getResult();
        if(!empty($res)){
            $settings['error'] = false;
            $settings['contact'] =$res[0]['value'];
            print_r(json_encode($settings));
            
        }else{
            $settings['error'] = true;
            $settings['settings'] = "No se encontraron configuraciones!";
            $settings['message'] = "Algo salió mal!";
            print_r(json_encode($settings));
            
        }
    }
    if(isset($_POST['get_about_us'])){
        $sql = "select value from `settings` where variable='about_us'";
        // echo $sql;
        $db->sql($sql);
        $res = $db->getResult();
        if(!empty($res)){
            $settings['error'] = false;
            $settings['about'] =$res[0]['value'];
            print_r(json_encode($settings));
            
        }else{
            $settings['error'] = true;
            $settings['settings'] = "No se encontraron configuraciones!";
            $settings['message'] = "Algo salió mal!";
            print_r(json_encode($settings));
            
        }
    }
        if(isset($_POST['get_timezone'])){
        $sql = "select value from `settings` where variable='system_timezone'";
        $db->sql($sql);
        $res = $db->getResult();
        if(!empty($res)){
            $settings['error'] = false;
            $settings['settings'] = json_decode($res[0]['value'],1);
            $settings['settings']['currency'] = "₹";
            print_r(json_encode($settings));
            
        }else{
            $settings['error'] = true;
            $settings['settings'] = "No se encontraron configuraciones!";
            $settings['message'] = "Algo salió mal!";
            print_r(json_encode($settings));
            
        }
    }
    if(isset($_POST['get_fcm_key'])){
        $sql = "select value from `settings` where variable='fcm_server_key'";
        $db->sql($sql);
        $res = $db->getResult();
        if(!empty($res)){
            $settings['error'] = false;
            $settings['fcm'] =$res[0]['value'];
            print_r(json_encode($settings));
            
        }else{
            $settings['error'] = true;
            $settings['settings'] = "No se encontraron configuraciones!";
            $settings['message'] = "Algo salió mal!";
            print_r(json_encode($settings));
            
        }
    }
}else if(isset($_POST['get_time_slots'])){
    $sql = "select * from `time_slots` where status=1";
    $db->sql($sql);
    $res = $db->getResult();
    if(!empty($res)){
        $settings['error'] = false;
        $settings['time_slots'] = $res;
        print_r(json_encode($settings));            
    }else{
        $settings['error'] = true;
        $settings['time_slots'] = null;
        $settings['message'] = "No se encontraron intervalos de tiempo activos!";
        print_r(json_encode($settings));
        
    }
} else {
    die('Algo mal!!.');
}
$db->disconnect();
?>