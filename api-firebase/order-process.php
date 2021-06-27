
<?php
header('Access-Control-Allow-Origin: *');
include_once('send-email.php');
include_once('send-sms.php');
include_once('../includes/crud.php');
include_once('../includes/custom-functions.php');
include_once('../includes/variables.php');
include_once('verify-token.php');
$db = new Database();
$db->connect();
$db->sql("SET NAMES utf8");
$function = new custom_functions();
$settings = $function->get_settings('system_timezone',true);
$app_name = $settings['app_name'];
$support_email = $settings['support_email'];
	$config = $function->get_configurations();
		if(isset($config['system_timezone']) && isset($config['system_timezone_gmt'])){
			date_default_timezone_set($config['system_timezone']);
			$db->sql("SET `time_zone` = '".$config['system_timezone_gmt']."'");
		}else{
	date_default_timezone_set('Asia/Kolkata');
	$db->sql("SET `time_zone` = '+05:30'");
}

 
$response = array();


if(isset($_POST['ajaxCall']) && !empty($_POST['ajaxCall'])){
   
	$accesskey="90336";	
}else{
	if(isset($_POST['accesskey']) && $_POST['accesskey'] != ''){
		$accesskey=$_POST['accesskey'];
	}else{
		$response['error']= true;
		$response['message']="clave de acceso requerida";
		print_r(json_encode($response));
		return false;
	}
	
}

if($access_key != $accesskey){
	$response['error']= true;
	$response['message']="clave de acceso inválida";
	print_r(json_encode($response));
	return false;
}
// if(!isset($_POST['ajaxCall']) && empty($_POST['ajaxCall'])){
// 	if(!verify_token()){
//         return false;
//     }
// }

