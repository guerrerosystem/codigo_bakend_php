<?php
	session_start();
    
    // set time for session timeout
    $currentTime = time() + 25200;
    $expired = 3600;
    if(!isset($_SESSION['delivery_boy_id']) && !isset($_SESSION['name'])){
    	header("location:index.php");
    }else{
		$id = $_SESSION['delivery_boy_id'];	
    }
    
 
    if ($currentTime > $_SESSION['timeout']) {
        session_destroy();
        header("location:index.php");
    }
    
    // destroy previous session timeout and create new one
    unset($_SESSION['timeout']);
    $_SESSION['timeout'] = $currentTime + $expired;
    
	header("Content-Type: application/json");
    header("Expires: 0");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
	
	
	include_once('../includes/custom-functions.php');
	$fn = new custom_functions;
	include_once('../includes/crud.php');
	include_once('../includes/variables.php');
	$db = new Database();
	$db->connect();
	$config = $fn->get_configurations();
		if(isset($config['system_timezone']) && isset($config['system_timezone_gmt'])){
			date_default_timezone_set($config['system_timezone']);
			$db->sql("SET `time_zone` = '".$config['system_timezone_gmt']."'");
		}else{
	date_default_timezone_set('Asia/Kolkata');
	$db->sql("SET `time_zone` = '+05:30'");
}
	
	//data of 'ORDERS' table goes here
	if(isset($_GET['table']) && $_GET['table'] == 'orders'){
		$offset = 0; $limit = 10;
		$sort = 'o.id'; $order = 'DESC';
		$where = ' ';
		if(!empty($_GET['start_date']) && !empty($_GET['end_date'])){
			$where .= " where DATE(date_added)>=DATE('".$_GET['start_date']."') AND DATE(date_added)<=DATE('".$_GET['end_date']."')";
		}
		if(isset($_GET['sort']))
			$sort = $_GET['sort'];
		if(isset($_GET['offset']))
			$offset = $_GET['offset'];
		if(isset($_GET['limit']))
			$limit = $_GET['limit'];
		if(isset($_GET['order']))
			$order = $_GET['order'];
		if(isset($_GET['search']) && !empty($_GET['search'])){
			$search = $_GET['search'];
			if(!empty($_GET['start_date']) && !empty($_GET['end_date'])){
				$where .= " AND (name like '%".$search."%' OR o.id like '%".$search."%' OR o.mobile like '%".$search."%' OR address like '%".$search."%' OR `payment_method` like '%".$search."%' OR `delivery_charge` like '%".$search."%' OR `delivery_time` like '%".$search."%' OR o.`status` like '%".$search."%' OR `date_added` like '%".$search."%')";
			} else{
				$where .= " where (name like '%".$search."%' OR o.id like '%".$search."%' OR o.mobile like '%".$search."%' OR address like '%".$search."%' OR `payment_method` like '%".$search."%' OR `delivery_charge` like '%".$search."%' OR `delivery_time` like '%".$search."%' OR o.`status` like '%".$search."%' OR `date_added` like '%".$search."%')";
			}
		}
        if(isset($_GET['filter_order']) && $_GET['filter_order']!=''){
            $filter_order=$db->escapeString($_GET['filter_order']);
            if(isset($_GET['search']) && $_GET['search']!='' ){
                 $where .=" and `active_status`='".$filter_order."'";
            }elseif(isset($_GET['start_date']) && $_GET['start_date']!=''){
                 $where .=" and `active_status`='".$filter_order."'";
            }else{
                 $where .=" where `active_status`='".$filter_order."'";
            }
            
  
        }
        if(empty($where)){
			$where .= " WHERE delivery_boy_id = ".$id;
		}else{
			$where .= " AND delivery_boy_id = ".$id;
		}

		$sql = "SELECT COUNT(o.id) as total FROM `orders` o JOIN users u ON u.id=o.user_id".$where;
// 		echo $sql;
		$db->sql($sql);
		$res = $db->getResult();
		foreach($res as $row){
			$total = $row['total'];
		}
		$sql="select o.*,u.name FROM orders o JOIN users u ON u.id=o.user_id".$where." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit;
// 		echo $sql;
		$db->sql($sql);
		$res = $db->getResult();
// 		print_r($res);
		;
		for($i=0;$i<count($res);$i++) {
			$sql="select oi.*,p.name as name, u.name as uname,v.measurement, (SELECT short_code FROM unit un where un.id=v.measurement_unit_id)as mesurement_unit_name,(SELECT status FROM orders o where o.id=oi.order_id)as order_status from `order_items` oi 
			    join product_variant v on oi.product_variant_id=v.id 
			    join products p on p.id=v.product_id 
			    JOIN users u ON u.id=oi.user_id 
			    where oi.order_id=".$res[$i]['id'];
    		$db->sql($sql);
    		$res[$i]['items'] = $db->getResult();
    // 		print_r($res[$i]['items']);
	    }
		$bulkData = array();
		$bulkData['total'] = $total;
		$rows = array();
		$tempRow = array();
		// print_r($res);
		foreach($res as $row){
			$items = $row['items'];
// 			print_r($items);
			$items1='';
			$temp = '';
			$total_amt=0;
			foreach($items as $item){
				$temp .= "<b>ID :</b>".$item['id']."<b> ID  variante  producto :</b> ".$item['product_variant_id']."<b> Nombre : </b>".$item['name']." <b>Unidad : </b>".$item['measurement'].$item['mesurement_unit_name']." <b>Precio : </b>".$item['price']." <b>Cantidad : </b>".$item['quantity']." <b>Subtotal : </b>".$item['quantity']*$item['price']."<br>------<br>";
				$total_amt += $item['sub_total'];
			}

			$items1 = $temp;
			$temp = '';
			$status = json_decode($row['items'][0]['order_status']);
			if(!empty($status)){
    			foreach($status as $st){
    				$temp .= $st[0]." : ".$st[1]."<br>------<br>";
    			}
			}
			if($row['active_status']=='recibido'){
                $active_status = '<label class="label label-primary">'.$row['active_status'].'</label>';
            }
            if($row['active_status']=='procesado'){
                $active_status = '<label class="label label-info">'.$row['active_status'].'</label>';
            }
            if($row['active_status']=='enviado'){
                $active_status = '<label class="label label-warning">'.$row['active_status'].'</label>';
            }
            if($row['active_status']=='entregado'){
                $active_status = '<label class="label label-success">'.$row['active_status'].'</label>';
            }
            if($row['active_status']=='devuelto' || $row['active_status'] == 'cancelado' ){
                $active_status = '<label class="label label-danger">'.$row['active_status'].'</label>';
            }
			// print_r($res[0]['items']);
// 			$total = ($total_amt + $row['delivery_charge']) - $row['wallet_balance'];
// 			$discounted_amount = $total*$row['items'][0]['discount']/100;
			$status = $temp;
			$operate = "<a class='btn btn-sm btn-primary edit-fees' data-id='".$row['id']."' data-toggle='modal' data-target='#editFeesModal'>Edit</a>";
			
			$operate .= "<a onclick='return conf(\"delete\");' class='btn btn-sm btn-danger' href='../public/db_operations.php?id=".$row['id']."&delete_order=1' target='_blank'>Eliminar</a>";
			$discounted_amount = $row['total'] * $row['items'][0]['discount'] / 100; /*  */
    	    $final_total = $row['total'] - $discounted_amount;
            $discount_in_rupees = $row['total']-$final_total;
            $discount_in_rupees = floor($discount_in_rupees);
			$tempRow['id'] = $row['id'];
			$tempRow['user_id'] = $row['user_id'];
			$tempRow['name'] = $row['items'][0]['uname'];
			$tempRow['mobile'] = $row['mobile'];
			$tempRow['delivery_charge'] = $row['delivery_charge'];
			$tempRow['items']=$items1;
			$tempRow['total']=$row['total'];
			$tempRow['tax']=$row['tax_amount'].'('.$row['tax_percentage'].'%)';
			$tempRow['promo_discount']=$row['promo_discount'];
			$tempRow['wallet_balance']=$row['wallet_balance'];
			$tempRow['discount'] = $discount_in_rupees.'('.$row['items'][0]['discount'].'%)';
			$tempRow['qty'] = $row['items'][0]['quantity'];
			// 	$tempRow['final_total'] = $row['final_total'];
// 			$tempRow['final_total'] = ceil($total-$discounted_amount);
			$tempRow['final_total'] = ceil($row['final_total']);
			$tempRow['promo_code'] = $row['promo_code'];
			$tempRow['deliver_by'] = $row['items'][0]['deliver_by'];
			$tempRow['payment_method'] = $row['payment_method'];
			$tempRow['address'] = $row['address'];
			$tempRow['delivery_time'] = $row['delivery_time'];
			// $tempRow['items'] = $items;
			$tempRow['status'] = $status;
			$tempRow['active_status'] = $active_status;
			$tempRow['wallet_balance'] = $row['wallet_balance'];
			$tempRow['date_added'] = date('d-m-Y',strtotime($row['date_added']));
			$tempRow['operate'] = '<a href="detalle-pedido.php?id='.$row['id'].'"><i class="fa fa-eye"></i> View</a>
				<br><a href="eliminar-pedido.php?id='.$row['id'].'"><i class="fa fa-trash"></i> Eliminar</a>';
			$rows[] = $tempRow;
		}
		$bulkData['rows'] = $rows;
		print_r(json_encode($bulkData));
	}
	
	// data of 'Fund Transfer' table goes here
	if(isset($_GET['table']) && $_GET['table'] == 'fund-transfers'){
		
		$offset = 0; $limit = 10;
		$sort = 'id'; $order = 'DESC';
		$where = '';
		if(isset($_GET['offset']))
			$offset = $_GET['offset'];
		if(isset($_GET['limit']))
			$limit = $_GET['limit'];
		
		if(isset($_GET['sort']))
			$sort = $_GET['sort'];
		if(isset($_GET['order']))
			$order = $_GET['order'];
		
		if(isset($_GET['search']) && $_GET['search'] !=''){
			$search = $_GET['search'];
			$where = " Where f.`id` like '%".$search."%' OR f.`delivery_boy_id` like '%".$search."%' OR d.`name` like '%".$search."%' OR f.`message` like '%".$search."%' OR d.`mobile` like '%".$search."%' OR d.`address` like '%".$search."%' OR f.`opening_balance` like '%".$search."%' OR f.`closing_balance` like '%".$search."%' OR d.`balance` like '%".$search."%' OR f.`date_created` like '%".$search."%'" ;
		}
		if(empty($where)){
			$where .= " WHERE delivery_boy_id = ".$id;
		}else{
			$where .= " AND delivery_boy_id = ".$id;
		}
		
		$sql = "SELECT COUNT(*) as total FROM `fund_transfers` f JOIN `delivery_boys` d ON f.delivery_boy_id=d.id".$where;
//  		echo $sql;
		$db->sql($sql);
		$res = $db->getResult();
		foreach($res as $row)
			$total = $row['total'];
		
		$sql = "SELECT f.*,d.name,d.mobile,d.address FROM `fund_transfers` f JOIN `delivery_boys` d ON f.delivery_boy_id=d.id ".$where." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit;
// 		echo $sql;
		$db->sql($sql);
		$res = $db->getResult();
		
		$bulkData = array();
		$bulkData['total'] = $total;
		$rows = array();
		$tempRow = array();
		
		foreach($res as $row){
			
			
			$tempRow['id'] = $row['id'];
			$tempRow['name'] = $row['name'];
			$tempRow['mobile'] = $row['mobile'];
			$tempRow['address'] = $row['address'];
			$tempRow['delivery_boy_id'] = $row['delivery_boy_id'];
			$tempRow['opening_balance'] = $row['opening_balance'];
			$tempRow['closing_balance'] = $row['closing_balance'];
			$tempRow['status'] = $row['status'];
			$tempRow['message'] = $row['message'];
			$tempRow['date_created'] = $row['date_created'];
			// $tempRow['mobile'] = $row['mobile'];
			// $tempRow['address'] = $row['address'];
			// $tempRow['bonus'] = $row['bonus'];
			// if($row['status']==0)
			//     $tempRow['status']="<label class='label label-danger'>Deactive</label>";
   //          else
   //              $tempRow['status']="<label class='label label-success'>Active</label>";
			// $tempRow['operate'] = $operate;
			$rows[] = $tempRow;
		}
		$bulkData['rows'] = $rows;
		print_r(json_encode($bulkData));
	}
?>