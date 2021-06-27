<?php
session_start();
include('../includes/crud.php');
$db = new Database();
$db->connect();
$db->sql("SET NAMES 'utf8'");
$auth_username = $db->escapeString($_SESSION["user"]);

include_once('../includes/custom-functions.php');
$fn = new custom_functions;
$permissions = $fn->get_permissions($_SESSION['id']);
$config = $fn->get_configurations();
if(isset($config['system_timezone']) && isset($config['system_timezone_gmt'])){
    date_default_timezone_set($config['system_timezone']);
    $db->sql("SET `time_zone` = '".$config['system_timezone_gmt']."'");
}else{
    date_default_timezone_set('Asia/Kolkata');
    $db->sql("SET `time_zone` = '+05:30'");
}
function checkadmin($auth_username){
    $db = new Database();
    $db->connect();
    $db->sql("SELECT `username` FROM `admin` WHERE `username`='$auth_username' LIMIT 1");
    $res = $db->getResult();
    if(!empty($res)){
        
            return true;
        }
        else{
            return false;
        }
    }

if(isset($_POST['change_category'])){
    if($permissions['subcategories']['read']==1){
        if($_POST['category_id']==''){
            $sql = "SELECT * FROM subcategory";
        }else{
            $sql = "SELECT * FROM subcategory WHERE category_id=".$_POST['category_id'];
        }
    }else{
        echo "<option value=''>--Seleccionar subcategoría--</option>";
        return false;
    }
   
    $db->sql($sql);
    $res = $db->getResult();
    if(!empty($res)){
        foreach($res as $row){
            echo "<option value=".$row['id'].">".$row['name']."</option>";
        }
    }else{
        echo "<option value=''>--No se agrega ninguna subcategoría--</option>";
    }
}

if(isset($_POST['category'])){
    if($permissions['subcategories']['read']==1){
    if($_POST['category_id']==''){
        $sql = "SELECT * FROM subcategory";
    }else{
         $sql = "SELECT * FROM subcategory WHERE category_id=".$_POST['category_id'];
    }
   
    $db->sql($sql);
    $res = $db->getResult();
    if(!empty($res)){
        echo "<option value=''>Todos</option>";
        foreach($res as $row){
            echo "<option value=".$row['id'].">".$row['name']."</option>";
        }
    }else{
        echo "<option value=''>--No se agrega ninguna subcategoría--</option>";
    }
}else{
    echo "<option value=''>Todos</option>";
}
}

if(isset($_POST['find_subcategory'])){
   
    $sql = "SELECT * FROM subcategory WHERE category_id=".$_POST['category_id'];
    $db->sql($sql);
    $res = $db->getResult();
    if(!empty($res)){
        foreach($res as $row){
            echo "<option value=".$row['id'].">".$row['name']."</option>";
        }
    }else{
        echo "<option value=''>--No se agrega ninguna subcategoría--</option>";
    }
}

if(isset($_POST['delete_variant'])){
    $id=$_POST['id'];
    $sql="DELETE FROM product_variant WHERE id=".$id;
    $db->sql($sql);
}

if(isset($_POST['delete_order'])){
    $id=$_POST['id'];
    $db->delete($orders,$id==$id);
}