if(isset($_POST['place_order']) && isset($_POST['user_id']) && !empty($_POST['product_variant_id'])){
    if(!verify_token()){
        return false;
    }
	// echo "test";

    // $user_name = $db->escapeString($_POST['user_name']);
	$user_id = $db->escapeString($_POST['user_id']);
	$mobile = $db->escapeString($_POST['mobile']);
	$wallet_balance = (isset($_POST['wallet_balance']) && is_numeric($_POST['wallet_balance']))?$db->escapeString($_POST['wallet_balance']):0;
	$wallet_used = (isset($_POST['wallet_used']) && $_POST['wallet_used'] == 'true')?'true':'false';
	$items = $db->escapeString(stripslashes($_POST['product_variant_id']));
	$total = $db->escapeString($_POST['total']);
	$delivery_charge = $db->escapeString($_POST['delivery_charge']);
	$discount = (isset($_POST['discount']))?$db->escapeString($_POST['discount']):0;
	$tax_percentage = (isset($_POST['tax_percentage']) && is_numeric($_POST['tax_percentage']))?$db->escapeString($_POST['tax_percentage']):'';
	$tax_amount = (isset($_POST['tax_amount']) && is_numeric($_POST['tax_amount']))?$db->escapeString($_POST['tax_amount']):'';
	$wallet_balance = (isset($_POST['wallet_balance']) && is_numeric($_POST['wallet_balance']))?$db->escapeString($_POST['wallet_balance']):0;
	$final_total = $db->escapeString($_POST['final_total']);
	$payment_method = $db->escapeString($_POST['payment_method']);
	$address = $db->escapeString($_POST['address']);
	$delivery_time = (isset($_POST['delivery_time']))?$db->escapeString($_POST['delivery_time']):"";
	$latitude = $db->escapeString($_POST['latitude']);
	$longitude = $db->escapeString($_POST['longitude']);
	$promo_code = (isset($_POST['promo_code'])&& !empty($_POST['promo_code']) )?$db->escapeString($_POST['promo_code']):"-";
	$promo_discount = (isset($_POST['promo_discount'])&& !empty($_POST['promo_discount']) )?$db->escapeString($_POST['promo_discount']):0;
	$status[] = array( 'recibido',date("d-m-Y h:i:sa") );
	// $item_details=json_decode(stripslashes(strip_tags($items)),1);
	$item_details=$function->get_product_by_variant_id($items);
	$total_amount=$total+$delivery_charge+$tax_amount-$discount;
	// print_r($item_details);
	$quantity_arr=json_decode($_POST['quantity'],1);
	// $final_total=0;
	
	/* validate promo code if applied */
	if (isset($_POST['promo_code']) && $_POST['promo_code'] != '') {
	    $promo_code = $db->escapeString($_POST['promo_code']);
	    $response=$function->validate_promo_code($user_id,$promo_code,$total);
	    if($response['error']==true){
			echo json_encode($response);
			exit();
		}
	    
	}
	
	/* process wallet balance */
	$user_wallet_balance = $function->get_wallet_balance($user_id);
	
	if($user_wallet_balance >= $wallet_balance && $user_wallet_balance != 0 && $wallet_used=='true'){
	    
	    /* deduct the balance & set the wallet transaction */
	   // $new_balance = ($user_wallet_balance - $final_total);
	    $new_balance = $total_amount>
	    $user_wallet_balance?0:$user_wallet_balance - $total_amount;
	    $function->update_wallet_balance($new_balance,$user_id);
		/* add wallet transaction */
		$wallet_txn_id = $function->add_wallet_transaction($user_id,'debit',$wallet_balance,'Utilizado contra la colocación de pedidos');
	}else{
	    $wallet_used = false;
	}
	
	$data = array(
		'user_id'=>$user_id,
		'mobile'=>$mobile,
		'delivery_charge'=>$delivery_charge,
		'wallet_balance' => ($wallet_used)?$wallet_balance:0,
		'total' => $total,
		'tax_percentage' => $tax_percentage,
		'tax_amount' => $tax_amount,
		'final_total' => $final_total,
		'payment_method'=>$payment_method,
		'address'=>$address,
		'delivery_time'=>$delivery_time,
		'status' => $db->escapeString(json_encode($status)),
		'latitude' => $latitude,
		'longitude' => $longitude,
		'promo_code' => $promo_code,
		'promo_discount' => $promo_discount,
		'discount' => $discount,
		'active_status' => 'recibido'
	);
	$db->insert('orders',$data);
	$order_id = $db->getResult()[0];
	// print_r($item_details);
	for($i=0;$i<count($item_details);$i++){
		$product_id = $item_details[$i]['product_id'];
		// $unit = (float) filter_var( $item_details[$i][2], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ) ; // float(55.35) 
		$measurement = $item_details[$i]['measurement'];
		$product_variant_id = $item_details[$i]['id'];
		// echo $product_variant_id;
		$measurement_unit_id = $item_details[$i]['measurement_unit_id'];
		$stock_unit_id = $item_details[$i]['stock_unit_id'];
		$price = $item_details[$i]['price'];
		$discounted_price = $item_details[$i]['discounted_price'];
		// $seller_id = $item_details[$i]['seller_id'];
		$type = $item_details[$i]['type'];
		$total_stock = $item_details[$i]['stock'];
		$quantity = $quantity_arr[$i];
		
		$sub_total = $discounted_price != 0?$discounted_price * $quantity:$price * $quantity;
		

		$data = array(
		    'user_id'=>$user_id,
		    'order_id'=>$db->escapeString($order_id),
		    // 'seller_id'=>$db->escapeString($seller_id),
		   'product_variant_id'=>$db->escapeString($product_variant_id),
		   'quantity'=>$db->escapeString($quantity),
		    'price'=>$db->escapeString($price),
		    'discounted_price'=>$db->escapeString($discounted_price),
		    'discount'=>$discount,
		    'sub_total'=>$db->escapeString($sub_total),
		    'status'=>$db->escapeString(json_encode($status)),
		    'active_status' => 'recibido'

		);
		$db->insert('order_items',$data);
		$res = $db->getResult();
		// $final_total=$final_total + $sub_total;
		$balance = $final_total/10;
		
		// $sql = "update seller set balance=balance+'".$balance."' where id=".$seller_id;
		// $db->sql($sql);
		// $res = $db->getResult();
	
			 if($type=='packet'){
				 $stock = $total_stock-$quantity;
				 // print_r($stock);
				 $sql = "update product_variant set stock = $stock where id = $product_variant_id";
				 // echo $sql;
				 $db->sql($sql);
				 $res = $db->getResult();
				 $db->select("product_variant","stock",null,"id='".$product_variant_id."'");
				 $variant_qty = $db->getResult();
				 if($variant_qty[0]['stock']<=0){
					$data = array(
						"serve_for"=> "agotado",
					);
					$db->update("product_variant",$data,"id=$product_variant_id");
					$res = $db->getResult();
				 }
			 }elseif($type=='loose'){
				 if($measurement_unit_id==$stock_unit_id){
					 $stock = $quantity*$measurement;
					}else{
						$db->select('unit','*',null,'id='.$measurement_unit_id);
						$unit = $db->getResult();
						// print_r($unit);
						// if(!empty($unit[0]['parent_id'])){
							$stock=$function->convert_to_parent(($measurement*$quantity),$unit[0]['id']);
							// $stock = ($measurement*$quantity)/$unit[0]['conversion'];
						// }
						// else{
						// 	$db->select('unit','conversion',null,"parent_id='".$measurement_unit_id."'");
						// 	$unit = $db->getResult();
						// 	$stock=$function->convert_to_child(($measurement*$quantity),$unit[0]['id']);
							
						// }
					}

					$sql = "update product_variant set stock = stock - $stock where product_id = $product_id AND type='loose'";
					// echo $sql;
					$db->sql($sql);
					$res = $db->getResult();
					$sql = "select stock from product_variant where product_id=".$product_id;
					$db->sql($sql);
					$res_stck= $db->getResult();
					if($res_stck[0]['stock']<=0){
					    $sql = "update product_variant set serve_for='agotado' where product_id=".$product_id;
					    $db->sql($sql);
					}

			 }
		}
		
		// $final_total = $final_total + $delivery_charge; /* because delivery charge is already added from android */
		$data = array(
	        'final_total'=>$final_total
		);

		if($db->update('orders',$data,'id='.$order_id)){// Table name, column names and respective values
			$res = $db->getResult();
			$response['error'] = "false";
			$response['message'] = "Pedido realizado con éxito.";
			$response['order_id'] = $order_id;
			$_SESSION['checkout']=$_POST;
			$_SESSION['checkout']['order_id'] = $order_id;
			
		
			$sql = "select name, email, mobile, country_code from users where id=".$user_id;
// 			echo $sql;
			$db->sql($sql);
			$res = $db->getResult();
			$to = $res[0]['email'];
			$mobile = $res[0]['mobile'];
			$country_code = $res[0]['country_code'];
// 			echo $country_code;
			$subject = "Pedido recibido con éxito";
			$message = "Hola ".ucwords($res[0]['name']).", Hemos recibido su pedido con éxito. Los resúmenes de sus pedidos son los siguientes:<br><br>";
			$message .= "<b>Pedido ID :</b> #".$response['order_id']."<br><br>Productos pedidos : <br>";
			// $items = json_decode($_POST['items']);
			$items = $db->escapeString($_POST['product_variant_id']);
			$quantity_arr=json_decode($_POST['quantity'],1);
			$item_details=$function->get_product_by_variant_id($items);
			// print_r($item_details);
			for($i=0;$i<count($item_details);$i++){
				$product_id = $item_details[$i]['product_id'];
				// $unit = (float) filter_var( $item_details[$i][2], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ) ; // float(55.35) 
				$measurement = $item_details[$i]['measurement'];
				$product_variant_id = $item_details[$i]['id'];
				// echo $product_variant_id;
				$measurement_unit_id = $item_details[$i]['measurement_unit_id'];
				$stock_unit_id = $item_details[$i]['stock_unit_id'];
				$price = $item_details[$i]['price'];
				$discounted_price = $item_details[$i]['discounted_price'];
				// $seller_id = $item_details[$i]['seller_id'];
				$type = $item_details[$i]['type'];
				$total_stock = $item_details[$i]['stock'];
				$quantity = $quantity_arr[$i];
				// print_r($item_details[0]['price']);
				$price = $item_details[$i]['discounted_price']==0?$item_details[$i]['price']:$item_details[$i]['discounted_price'];
				$message .= "<b>Nombre : </b>".$item_details[$i]['name']."<b> Cantidad :</b>".$quantity."<b> Subtotal :</b>".$price*$quantity."<br>";
			}
			$message .= "<b>Cantidad total : </b>".$total." <b>Comision de envío : </b>".$delivery_charge." <b>Importe del impuesto : </b>".$tax_amount." <b>Descuento : </b>".$discount." <b>Saldo utilizada : </b>".$wallet_balance." <b>Total :</b>".$final_total;
			$message .= "<br>Metodo de pago: ".$payment_method;
			$message .= "<br><br>T¡Gracias por hacer un pedido con nosotros! <br><br> ¡Recibirá futuras actualizaciones de su pedido por correo electrónico!";
			send_email($to,$subject,$message);

            $subject = "Nuevo pedido realizado para $app_name";
			$message = "Nuevo pedido ID : #".$response['order_id']." recibido por favor tome nota de ello y continúe";
			send_email($support_email,$subject,$message);
			// sendSms($mobile,$message,$country_code);
			print_r(json_encode($response));
		}else{
			$response['error'] = "true";
			$response['message'] = "No se pudo realizar el pedido. ¡Inténtalo de nuevo!";
			$response['order_id'] = 0;
			$_SESSION['checkout']['order_id'] = 0;
			print_r(json_encode($response));
		}

	}elseif(isset($_POST['place_order']) && isset($_POST['user_id']) && empty(json_decode($_POST['product_variant_id']))){
		$response['error'] = "true";
		$response['message'] = "¡El pedido sin artículos en el carrito no se puede realizar!";
		$response['order_id'] = 0;
		$_SESSION['checkout']['order_id'] = 0;
		print_r(json_encode($response));
	
}
if(isset($_POST['get_orders']) && isset($_POST['user_id'])) {
    if(!verify_token()){
        return false;
    }
	$user_id = $db->escapeString($_POST['user_id']);
	$limit = (isset($_POST['limit']) && !empty($_POST['limit']) && is_numeric($_POST['limit']))?$_POST['limit']:10;
	$offset = (isset($_POST['offset']) && !empty($_POST['offset']) && is_numeric($_POST['offset']))?$_POST['offset']:0;
    $sql = "select *,(select name from users u where u.id=o.user_id) as user_name from orders o where user_id=".$user_id." ORDER BY date_added DESC LIMIT $offset,$limit";
    $db->sql($sql);
    $res = $db->getResult();
    $i=0; $j=0;
    foreach($res as $row){
        if($row['discount']>0){
            $discounted_amount = $row['total'] * $row['discount'] / 100; /*  */
    	    $final_total = $row['total'] - $discounted_amount;
            $discount_in_rupees = $row['total']-$final_total;
            // echo $discount_in_rupees;
        } else {
            $discount_in_rupees = 0;
        }
        
        $res[$i]['discount_rupees'] = "$discount_in_rupees";
        $final_total = ceil($res[$i]['final_total']);
        $res[$i]['final_total'] = "$final_total";
        $res[$i]['date_added'] = date('d-m-Y h:i:sa', strtotime($res[$i]['date_added']));
        $sql = "select oi.*,p.name,p.image,v.measurement,(select short_code from unit u where u.id=v.measurement_unit_id) as unit from order_items oi join product_variant v on oi.product_variant_id=v.id join products p on p.id=v.product_id where order_id=".$row['id'];
        $db->sql($sql);
        $res[$i]['items'] = $db->getResult();
        $res[$i]['status'] = json_decode($res[$i]['status']);
            
            for($j=0; $j < count($res[$i]['items']); $j++){
                $res[$i]['items'][$j]['status'] = (!empty($res[$i]['items'][$j]['status']))?json_decode($res[$i]['items'][$j]['status']):array();
                // unset($res[$i]['items'][$j]['status']);
                $res[$i]['items'][$j]['image'] = DOMAIN_URL.$res[$i]['items'][$j]['image'];
            }
        $i++;
    }
        $orders = $order = array();
        
        if(!empty($res)){
            $orders['error'] = false;
            $orders['data'] = array_values($res);
            print_r(json_encode($orders));
        }else{
            $res['error'] = true;
            $res['message'] = "No se encontraron pedidos!";
            print_r(json_encode($res));
            // return $res;
        }
}
if(isset($_POST['update_order_item_status']) && isset($_POST['order_item_id'])) {
    if(!verify_token()){
        return false;
    }
	$order_item_id = $db->escapeString($_POST['order_item_id']);
	$order_id = $db->escapeString($_POST['order_id']);
	$postStatus = $db->escapeString($_POST['status']);
	
	$sql = "SELECT COUNT(id) as cancelado FROM `order_items` WHERE id=".$order_item_id." && status LIKE '%$postStatus%'";
	$db->sql($sql);
	$res_cancelled = $db->getResult();
	if($res_cancelled[0]['cancelado']>0){
    	$response['error'] = true;
		$response['message'] = 'No se pudo actualizar el estado del pedido. El artículo ya está '.ucwords($postStatus).'!';
		print_r(json_encode($response));
		return false;
	}
	
	$sql = "SELECT user_id,status,sub_total FROM order_items WHERE id =".$order_item_id;
	$db->sql($sql);
	$result=$db->getResult();
	
    if(!empty($result)){
    	$status = json_decode($result[0]['status']);
    	if($postStatus == 'cancelado'){
    	    $sql = 'SELECT final_total,total,user_id,payment_method,wallet_balance,delivery_charge,tax_amount,status FROM orders WHERE id='.$order_id;
    	    $db->sql($sql);
    	    $res_order = $db->getResult();
    	   // print_r($res_order[0]['total']);
    	    $sql = 'SELECT oi.`product_variant_id`,oi.`quantity`,oi.`discounted_price`,oi.`price`,pv.`product_id`,pv.`type`,pv.`stock`,pv.`stock_unit_id`,pv.`measurement`,pv.`measurement_unit_id` FROM `order_items` oi join `product_variant` pv on pv.id = oi.product_variant_id WHERE oi.`id`='.$order_item_id;
    	    $db->sql($sql);
    	    $res_oi = $db->getResult();
    	    $price = $res_oi[0]['discounted_price']==0?$res_oi[0]['price']:$res_oi[0]['discounted_price'];
    	    $total = $res_order[0]['total'];
    	    $final_total = $res_order[0]['final_total'];
    	    $delivery_charge = $res_order[0]['delivery_charge'];
    	   // echo $total - $price;
    	    if($total - $price >= 0){
    	        $sql_total = "update orders set total=$total-$price where id=".$order_id;
    	        $db->sql($sql_total);
    	    }
    	    $sql = "select total from orders where id=".$order_id;
    	    $db->sql($sql);
    	    $res_total=$db->getResult();
    	    $total = $res_total[0]['total'];
    	    
    	    if($total<$config['min_amount']){
    	        if($delivery_charge==0){
    	            $dchrg = $config['delivery_charge'];
    	            $sql_delivery_chrg = "update orders set delivery_charge=$dchrg where id=".$order_id;
            	   // echo $sql_delivery_chrg;
            	    $db->sql($sql_delivery_chrg);
            	    $sql_final_total = "update orders set final_total=$final_total-$price+$dchrg where id=".$order_id;
    	        }else{
    	            $sql_final_total = "update orders set final_total=$final_total-$price where id=".$order_id;
    	        }
    	        $db->sql($sql_final_total);
   
	        }else{
	            $sql_final_total = "update orders set final_total=$final_total-$price where id=".$order_id;
	        }
	        $db->sql($sql_final_total);
	        if($total==0){
    	        $sql = "update orders set delivery_charge=0,tax_amount=0,tax_percentage=0,final_total=0 where id=".$order_id;
    	        $db->sql($sql);
    	    }



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
        	$status[] = array($postStatus,date("d-m-Y h:i:sa") );
            $currentStatus = $postStatus;
            $data = array(
                'status' => $db->escapeString(json_encode($status)),
                'active_status' => $currentStatus
            );
            $db->update('order_items',$data,'id='.$order_item_id);
            
        	$sql = "SELECT id FROM order_items WHERE order_id=".$order_id;
        	$db->sql($sql);
        	$total = $db->numRows();
        	$sql = "SELECT id FROM `order_items` WHERE order_id=".$order_id." && (`active_status` LIKE '%cancelado%' OR `active_status` LIKE '%devuelto%' )";
        	$db->sql($sql);
        	$cancelado = $db->numRows();
        	if($cancelado==$total){
        	   // print_r($res_order);
        	    if($res_order[0]['payment_method'] != 'cod' && $res_order[0]['payment_method'] !='COD'){
                	/* update user's wallet */
                    $user_id = $res_order[0]['user_id'];
                    $total_amount = $res_order[0]['total']+$res_order[0]['delivery_charge']+$res_order[0]['tax_amount'];
                    $user_wallet_balance = $function->get_wallet_balance($user_id);
                    $new_balance = $user_wallet_balance + $total_amount;
                    // return false;
                    $function->update_wallet_balance($new_balance,$user_id);
                    /* add wallet transaction */
            	    $wallet_txn_id = $function->add_wallet_transaction($user_id,'credit',$total_amount,'Saldo acreditado contra la cancelación del artículo.');
                }else{
                if($res_order[0]['wallet_balance']!=0){
                    /* update user's wallet */
                    $user_id = $res_order[0]['user_id'];
                    // $total = $res[0]['total'];
                    $user_wallet_balance = $function->get_wallet_balance($user_id);
                    $new_balance = ($user_wallet_balance + $res_order[0]['wallet_balance']);
                    // echo $new_balance;
                    $function->update_wallet_balance($new_balance,$user_id);
        	        /* add wallet transaction */
        		    $wallet_txn_id = $function->add_wallet_transaction($user_id,'credit',$res_order[0]['wallet_balance'],'Saldo acreditado contra la cancelación del artículo.');
                }
                    
           }
        	    
            	$data_order = array(
            	    'status' => $db->escapeString(json_encode($status)),
            		'active_status' => $currentStatus
        	        );
                $db->update('orders',$data_order,'id='.$order_id);
        	}
		    
        	$response['error'] = false;
        	$response['message'] = 'Pedido cancelado con éxito!';
        	$response['subtotal'] = $result[0]['sub_total'];
        	print_r(json_encode($response));
        	return false;
    	}
    	if($postStatus == 'devuelto'){
    	    $is_item_delivered = 0;
    	    foreach($status as $each_status){
        		if (in_array('entregado', $each_status)) {
        			$is_item_delivered = 1;
        			$config['max-product-return-days'];
        			$now = time(); // or your date as well
                    $status_date = strtotime($each_status[1]);
                    $datediff = $now - $status_date;
                    
                    $no_of_days = round($datediff / (60 * 60 * 24));
                    if($no_of_days > $config['max-product-return-days']){
                        $response['error'] = true;
            			$response['message'] = 'Oops! Lo sentimos, no puede devolver el artículo ahora. Has cruzado el período de devolución máximo del producto';
            			print_r(json_encode($response));
            			return false;
                    }
        		}
        	}
        	if(!$is_item_delivered){
        	    $response['error'] = true;
    			$response['message'] = 'No se puede devolver el artículo a menos que se entregue!';
    			print_r(json_encode($response));
    			return false;
        	}
        	/* store return request */
        	$function->store_return_request($result[0]['user_id'],$order_id,$order_item_id);
        	
        	$status[] = array($postStatus,date("d-m-Y h:i:sa") );
            $data = array(
                'status' => $db->escapeString(json_encode($status)),
                'active_status' => $postStatus
            );
            $db->update('order_items',$data,'id='.$order_item_id);

		    /* check for other item status and summery of order */
		    $sql = "SELECT id FROM order_items WHERE order_id=".$order_id;
        	$db->sql($sql);
        	$total = $db->numRows();
        	$sql = "SELECT id FROM `order_items` WHERE order_id=".$order_id." && (`active_status` LIKE '%cancelado%' OR `active_status` LIKE '%devuelto%' )";
        	$db->sql($sql);
        	$devuelto = $db->numRows();
        	if($devuelto == $total){
        	    $sql = "SELECT status FROM orders WHERE id =".$order_id;
            	$db->sql($sql);
            	$res = $db->getResult();
            	$status_order=json_decode($res[0]['status']);
            	$status_order[] = array($postStatus,date("d-m-Y h:i:sa") );
            	$data_order = array(
            	    'status' => $db->escapeString(json_encode($status)),
            		'active_status' => $postStatus
            	);
                $db->update('orders',$data_order,'id='.$order_id);
        	}
        	$response['error'] = false;
        	$response['message'] = '¡La solicitud de devolución del artículo de pedido se recibió correctamente! El monto se acreditará a su billetera una vez aprobado.';
        	$response['subtotal'] = $result[0]['sub_total'];
        	print_r(json_encode($response));
        	return false;
    	}
    }else{
	    $response['error'] = true;
    	$response['message'] = 'Artículo de pedido no encontrado!';
    	print_r(json_encode($response));
    	return false;
	}
}

