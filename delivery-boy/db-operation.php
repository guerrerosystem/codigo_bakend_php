<?php
session_start();
include('../includes/crud.php');
$db = new Database();
$db->connect();
$db->sql("SET NAMES 'utf8'");

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

if(isset($_POST['update_delivery_boy']) && isset($_POST['delivery_boy_id'])){
    $id = $db->escapeString($_POST['delivery_boy_id']);
    if(isset($_POST['old_password']) && $_POST['old_password'] != ''){
        $old_password = md5($_POST['old_password']);
        $sql = "SELECT `password` FROM delivery_boys WHERE id=".$id;
        $db->sql($sql);
        $res = $db->getResult();
        if($res[0]['password'] != $old_password){
            echo "<label class='alert alert-danger'>La contraseña anterior no coincide.</label>";
            return false;
        }
    }
    if($_POST['update_password'] !='' && empty(trim($_POST['old_password']))){
        echo "<label class='alert alert-danger'>Por favor ingrese la contraseña anterior.</label>";
        return false;
    }
    $name = $db->escapeString($_POST['update_name']);
    $password = !empty($_POST['update_password'])?$db->escapeString($_POST['update_password']):'';
    // $password = '12345678';
    $address = $db->escapeString($_POST['update_address']);
    $password = !empty($password)?md5($password):'';
    if(!empty($password)){
		$sql = "Update delivery_boys set `name`='".$name."',password='".$password."',`address`='".$address."' where `id`=".$id;
    }else{
		$sql = "Update delivery_boys set `name`='".$name."',`address`='".$address."' where `id`=".$id;
    }
    if($db->sql($sql)){
        echo "<label class='alert alert-success'>Información actualizada con éxito.</label>";
    }else{
        echo "<label class='alert alert-danger'>¡Se produjo algún error! Inténtalo de nuevo.</label>";

    }
}

?>