if(isset($_POST['system_configurations'])){    
    if($permissions['settings']['update']==0){
        echo '<label class="alert alert-danger">No tienes permiso para actualizar la configuración</label>';
        return false;
    }
    $date = $db->escapeString(date('Y-m-d'));
    // $data = $fn->get_settings('currency');
    $currency = empty($_POST['currency'])?'₹':$_POST['currency'];
    // if(empty($data)){
    //     $sql = "INSERT INTO `settings`(`variable`, `value`) VALUES ('currency','$currency')";
    //     $db->sql($sql);
    //     $message = "<div class='alert alert-success'> Settings updated successfully!</div>";
    // }else{
        $sql = "UPDATE `settings` SET `value`='".$currency."' WHERE `variable`='currency'";
        $db->sql($sql);
        $message = "<div class='alert alert-success'> Configuración actualizada con éxito!</div>";
    // }
    $_POST['system_timezone_gmt'] = (trim($_POST['system_timezone_gmt']) == '00:00')?"+".trim($_POST['system_timezone_gmt']):$_POST['system_timezone_gmt'];
    
    if(preg_match("/[a-z]/i", $_POST['current_version'])){
        $_POST['current_version']=0;
    }
    if(preg_match("/[a-z]/i", $_POST['minimum_version_required'])){
        $_POST['minimum_version_required']=0;
    }
    if(preg_match("/[a-z]/i", $_POST['delivery_charge'])){
        $_POST['delivery_charge']=0;
    }
    if(preg_match("/[a-z]/i", $_POST['min-refer-earn-order-amount'])){
        $_POST['min-refer-earn-order-amount']=0;
    }
    if(preg_match("/[a-z]/i", $_POST['min_amount'])){
        $_POST['min_amount']=0;
    }
    if(preg_match("/[a-z]/i", $_POST['max-refer-earn-amount'])){
        $_POST['max-refer-earn-amount']=0;
    }
    if(preg_match("/[a-z]/i", $_POST['minimum-withdrawal-amount'])){
        $_POST['minimum-withdrawal-amount']=0;
    }
    if(preg_match("/[a-z]/i", $_POST['refer-earn-bonus'])){
        $_POST['refer-earn-bonus']=0;
    }
    if(preg_match("/[a-z]/i", $_POST['tax'])){
        $_POST['tax']=0;
    }
    $sql = "UPDATE settings SET value='". json_encode($_POST) ."' WHERE variable='system_timezone'";
    $db->sql($sql);
    $res = $db->getResult();
    // $sql_currency = "UPDATE settings SET value='".$_POST['currency']."' WHERE variable='currency'";
    // $db->sql($sql_currency);
    $sql_logo="select value from `settings` where variable='Logo' OR variable='logo'";
    $db->sql($sql_logo);
    $res_logo = $db->getResult();
    // print_r($res_logo[0]['value']);  
    $file_name=$_FILES['logo']['name'];
    // if($_FILES['logo']['size'] > 0){
          // print_r($res_logo[0]['value']); 
    $allowedExts = array("gif", "jpeg", "jpg", "png");
    $tmp = explode('.', $file_name);
    $ext = end($tmp);
    
    if(!(in_array($ext, $allowedExts))){
            $message = "¡El tipo de imagen no es válido! ¡Sube la imagen adecuada!<br/>";
        }else{
            $old_image = '../dist/img/'.$res_logo[0]['value'];
            // echo $old_image;
            if(file_exists($old_image)){
                unlink($old_image);
            }
            
            $target_path = '../dist/img/';
            $filename = "logo.".strtolower($ext);
            $full_path = $target_path.''.$filename;
            // echo $full_path;
            if(!move_uploaded_file($_FILES["logo"]["tmp_name"], $full_path)){
                $message = "La imagen no se pudo cargar<br/>";
            }else{
                //Update Logo - id = 5
                $sql = "UPDATE `settings` SET `value`='".$filename."' WHERE `variable` = 'logo'";
                $db->sql($sql);
            }
        }
            
        // }

echo "<p class='alert alert-success'>Ajustes guardados!</p>";
}
if(isset($_POST['add_delivery_boy']) && $_POST['add_delivery_boy']==1){
    if(!checkadmin($auth_username)){
        echo "<label class='alert alert-danger'>Acceso denegado: no tiene autorización para acceder a esta página.</label>";
        return false;
    }
    if($permissions['delivery_boys']['create']==0){
       echo '<label class="alert alert-danger">No tienes permiso para crear repartidor</label>';
       return false; 
    }
    $name = $db->escapeString($_POST['name']);
    $mobile = $db->escapeString($_POST['mobile']);
    $address = $db->escapeString($_POST['address']);
    $bonus = $db->escapeString($_POST['bonus']);
    $password = $db->escapeString($_POST['password']);
    $password = md5($password);
    $sql='SELECT id FROM delivery_boys WHERE mobile='.$mobile;
    $db->sql($sql);
    $res=$db->getResult();
    $count=$db->numRows($res);
        if($count>0){
            echo '<label class="alert alert-danger">¡El número de móvil ya existe!</label>';
            return false;
        }
    $sql = "INSERT INTO delivery_boys (name,mobile,password,address,bonus)
                        VALUES('$name', '$mobile', '$password', '$address','$bonus')";
    if($db->sql($sql)){
        echo '<label class="alert alert-success">Repartidor añadido con exito!</label>';
    }else{
        echo '<label class="alert alert-danger">¡Se produjo algún error! Inténtalo de nuevo.</label>';
    }
    

}
if(isset($_POST['update_delivery_boy']) && $_POST['update_delivery_boy']==1){
    if(!checkadmin($auth_username)){
        echo "<label class='alert alert-danger'>Acceso denegado: no tiene autorización para acceder a esta página.</label>";
        return false;
    }
    if($permissions['delivery_boys']['update']==0){
       echo '<label class="alert alert-danger">No tienes permiso para actualizar repartidor</label>';
       return false; 
    }
    $id = $db->escapeString($_POST['delivery_boy_id']);
    if($id==104){
       echo '<label class="alert alert-danger">Lo siento, no puedes actualizar este repartidor</label>';
       return false; 
    }
    $name = $db->escapeString($_POST['update_name']);
    $password = !empty($_POST['update_password'])?$db->escapeString($_POST['update_password']):'';
    $address = $db->escapeString($_POST['update_address']);
    $bonus = $db->escapeString($_POST['update_bonus']);
    $status = $db->escapeString($_POST['status']);
    $password = !empty($password)?md5($password):'';
    if(!empty($password)){
        $sql = "Update delivery_boys set `name`='".$name."',password='".$password."',`address`='".$address."',`bonus`='".$bonus."',`status`='".$status."' where `id`=".$id;
    }else{
         $sql = "Update delivery_boys set `name`='".$name."',`address`='".$address."',`bonus`='".$bonus."',`status`='".$status."' where `id`=".$id;
    }
    if($db->sql($sql)){
        echo "<label class='alert alert-success'>Información actualizada con éxito.</label>";
    }else{
        echo "<label class='alert alert-danger'>¡Se produjo algún error! Inténtalo de nuevo.</label>";

    }
}
if(isset($_GET['delete_delivery_boy']) && $_GET['delete_delivery_boy']==1){
    if($permissions['delivery_boys']['delete']==0){
        echo 2;
        return false;
    }
    $id=$db->escapeString($_GET['id']);
    if($id==104){
        echo 3;
        return false;
    }
    $sql = "DELETE FROM `delivery_boys` WHERE id=".$id;
    if($db->sql($sql)){
        echo 0;
    }else{
        echo 1;
    }

}
if(isset($_POST['update_payment_request']) && $_POST['update_payment_request']==1){
    if(!checkadmin($auth_username)){
        echo "<label class='alert alert-danger'>Acceso denegado: no tiene autorización para acceder a esta página.</label>";
        return false;
    }
    if($permissions['payment']['update']==0){
        echo "<label class='alert alert-danger'>No tiene permiso para actualizar la solicitud de pago.</label>";
        return false;
    }
    $id = $db->escapeString($_POST['payment_request_id']);
    $remarks = $db->escapeString($_POST['update_remarks']);
    $status = $db->escapeString($_POST['status']);
    if($status=='2'){
        $sql = "SELECT user_id,amount_requested FROM payment_requests WHERE id=".$id;
        $db->sql($sql);
        $res = $db->getResult();
        $user_id = $res[0]['user_id'];
        $amount = $res[0]['amount_requested'];

        $sql = "UPDATE users SET balance = balance + $amount WHERE id=".$user_id;
        $db->sql($sql);

    }
    $sql = "Update payment_requests set `remarks`='".$remarks."',`status`='".$status."' where `id`=".$id;
    if($db->sql($sql)){
        echo "<label class='alert alert-success'>Actualizado con éxito.</label>";
    }else{
        echo "<label class='alert alert-danger'>¡Se produjo algún error! Inténtalo de nuevo.</label>";

    }
}
if(isset($_POST['boy_id']) && isset($_POST['transfer_fund'])){
    if(!checkadmin($auth_username)){
        echo "<label class='alert alert-danger'>Acceso denegado: no tiene autorización para acceder a esta página.</label>";
        return false;
    }
    if($permissions['payment']['update']==0){
        echo "<label class='alert alert-danger'>No tienes permiso para actualizar repartidor.</label>";
        return false;
    }
    $id=$db->escapeString($_POST['boy_id']);
    $balance=$db->escapeString($_POST['delivery_boy_balance']);
    $amount=$db->escapeString($_POST['amount']);
    $message=(!empty($_POST['message']))?$db->escapeString($_POST['message']):'Fondo transferido por el administrador';
    $bal=$balance-$amount;
    $sql = "Update delivery_boys set `balance`='".$bal."' where `id`=".$id;
    $db->sql($sql);
    $sql = "INSERT INTO `fund_transfers` (`delivery_boy_id`,`amount`,`opening_balance`,`closing_balance`,`status`,`message`) VALUES ('".$id."','".$amount."','".$balance."','".$bal."','EXITO','".$message."')";
    $db->sql($sql);
    echo "<p class='alert alert-success'>Cantidad transferida con éxito!</p>";
}
if(isset($_POST['add_promo_code']) && $_POST['add_promo_code']==1){
    if(!checkadmin($auth_username)){
        echo "<label class='alert alert-danger'>Acceso denegado: no tiene autorización para acceder a esta página.</label>";
        return false;
    }
    if($permissions['promo_codes']['create']==0){
       echo '<label class="alert alert-danger">No tienes permiso para crear un código promocional</label>'; 
       return false;
    }
    $promo_code = $db->escapeString($_POST['promo_code']);
    $message = $db->escapeString($_POST['message']);
    $start_date = $db->escapeString($_POST['start_date']);
    $end_date = $db->escapeString($_POST['end_date']);
    $no_of_users = $db->escapeString($_POST['no_of_users']);
    $minimum_order_amount = $db->escapeString($_POST['minimum_order_amount']);
    $discount = $db->escapeString($_POST['discount']);
    $discount_type = $db->escapeString($_POST['discount_type']);
    $max_discount_amount = $db->escapeString($_POST['max_discount_amount']);
    $repeat_usage = $db->escapeString($_POST['repeat_usage']);
    $no_of_repeat_usage = !empty($_POST['repeat_usage'])?$db->escapeString($_POST['no_of_repeat_usage']):0;
    $status = $db->escapeString($_POST['status']);

    $sql = "INSERT INTO promo_codes (promo_code,message,start_date,end_date,no_of_users,minimum_order_amount,discount,discount_type,max_discount_amount,repeat_usage,no_of_repeat_usage,status)
                        VALUES('$promo_code', '$message', '$start_date', '$end_date','$no_of_users','$minimum_order_amount','$discount','$discount_type','$max_discount_amount','$repeat_usage','$no_of_repeat_usage','$status')";
                        // echo $sql;
    if($db->sql($sql)){
        echo '<label class="alert alert-success">¡Código promocional agregado con éxito!</label>';
    }else{
        echo '<label class="alert alert-danger">¡Se produjo algún error! Inténtalo de nuevo.</label>';
    }
    

}
if(isset($_POST['update_promo_code']) && $_POST['update_promo_code']==1){
    if(!checkadmin($auth_username)){
        echo "<label class='alert alert-danger'>Acceso denegado: no tiene autorización para acceder a esta página.</label>";
        return false;
    }
    if($permissions['promo_codes']['update']==0){
       echo '<label class="alert alert-danger">No tienes permiso para actualizar el código promocional</label>'; 
       return false;
    }
    $id = $db->escapeString($_POST['promo_code_id']);
    $promo_code = $db->escapeString($_POST['update_promo']);
    $message = $db->escapeString($_POST['update_message']);
    $start_date = $db->escapeString($_POST['update_start_date']);
    $end_date = $db->escapeString($_POST['update_end_date']);
    $no_of_users = $db->escapeString($_POST['update_no_of_users']);
    $minimum_order_amount = $db->escapeString($_POST['update_minimum_order_amount']);
    $discount = $db->escapeString($_POST['update_discount']);
    $discount_type = $db->escapeString($_POST['update_discount_type']);
    $max_discount_amount = $db->escapeString($_POST['update_max_discount_amount']);
    $repeat_usage = $db->escapeString($_POST['update_repeat_usage']);
    $no_of_repeat_usage = $repeat_usage==0?'0':$db->escapeString($_POST['update_no_of_repeat_usage']);
    $status = $db->escapeString($_POST['status']);
    
    $sql = "Update promo_codes set `promo_code`='".$promo_code."',`message`='".$message."',`start_date`='".$start_date."',`end_date`='".$end_date."',`no_of_users`='".$no_of_users."',`minimum_order_amount`='".$minimum_order_amount."',`discount`='".$discount."',`discount_type`='".$discount_type."',`max_discount_amount`='".$max_discount_amount."',`repeat_usage`='".$repeat_usage."',`no_of_repeat_usage`='".$no_of_repeat_usage."',`status`='".$status."' where `id`=".$id;
    
    if($db->sql($sql)){
        echo "<label class='alert alert-success'>Código promocional actualizado con éxito.</label>";
    }else{
        echo "<label class='alert alert-danger'>¡Se produjo algún error! Inténtalo de nuevo.</label>";

    }
}
if(isset($_GET['delete_promo_code']) && $_GET['delete_promo_code']==1){
    if($permissions['promo_codes']['delete']==0){
        echo 2;
        return false;
    }
    $id=$db->escapeString($_GET['id']);
    $sql = "DELETE FROM `promo_codes` WHERE id=".$id;
    if($db->sql($sql)){
        echo 0;
    }else{
        echo 1;
    }

}
if(isset($_POST['add_time_slot']) && $_POST['add_time_slot']==1){
    if(!checkadmin($auth_username)){
        echo "<label class='alert alert-danger'>Acceso denegado: no tiene autorización para acceder a esta página.</label>";
        return false;
    }
    if($permissions['settings']['update']==0){
        
       echo '<label class="alert alert-danger">No tienes permiso para agregar un intervalo de tiempo</label>'; 
       return false;
    
    }
    $title = $db->escapeString($_POST['title']);
    $from_time = $db->escapeString($_POST['from_time']);
    $to_time = $db->escapeString($_POST['to_time']);
    $last_order_time = $db->escapeString($_POST['last_order_time']);
    $status = $db->escapeString($_POST['status']);
    $sql = "INSERT INTO time_slots (title,from_time,to_time,last_order_time,status)
                        VALUES('$title', '$from_time', '$to_time', '$last_order_time','$status')";
    if($db->sql($sql)){
        echo '<label class="alert alert-success">Agregado exitosamente!</label>';
    }else{
        echo '<label class="alert alert-danger">¡Se produjo algún error! Inténtalo de nuevo.</label>';
    }
    

}
if(isset($_POST['update_time_slot']) && $_POST['update_time_slot']==1){
    if(!checkadmin($auth_username)){
        echo "<label class='alert alert-danger'>Acceso denegado: no tiene autorización para acceder a esta página.</label>";
        return false;
    }
    if($permissions['settings']['update']==0){
        
       echo '<label class="alert alert-danger">No tienes permiso para actualizar el intervalo de tiempo</label>'; 
       return false;
    
    }
    $id = $db->escapeString($_POST['time_slot_id']);
    $title = $db->escapeString($_POST['update_title']);
    $from_time = $db->escapeString($_POST['update_from_time']);
    $to_time = $db->escapeString($_POST['update_to_time']);
    $last_order_time = $db->escapeString($_POST['update_last_order_time']);
    $status = $db->escapeString($_POST['status']);
    $sql = "Update time_slots set `title`='".$title."',`from_time`='".$from_time."',`to_time`='".$to_time."',`last_order_time`='".$last_order_time."',`status`='".$status."' where `id`=".$id;
    if($db->sql($sql)){
        echo "<label class='alert alert-success'>Horarios actualizada con éxito.</label>";
    }else{
        echo "<label class='alert alert-danger'>¡Se produjo algún error! Inténtalo de nuevo</label>";

    }
}
if(isset($_GET['delete_time_slot']) && $_GET['delete_time_slot']==1){
    if($permissions['settings']['update']==0){
        
       echo 2; 
       return false;
    
    }
    $id=$db->escapeString($_GET['id']);
    $sql = "DELETE FROM `time_slots` WHERE id=".$id;
    if($db->sql($sql)){
        echo 0;
    }else{
        echo 1;
    }

}
if(isset($_POST['update_return_request']) && $_POST['update_return_request']==1){
    if(!checkadmin($auth_username)){
        echo "<label class='alert alert-danger'>Acceso denegado: no tiene autorización para acceder a esta página.</label>";
        return false;
    }
    if($permissions['return_requests']['update']==0){
        echo "<label class='alert alert-danger'>No tiene permiso para actualizar la solicitud de devolución.</label>";
        return false;
    }
    
    $id = $db->escapeString($_POST['return_request_id']);
    $order_item_id = $db->escapeString($_POST['order_item_id']);
    $order_id = $db->escapeString($_POST['order_id']);
    $remarks = $db->escapeString($_POST['update_remarks']);
    $status = $db->escapeString($_POST['status']);
    $sql="select status from return_requests where id=".$id;
    $db->sql($sql);
    $res=$db->getResult();
    if($res[0]['status']==1){
        echo "<label class='alert alert-danger'>Solicitud de devolución ya aprobada.</label>";
        return false;
    }
    if($status==1){
        $sql = 'SELECT oi.`product_variant_id`,oi.`quantity`,oi.`discounted_price`,oi.`price`,pv.`product_id`,pv.`type`,pv.`stock`,pv.`stock_unit_id`,pv.`measurement`,pv.`measurement_unit_id` FROM `order_items` oi join `product_variant` pv on pv.id = oi.product_variant_id WHERE oi.`id`='.$order_item_id;
        
        $db->sql($sql);
        $res_oi = $db->getResult();
		  if($res_oi[0]['type']=='packet'){
    	        $sql = "UPDATE product_variant SET stock = stock + ".$res_oi[0]['quantity']." WHERE id='".$res_oi[0]['product_variant_id']."'";
    			$db->sql($sql);
    			
    			$sql = "select stock from product_variant where id=".$res_oi[0]['product_variant_id'];
    			$db->sql($sql);
    			$res_stock = $db->getResult();
    			if($res_stock[0]['stock']>0){
        			$sql = "UPDATE product_variant set serve_for='disponible' WHERE id='".$res_oi[0]['product_variant_id']."'";
        			$db->sql($sql);
    			}
    			    
    	    }else{
    	        /* When product type is loose */
    	        if($res_oi[0]['measurement_unit_id'] != $res_oi[0]['stock_unit_id']){
    	            $stock = $function->convert_to_parent($res_oi[0]['measurement'],$res_oi[0]['measurement_unit_id']);
    	            $stock = $stock * $res_oi[0]['quantity'];
    	            $sql = "UPDATE product_variant SET stock = stock + ".$stock." WHERE product_id='".$res_oi[0]['product_id']."'";
    			    $db->sql($sql);
    	        }else{
    	            $stock = $res_oi[0]['measurement'] * $res_oi[0]['quantity'];
    	            $sql = "UPDATE product_variant SET stock = stock + ".$stock." WHERE product_id='".$res_oi[0]['product_id']."'";
    			    $db->sql($sql);
    	        }
    	        $sql = "select stock from product_variant where product_id=".$res_oi[0]['product_id'];
                $db->sql($sql);
                $res_stck= $db->getResult();
                if($res_stck[0]['stock']>0){
                    $sql = "UPDATE product_variant set serve_for='disponible' WHERE product_id='".$res_oi[0]['product_id']."'";
        			$db->sql($sql);
                }
    	    }
            /* update user's wallet */
            $total = $res_oi[0]['discounted_price']==0?$res_oi[0]['price']*$res_oi[0]['quantity']:$res_oi[0]['discounted_price']*$res_oi[0]['quantity'];
            $sql = "select user_id from return_requests where id=".$id;
            $db->sql($sql);
            $res_user = $db->getResult();
            $user_id = $res_user[0]['user_id'];
            // $sql = "select balance from users where id=".$user_id;
            // $db->sql($sql);
            // $balance=$db->getResult();
        //     $new_balance = ($balance[0]['balance'] + $total);
	       // $function->update_wallet_balance($new_balance,$user_id);
	       $sql = "update users set balance = balance + $total where id=".$user_id;
	       $db->sql($sql);
	        /* add wallet transaction */
	        $sql = "insert into wallet_transactions (`user_id`,`type`,`amount`,`message`,`status`)values(".$user_id.",'credit',".$total.",'Saldo acreditado en la devolución del producto aprobado.',1)";
	       $db->sql($sql);
		  //  $wallet_txn_id = $function->add_wallet_transaction($user_id,'credit',$total,'Balance credited on product return approved.');
		    

    }
        $sql_query = "Update return_requests set `remarks`='".$remarks."',`status`='".$status."' where `id`=".$id;
        if($db->sql($sql_query)){
            echo "<label class='alert alert-success'>Solicitud de devolución actualizada correctamente.</label>";
        }else{
            echo "<label class='alert alert-danger'>¡Se produjo algún error! Inténtalo de nuevo.</label>";
    
        }
}
if(isset($_GET['delete_return_request']) && $_GET['delete_return_request']==1){
    if($permissions['return_requests']['delete']==0){
        echo 2;
        return false;
    }
    $id=$db->escapeString($_GET['id']);
    $sql = "DELETE FROM `return_requests` WHERE id=".$id;
    // echo $sql;
    if($db->sql($sql)){
        echo 0;
    }else{
        echo 1;
    }

}
if(isset($_POST['manage_customer_wallet']) && isset($_POST['user_id'])){
    if(!checkadmin($auth_username)){
        echo "<label class='alert alert-danger'>Acceso denegado: no tiene autorización para acceder a esta página.</label>";
        return false;
    }
    if($permissions['customers']['read']==0){
       echo '<label class="alert alert-danger">No tienes permiso para administrar el saldo de la billetera</label>'; 
       return false;
    }

    $user_id = $db->escapeString($_POST['user_id']);
    $amount = $db->escapeString($_POST['amount']);
    $type = $db->escapeString($_POST['type']);
    $message = !empty(trim($_POST['message']))?trim($db->escapeString($_POST['message'])):'Transaccion por administrador';
    
    $balance = $fn->get_wallet_balance($user_id);
    $new_balance = $type=='credit'?$balance+$amount:$balance-$amount;
    $fn->update_wallet_balance($new_balance,$user_id);
    if($fn->add_wallet_transaction($user_id,$type,$amount,$message)){
         echo "<label class='alert alert-success'>Saldo actualizado exitosamente.</label>";
    }else{
        echo "<label class='alert alert-danger'>¡Se produjo algún error! Inténtalo de nuevo.</label>";

    }


}
if(isset($_POST['add_system_user']) && $_POST['add_system_user']==1){
    if(!checkadmin($auth_username)){
        echo "<label class='alert alert-danger'>Acceso denegado: no tiene autorización para acceder a esta página.</label>";
        return false;
    }
    $id = $_SESSION['id'];
    $username = $db->escapeString($_POST['username']);
    $email = $db->escapeString($_POST['email']);
    $password = $db->escapeString($_POST['password']);
    $password = md5($password);
    $role = $db->escapeString($_POST['role']);
    

    $sql="SELECT id FROM admin WHERE username='".$username."'";
    $db->sql($sql);
    $res=$db->getResult();
    $count=$db->numRows($res);
        if($count>0){
            echo '<label class="alert alert-danger">¡Nombre de usuario ya existe!</label>';
            return false;
        }

    $sql="SELECT id FROM admin WHERE email='".$email."'";
    $db->sql($sql);
    $res=$db->getResult();
    $count=$db->numRows($res);
        if($count>0){
            echo '<label class="alert alert-danger">¡El Email ya existe!</label>';
            return false;
        }
    $permissions['orders']=array("create"=>$_POST['is-create-order'], "read"=>$_POST['is-read-order'], "update"=>$_POST['is-update-order'],"delete"=>$_POST['is-delete-order']);

    $permissions['categories']=array("create"=>$_POST['is-create-category'], "read"=>$_POST['is-read-category'], "update"=>$_POST['is-update-category'],"delete"=>$_POST['is-delete-category']);

    $permissions['subcategories']=array("create"=>$_POST['is-create-subcategory'], "read"=>$_POST['is-read-subcategory'], "update"=>$_POST['is-update-subcategory'],"delete"=>$_POST['is-delete-subcategory']);

    $permissions['products']=array("create"=>$_POST['is-create-product'], "read"=>$_POST['is-read-product'], "update"=>$_POST['is-update-product'],"delete"=>$_POST['is-delete-product']);

    $permissions['products_order']=array("read"=>$_POST['is-read-products-order'], "update"=>$_POST['is-update-products-order']);

    $permissions['home_sliders']=array("create"=>$_POST['is-create-home-slider'], "read"=>$_POST['is-read-home-slider'], "delete"=>$_POST['is-delete-home-slider']);

    $permissions['new_offers']=array("create"=>$_POST['is-create-new-offer'], "read"=>$_POST['is-read-new-offer'], "delete"=>$_POST['is-delete-new-offer']);

    $permissions['promo_codes']=array("create"=>$_POST['is-create-promo'], "read"=>$_POST['is-read-promo'], "update"=>$_POST['is-update-promo'],"delete"=>$_POST['is-delete-promo']);

    $permissions['featured']=array("create"=>$_POST['is-create-featured'], "read"=>$_POST['is-read-featured'], "update"=>$_POST['is-update-featured'],"delete"=>$_POST['is-delete-featured']);

    $permissions['customers']=array("read"=>$_POST['is-read-customers']);

    $permissions['payment']=array("read"=>$_POST['is-read-payment'], "update"=>$_POST['is-update-payment']);

    $permissions['return_requests']=array("read"=>$_POST['is-read-return'], "update"=>$_POST['is-update-return'],"delete"=>$_POST['is-delete-return']);

    $permissions['delivery_boys']=array("create"=>$_POST['is-create-delivery'], "read"=>$_POST['is-read-delivery'], "update"=>$_POST['is-update-delivery'],"delete"=>$_POST['is-delete-delivery']);

    $permissions['notifications']=array("create"=>$_POST['is-create-notification'], "read"=>$_POST['is-read-notification'], "delete"=>$_POST['is-delete-notification']);

    $permissions['transactions']=array("read"=>$_POST['is-read-transaction']);

    $permissions['settings']=array("read"=>$_POST['is-read-settings'], "update"=>$_POST['is-update-settings']);

    $permissions['locations']=array("create"=>$_POST['is-create-location'], "read"=>$_POST['is-read-location'], "update"=>$_POST['is-update-location'],"delete"=>$_POST['is-delete-location']);

    $permissions['reports']=array("create"=>$_POST['is-create-report'], "read"=>$_POST['is-read-report']);

    $permissions['faqs']=array("create"=>$_POST['is-create-faq'], "read"=>$_POST['is-read-faq'], "update"=>$_POST['is-update-faq'],"delete"=>$_POST['is-delete-faq']);

    $encoded_permissions = json_encode($permissions);
    $sql = "INSERT INTO admin (username,email,password,role,permissions,created_by)
                        VALUES('$username', '$email', '$password', '$role','$encoded_permissions','$id')";
                        // echo $sql;
    if($db->sql($sql)){
        echo '<label class="alert alert-success">'.$role.' Agregado exitosamente</label>';
    }else{
        echo '<label class="alert alert-danger">¡Se produjo algún error! Inténtalo de nuevo.</label>';
    }
    

}
if(isset($_GET['delete_system_user']) && $_GET['delete_system_user']==1){
    $id=$db->escapeString($_GET['id']);
    $sql = "DELETE FROM `admin` WHERE id=".$id;
    if($db->sql($sql)){
        echo 0;
    }else{
        echo 1;
    }

}
if(isset($_POST['update_system_user']) && $_POST['update_system_user']==1){
    if(!checkadmin($auth_username)){
        echo "<label class='alert alert-danger'>Acceso denegado: no tiene autorización para acceder a esta página.</label>";
        return false;
    }
    $id = $db->escapeString($_POST['system_user_id']);
    $permissions['orders']=array("create"=>$_POST['permission-is-create-order'], "read"=>$_POST['permission-is-read-order'], "update"=>$_POST['permission-is-update-order'],"delete"=>$_POST['permission-is-delete-order']);

    $permissions['categories']=array("create"=>$_POST['permission-is-create-category'], "read"=>$_POST['permission-is-read-category'], "update"=>$_POST['permission-is-update-category'],"delete"=>$_POST['permission-is-delete-category']);

    $permissions['subcategories']=array("create"=>$_POST['permission-is-create-subcategory'], "read"=>$_POST['permission-is-read-subcategory'], "update"=>$_POST['permission-is-update-subcategory'],"delete"=>$_POST['permission-is-delete-subcategory']);

    $permissions['products']=array("create"=>$_POST['permission-is-create-product'], "read"=>$_POST['permission-is-read-product'], "update"=>$_POST['permission-is-update-product'],"delete"=>$_POST['permission-is-delete-product']);

    $permissions['products_order']=array("read"=>$_POST['permission-is-read-products-order'], "update"=>$_POST['permission-is-update-products-order']);

    $permissions['home_sliders']=array("create"=>$_POST['permission-is-create-home-slider'], "read"=>$_POST['permission-is-read-home-slider'], "delete"=>$_POST['permission-is-delete-home-slider']);

    $permissions['new_offers']=array("create"=>$_POST['permission-is-create-new-offer'], "read"=>$_POST['permission-is-read-new-offer'], "delete"=>$_POST['permission-is-delete-new-offer']);

    $permissions['promo_codes']=array("create"=>$_POST['permission-is-create-promo'], "read"=>$_POST['permission-is-read-promo'], "update"=>$_POST['permission-is-update-promo'],"delete"=>$_POST['permission-is-delete-promo']);

    $permissions['featured']=array("create"=>$_POST['permission-is-create-featured'], "read"=>$_POST['permission-is-read-featured'], "update"=>$_POST['permission-is-update-featured'],"delete"=>$_POST['permission-is-delete-featured']);

    $permissions['customers']=array("read"=>$_POST['permission-is-read-customers']);

    $permissions['payment']=array("read"=>$_POST['permission-is-read-payment'], "update"=>$_POST['permission-is-update-payment']);

    $permissions['return_requests']=array("read"=>$_POST['permission-is-read-return'], "update"=>$_POST['permission-is-update-return'],"delete"=>$_POST['permission-is-delete-return']);

    $permissions['delivery_boys']=array("create"=>$_POST['permission-is-create-delivery'], "read"=>$_POST['permission-is-read-delivery'], "update"=>$_POST['permission-is-update-delivery'],"delete"=>$_POST['permission-is-delete-delivery']);

    $permissions['notifications']=array("create"=>$_POST['permission-is-create-notification'], "read"=>$_POST['permission-is-read-notification'], "delete"=>$_POST['permission-is-delete-notification']);

    $permissions['transactions']=array("read"=>$_POST['permission-is-read-transaction']);

    $permissions['settings']=array("read"=>$_POST['permission-is-read-settings'], "update"=>$_POST['permission-is-update-settings']);

    $permissions['locations']=array("create"=>$_POST['permission-is-create-location'], "read"=>$_POST['permission-is-read-location'], "update"=>$_POST['permission-is-update-location'],"delete"=>$_POST['permission-is-delete-location']);

    $permissions['reports']=array("create"=>$_POST['permission-is-create-report'], "read"=>$_POST['permission-is-read-report']);

    $permissions['faqs']=array("create"=>$_POST['permission-is-create-faq'], "read"=>$_POST['permission-is-read-faq'], "update"=>$_POST['permission-is-update-faq'],"delete"=>$_POST['permission-is-delete-faq']);

    $permissions = json_encode($permissions);
    // print_r($permissions);
    // return false;
    $sql = "UPDATE admin SET permissions='".$permissions."' WHERE id=".$id;
    if($db->sql($sql)){
        echo '<label class="alert alert-success">Actualizado con éxito!</label>';
    }else{
        echo '<label class="alert alert-danger">¡Se produjo algún error! Inténtalo de nuevo.</label>';
    }
    

}


?>