if(isset($_POST['update_order_status']) && isset($_POST['id'])) {
    // if(!verify_token()){
    //     return false;
    // }
    // print_r($_POST);
    // return false;
	$id = $db->escapeString($_POST['id']);
	$postStatus = $db->escapeString($_POST['status']);
	
	if(isset($_POST['delivery_boy_id']) && $_POST['delivery_boy_id'] != ''){
	    $delivery_boy_id = $db->escapeString($_POST['delivery_boy_id']);
	    $sql = "SELECT delivery_boy_id,status FROM `orders` where id=$id";
    	$db->sql($sql);	
	    $res_delivery_boy_id = $db->getResult();
	
        if( ($res_delivery_boy_id[0]['delivery_boy_id'] == 0) 
			|| ( $res_delivery_boy_id[0]['delivery_boy_id'] != $delivery_boy_id && $res_delivery_boy_id[0]['status'] != 'cancelado')){
            $sql_get_name="select name from delivery_boys where id='$delivery_boy_id'";
    		$db->sql($sql_get_name);
    		$delivery_boy_name = $db->getResult();
    		$message_delivery_boy = "Hola ".ucwords($delivery_boy_name[0]['name']).", Tienes un nuevo pedido para entregar. Aqui esta tu pedido ID : #".$id.". Por favor toma nota de ello.";
    		$function->send_notification_to_delivery_boy($delivery_boy_id,"Su nuevo pedido con ID : #$id ha sido ".ucwords($postStatus),$message_delivery_boy,'delivery_boys',$id); 
            $function->store_delivery_boy_notification($delivery_boy_id,$id,"Su nuevo pedido con ID: #$id  ha sido ".ucwords($postStatus),$message_delivery_boy,'order_reward');
        }
		$sql="UPDATE orders SET `delivery_boy_id`='".$delivery_boy_id."' WHERE id=".$id;
		$db->sql($sql);	
	}
	$sql = "SELECT COUNT(id) as cancelado FROM `orders` WHERE id=".$id." && (active_status LIKE '%cancelado%' OR active_status LIKE '%devuelto%')";
	$db->sql($sql);
	$res_cancelled = $db->getResult();
	if($res_cancelled[0]['cancelado']>0){
    	$response['error'] = true;
		$response['message'] = 'No se pudo actualizar el estado del pedido una vez cancelado o devuelto!';
		print_r(json_encode($response));
		return false;
	}
	

	$sql="select user_id,payment_method,wallet_balance,total,delivery_charge,tax_amount,status,active_status,delivery_boy_id from orders where id=".$id;
	$db->sql($sql); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
	$res = $db->getResult();
	if($res[0]['active_status']!='entregado' && $postStatus=='devuelto'){
	    $response['error'] = true;
		$response['message'] = 'No se puede devolver el pedido a menos que se entregue!';
		print_r(json_encode($response));
		return false;
	}
	$sql = "SELECT sub_total FROM order_items WHERE order_id=".$id;
	$db->sql($sql);
	$res_query = $db->getResult();
	$sql = "SELECT COUNT(id) as total FROM `orders` WHERE user_id=".$res[0]['user_id']." && status LIKE '%entregado%'";
	$db->sql($sql);
	$res_count = $db->getResult();
	$sql = "SELECT * FROM `users` WHERE id=".$res[0]['user_id'];
	$db->sql($sql);
	$res_user = $db->getResult();
    if(!empty($res)){
    	$status = json_decode($res[0]['status']);
    	$user_id =  $res[0]['user_id'];
    	foreach($status as $each){
    		if (in_array($postStatus, $each)) {
    			$response['error'] = true;
    			$response['message'] = isset($_POST['delivery_boy_id']) && $_POST['delivery_boy_id'] != '' && ($res[0]['delivery_boy_id']!=0)?'Repartidor actualizado, pedido ya '.$postStatus:'Pedido ya '.$postStatus;
    			print_r(json_encode($response));
    			return false;
    		}
    	}
    	if($postStatus=='cancelado' || $postStatus=='devuelto'){
    	    $sql = 'SELECT oi.`product_variant_id`,oi.`quantity`,pv.`product_id`,pv.`type`,pv.`stock`,pv.`stock_unit_id`,pv.`measurement`,pv.`measurement_unit_id` FROM `order_items` oi join `product_variant` pv on pv.id = oi.product_variant_id WHERE `order_id`='.$id;
    	    $db->sql($sql);
    	    $res_oi = $db->getResult();
    	    for($i=0;$i<count($res_oi);$i++){
        	    if($res_oi[$i]['type']=='packet'){
        	        $sql = "UPDATE product_variant SET stock = stock + ".$res_oi[$i]['quantity']." WHERE id='".$res_oi[$i]['product_variant_id']."'";
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
        	        if($res_oi[$i]['measurement_unit_id'] != $res_oi[$i]['stock_unit_id']){
        	            $stock = $function->convert_to_parent($res_oi[$i]['measurement'],$res_oi[$i]['measurement_unit_id']);
        	            $stock = $stock * $res_oi[$i]['quantity'];
        	            $sql = "UPDATE product_variant SET stock = stock + ".$stock." WHERE product_id='".$res_oi[$i]['product_id']."'";
        	            //echo $sql;
        			    $db->sql($sql);
        	        }else{
        	            $stock = $res_oi[$i]['measurement'] * $res_oi[$i]['quantity'];
        	            $sql = "UPDATE product_variant SET stock = stock + ".$stock." WHERE product_id='".$res_oi[$i]['product_id']."'";
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
    	    }
    	     if($res[0]['payment_method'] != 'cod' && $res[0]['payment_method'] !='COD'){
            	/* update user's wallet */
                $user_id = $res[0]['user_id'];
                $total = $res[0]['total']+$res[0]['delivery_charge']+$res[0]['tax_amount'];
                $user_wallet_balance = $function->get_wallet_balance($user_id);
                $new_balance = $user_wallet_balance + $total;
                // return false;
                $function->update_wallet_balance($new_balance,$user_id);
                /* add wallet transaction */
        	    $wallet_txn_id = $function->add_wallet_transaction($user_id,'credit',$sub_total,'saldo acreditado contra la cancelación del artículo.');
            }else{
            if($res[0]['wallet_balance']!=0){
                /* update user's wallet */
                $user_id = $res[0]['user_id'];
                // $total = $res[0]['total'];
                $user_wallet_balance = $function->get_wallet_balance($user_id);
                $new_balance = ($user_wallet_balance + $res[0]['wallet_balance']);
                $function->update_wallet_balance($new_balance,$user_id);
    	        /* add wallet transaction */
    		    $wallet_txn_id = $function->add_wallet_transaction($user_id,'credit',$sub_total,'Saldo acreditado contra la cancelación del artículo.');
            }
                
       }
    	}
    	
    	if($postStatus=='entregado'){
    		$sql = "SELECT delivery_boy_id,final_total,total FROM orders WHERE id=".$id;
    		$db->sql($sql);
    		$res_boy = $db->getResult();
    		if($res_boy[0]['delivery_boy_id']!=0){
    			$sql = "SELECT bonus,name FROM delivery_boys WHERE id=".$res_boy[0]['delivery_boy_id'];
    			$db->sql($sql);
    			$res_bonus = $db->getResult();
    			$reward = $res_boy[0]['total']/100*$res_bonus[0]['bonus'];
    	    	$sql = "UPDATE delivery_boys SET balance = balance + ceil($reward) WHERE id=".$res_boy[0]['delivery_boy_id'];
    			$db->sql($sql);
    			$comission = $function->add_delivery_boy_commission($delivery_boy_id,'credit',$reward,'Comisión de entrega de pedidos.');
    			
    			$sql = "SELECT value FROM `settings` WHERE variable='currency'";
    			$db->sql($sql);
    			$currency = $db->getResult();
    		    $message_delivery_boy = "Hola ".ucwords($res_bonus[0]['name']).", Aquí está la nueva actualización de su pedido para el ID de pedido: #".$id.". Su comisión de".$reward." is acreditado Por favor toma nota de ello.";
    			$function->send_notification_to_delivery_boy($delivery_boy_id,"Su comisión".$reward." ".$currency[0]['value']." ha sido acreditado","$message_delivery_boy",'delivery_boys',$id);
    			$function->store_delivery_boy_notification($delivery_boy_id,$id,"Su comisión ".$reward." ".$currency[0]['value']." ha sido acreditado",$message_delivery_boy,'order_reward');
    		}
    		if($config['is-refer-earn-on']==1){
    			if($res_boy[0]['total']>=$config['min-refer-earn-order-amount']){
    				if($res_count[0]['total']==0){
    					if($res_user[0]['friends_code'] != ''){
    						if($config['refer-earn-method']=='percentage'){
    							$percentage = $config['refer-earn-bonus'];
    							$bonus_amount = $res_boy[0]['total']/100*$percentage;
    							if($bonus_amount>$config['max-refer-earn-amount']){
    								$bonus_amount = $config['max-refer-earn-amount'];
    							}
    						}else{
    							$bonus_amount = $config['refer-earn-bonus'];
    						}
    						$sql  = "SELECT name,friends_code FROM users WHERE id=".$res[0]['user_id'];
    						$db->sql($sql);
    						$res_data = $db->getResult();
    						
    						$sql = " select id from `users` where `referral_code` = '".$res_data[0]['friends_code']."'";
    						$db->sql($sql);
    						$friend_user = $db->getResult();
    						
    						if(!empty($friend_user))
    						    $function->add_wallet_transaction($friend_user[0]['id'],'credit',floor($bonus_amount),'Recomiende y gane bonos en el primer pedido por '.ucwords($res_data[0]['name']));
    						
    						$sql = "UPDATE users SET balance = balance + floor($bonus_amount) WHERE referral_code='".$res_data[0]['friends_code']."'";
    						$db->sql($sql);
    						
    					}
    
    				}
    
    			}
    		}
    	}
    	$temp=[];
    	foreach($status as $s){
    	    array_push($temp,$s[0]);
    	}
    	$sql = "SELECT id,active_status FROM order_items WHERE order_id=".$id;
        $db->sql($sql);
        $result = $db->getResult();
    	if($postStatus=='cancelado'){
    
    	    if (!in_array('cancelado', $temp)) {
    	        $status[] = array('cancelado',date("d-m-Y h:i:sa") );
    	            $data = array(
    	            'status' => $db->escapeString(json_encode($status)),
    	        );
    	    }
    	    $db->update('orders',$data,'id='.$id);
    
    	    foreach($result as $item){
    	        if($item['active_status'] != 'cancelado'){
    	            $item_data = array(
    	            'status' => $db->escapeString(json_encode($status)),
        	        'active_status' => 'cancelado'
    	            );
    	        $db->update('order_items',$item_data,'id='.$item['id']);
    	        }
    	    }
    	}
    	
    	if($postStatus=='procesado'){
    	    if (!in_array('procesado', $temp)) {
    	        $status[] = array('procesado',date("d-m-Y h:i:sa") );
    	        $data = array(
    	            'status' => $db->escapeString(json_encode($status))
    	       );
    	    }
    	    $db->update('orders',$data,'id='.$id);
    	    foreach($result as $item){
    	        $item_data = array(
    	            'status' => $db->escapeString(json_encode($status)),
        	        'active_status' => 'procesado'
    	            );
    	        if($item['active_status'] != 'cancelado'){
    	             $db->update('order_items',$item_data,'id='.$item['id']);
    	        }
    	    }
    	}
    	if($postStatus=='enviado'){
    	    if (!in_array('procesado', $temp)) {
    	        $status[] = array('procesado',date("d-m-Y h:i:sa") );
    	        $data = array('status' => $db->escapeString(json_encode($status)));
    	    }
    	    if (!in_array('enviado', $temp)) {
    	        $status[] = array('enviado',date("d-m-Y h:i:sa") );
    	        $data = array('status' => $db->escapeString(json_encode($status)));
    	    }
    	    $db->update('orders',$data,'id='.$id);
    	    foreach($result as $item){
    	        $item_data = array(
                'status' => $db->escapeString(json_encode($status)),
    	        'active_status' => 'enviado'
    	            );
    	        if($item['active_status'] != 'cancelado'){
    	             $db->update('order_items',$item_data,'id='.$item['id']);
    	        }
    	    }
    	}
    	if($postStatus=='entregado'){
    	    if (!in_array('procesado', $temp)) {
    	        $status[] = array('procesado',date("d-m-Y h:i:sa") );
    	        $data = array('status' => $db->escapeString(json_encode($status)));
    	    }
    	    if (!in_array('enviado', $temp)) {
    	        $status[] = array('enviado',date("d-m-Y h:i:sa") );
    	        $data = array('status' => $db->escapeString(json_encode($status)));
    	    
    	    }
    	    if (!in_array('entregado', $temp)) {
    	        $status[] = array('entregado',date("d-m-Y h:i:sa") );
    	        $data = array('status' => $db->escapeString(json_encode($status)));
    	    
    	    }
    	    $db->update('orders',$data,'id='.$id);
        	 $item_data = array(
                'status' => $db->escapeString(json_encode($status)),
                'active_status' => 'entregado'
             );
    	    foreach($result as $item){
    	        
    	        if($item['active_status'] != 'cancelado'){
    	             $db->update('order_items',$item_data,'id='.$item['id']);
    	        }
    	    } 
    	}
    	if($postStatus=='devuelto'){
             $status[] = array('devuelto',date("d-m-Y h:i:sa") );
             $data = array('status' => $db->escapeString(json_encode($status)));
    	     $db->update('orders',$data,'id='.$id);
        	 $item_data = array(
                'status' => $db->escapeString(json_encode($status)),
                'active_status' => 'devuelto'
             );
    	    foreach($result as $item){
    	        
    	        if($item['active_status'] != 'cancelado' && $item['active_status']=='entregado'){
    	             $db->update('order_items',$item_data,'id='.$item['id']);
    	        }
    	    } 
    	}
    	$i = sizeof($status);
        $currentStatus = $status[$i-1][0];
        $final_status = array(
        	'active_status' => $currentStatus
    	);
    	//$db->update('order_items',$final_status,'order_id='.$id);
     	if($db->update('orders',$final_status,'id='.$id)){// Table name, column names and respective values
    		$response['error'] = false;
    		if($postStatus=='cancelado'){$response['message'] = "el pedido ha sido cancelada!";}
    		elseif($postStatus=='devuelto'){$response['message'] = "El pedido ha sido devuelto!";}
    		else{$response['message'] = "Pedido actualizado correctamente.";}
    		    
    		$res = $db->getResult();
    		
    		$sql = "select name,email,mobile,country_code from `users` where id=".$user_id;
    // 		echo $sql;
    		$db->sql($sql);
    		$res_user = $db->getResult();
    		
    		$to = $res_user[0]['email'];
    		$mobile = $res_user[0]['mobile'];
    		$country_code = $res_user[0]['country_code'];
    // 		echo $country_code;
    		$subject = "Su pedido ha sido ".ucwords($postStatus);
    		$message = "Hola ".ucwords($res_user[0]['name']).", Aquí está la nueva actualización de su pedido. ID : #".$id.". Su pedido ha sido ".ucwords($postStatus).". Por favor toma nota de ello.";
    		$message .= "¡Gracias por usar nuestros servicios! Recibirá futuras actualizaciones de su pedido por correo electrónico!";
    		$function->send_order_update_notification($user_id,"Su pedido ha sido ".ucwords($postStatus),$message_delivery_boy,'order');

    		if(isset($_POST['delivery_boy_id']) && $_POST['delivery_boy_id'] != ''){
        		$sql1 = "select name from `delivery_boys` where id=".$delivery_boy_id;
        // 		echo $sql;
        		$db->sql($sql1);
        		$res_delivery_boy = $db->getResult();
        		$message_delivery_boy = "Hola ".ucwords($res_delivery_boy[0]['name']).", Aquí está la nueva actualización de su pedido. ID : #".$id.". Su pedido ha sido ".ucwords($postStatus).". Por favor toma nota de ello.";
     
        		$function->send_notification_to_delivery_boy($delivery_boy_id,"Su pedido ha sido ".ucwords($postStatus),"$message_delivery_boy",'delivery_boys',$id); 
        		$function->store_delivery_boy_notification($delivery_boy_id,$id,"Su pedido ha sido ".ucwords($postStatus),$message_delivery_boy,'order_status');
    		}
    		send_email($to,$subject,$message);
    		$message = "Hola ".ucwords($res_user[0]['name']).", Aquí está la nueva actualización de su pedido.ID : #".$id.". Su pedido ha sido".ucwords($postStatus).". Por favor toma nota de ello.";
    		$message .= "¡Gracias por usar nuestros servicios! Contáctenos para más información.";
    		// sendSms($mobile,$message,$country_code);
    		
    		print_r(json_encode($response));
    	} else {
    		$response['error'] = true;
    		$response['message'] = isset($_POST['delivery_boy_id']) && $_POST['delivery_boy_id'] != ''?'repartidor actualizado, pero no se pudo actualizar el estado del pedido, intente nuevamente!':'No se pudo actualizar el estado del pedido, intente nuevamente!';
    		print_r(json_encode($response));
    	}
    }else{
		$response['error'] = true;
		$response['message'] = "Lo sentimos, pedido inválida ID";
		print_r(json_encode($response));
	}
}

if(isset($_POST['get_settings'])) {
    if(!verify_token()){
        return false;
    }
	$sql = "select value from `settings` where variable='system_timezone'";
	$db->sql($sql);
	$res = $db->getResult();
	$sql = "select value from `settings` where variable='currency'";
	$db->sql($sql);
	$res_currency = $db->getResult();
	   if(!empty($res)){
            $response['error'] = false;
            $response['settings'] = json_decode($res[0]['value'],1);
            $response['settings']['currency'] = $res_currency[0]['value'];
            $response['settings']['delivery_charge'] = empty($response['settings']['delivery_charge'])?"0":$response['settings']['delivery_charge'];
            $response['settings']['min-refer-earn-order-amount'] = empty($response['settings']['min-refer-earn-order-amount'])?"0":$response['settings']['min-refer-earn-order-amount'];
            $response['settings']['min_amount'] = empty($response['settings']['min_amount'])?"0":$response['settings']['min_amount'];
            $response['settings']['max-refer-earn-amount'] = empty($response['settings']['max-refer-earn-amount'])?"0":$response['settings']['max-refer-earn-amount'];
            $response['settings']['minimum-withdrawal-amount'] = empty($response['settings']['minimum-withdrawal-amount'])?"0":$response['settings']['minimum-withdrawal-amount'];
            $response['settings']['refer-earn-bonus'] = empty($response['settings']['refer-earn-bonus'])?"0":$response['settings']['refer-earn-bonus'];
            $response['settings']['current_version'] = empty($response['settings']['current_version'])?"0":$response['settings']['current_version'];
            $response['settings']['minimum_version_required'] = empty($response['settings']['minimum_version_required'])?"0":$response['settings']['minimum_version_required'];
            print_r(json_encode($response));
            
        }else{
            $response['error'] = true;
            $response['settings'] = "No se encontraron configuraciones!";
            $response['message'] = "Algo salió mal!";
            print_r(json_encode($response));
            
        }
}

if(isset($_POST['update_order_total_payable']) && isset($_POST['id'])){
	
	$id = $db->escapeString($_POST['id']);
	$discount = $db->escapeString($_POST['discount']);
	$deliver_by = $db->escapeString($_POST['deliver_by']);
	$total_payble = $db->escapeString($_POST['total_payble']);
	$total_payble = round($total_payble,2);
	// echo $total_payble;
	$data = array(
		'discount' =>$discount,
		'deliver_by' => $deliver_by,
	);
	$data1 = array(
		 'discount' =>$discount,
		 'final_total' => $total_payble,
// 		 'total' => $total_payble,
	);

	
	if($discount >= 0){
	    $db->update('order_items',$data,'order_id='.$id);
	    $db->update('orders',$data1,'id='.$id);  // Table name, column names and respective values
    	$res = $db->getResult();
    	if(!empty($res)){
    		// print_r($res);
    	    
        	$response['error'] = false;
            $response['message'] = "Pedido actualizado correctamente.";
            print_r(json_encode($response));
    	}else{
            $response['error'] = true;
            $response['message'] = "No se pudo actualizar el pedido. Inténtalo de nuevo!";
            print_r(json_encode($response));
    	}
	}
}


if(isset($_POST['add_transaction']) && $_POST['add_transaction'] == true){
    if(!verify_token()){
        return false;
    }
	/*add data to transaction table*/
	
	$user_id = $_POST['user_id'];
	$order_id = $_POST['order_id'];
	$type = $_POST['type'];
	$txn_id = $_POST['txn_id'];
	$amount = $_POST['amount'];
	$status = $db->escapeString($_POST['status']);
	$message = $db->escapeString($_POST['message']);
	$transaction_date = (isset($_POST['addedon']) && !empty($_POST['addedon']))?$db->escapeString($_POST['addedon']):date('Y-m-d H:i:s');
	$data = array(
		'user_id' =>$user_id,
		'order_id' =>$order_id,
		'type' => $type,
		'txn_id' => $txn_id,
		'amount' => $amount,
		'status' => $status,
		'message' => $message,
		'transaction_date' => $transaction_date
	);	
	$db->insert('transactions',$data);  // Table name, column names and respective values
	$res = $db->getResult();
	$response['error'] = false;
	$response['transaction_id'] = $res[0];
	$response['message'] = "Transacción agregada exitosamente!";
	echo json_encode($response);
}
?>