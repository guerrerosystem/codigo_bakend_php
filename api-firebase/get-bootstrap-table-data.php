<?php
	session_start();
    
    // set time for session timeout
    $currentTime = time() + 25200;
    $expired = 3600;
    
    // if session not set go to login page
    if (!isset($_SESSION['user'])) {
        header("location:index.php");
    }
    
    // if current time is more than session timeout back to login page
    if ($currentTime > $_SESSION['timeout']) {
        session_destroy();
        header("location:index.php");
    }
    
    // destroy previous session timeout and create new one
    unset($_SESSION['timeout']);
    $_SESSION['timeout'] = $currentTime + $expired;
    
    header('Access-Control-Allow-Origin: *');
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
		$sql = "SELECT COUNT(o.id) as total FROM `orders` o JOIN users u ON u.id=o.user_id".$where;
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
				$temp .= "<b>ID :</b>".$item['id']."<b> ID producto variant:</b> ".$item['product_variant_id']."<b> Nombre : </b>".$item['name']." <b>Unidad : </b>".$item['measurement'].$item['mesurement_unit_name']." <b>Precio : </b>".$item['price']." <b>Cantidad : </b>".$item['quantity']." <b>Subtotal : </b>".$item['quantity']*$item['price']."<br>------<br>";
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
			
			$operate .= "<a onclick='return conf(\"delete\");' class='btn btn-sm btn-danger' href='../public/db_operations.php?id=".$row['id']."&delete_order=1' target='_blank'>Delete</a>";
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
			$tempRow['operate'] = '<a href="detalle-pedido.php?id='.$row['id'].'"><i class="fa fa-eye"></i> Ver</a>
				<br><a href="eliminar-pedido.php?id='.$row['id'].'"><i class="fa fa-trash"></i> Eliminar</a>';
			$rows[] = $tempRow;
		}
		$bulkData['rows'] = $rows;
		print_r(json_encode($bulkData));
	}
	
	// data of 'CATEGORY' table goes here
	if(isset($_GET['table']) && $_GET['table'] == 'category'){
		
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
		
		if(isset($_GET['search'])){
			$search = $_GET['search'];
			$where = " Where `id` like '%".$search."%' OR `name` like '%".$search."%' OR `subtitle` like '%".$search."%' OR `image` like '%".$search."%'";
		}
		
		$sql = "SELECT COUNT(*) as total FROM `category` ".$where;
		$db->sql($sql);
		$res = $db->getResult();
		foreach($res as $row)
			$total = $row['total'];
		
		$sql = "SELECT * FROM `category` ".$where." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit;
		$db->sql($sql);
		$res = $db->getResult();
		
		$bulkData = array();
		$bulkData['total'] = $total;
		$rows = array();
		$tempRow = array();
		
		foreach($res as $row){
			
			$operate = '<a href="ver-subcategoria.php?id='.$row['id'].'"><i class="fa fa-folder-open-o"></i>View Subcategories</a>';
			$operate .= ' <a href="editar-categoria.php?id='.$row['id'].'"><i class="fa fa-edit"></i>Editar</a>';
			$operate .= ' <a class="btn-xs btn-danger" href="eliminar-categoria.php?id='.$row['id'].'"><i class="fa fa-trash-o"></i>Eliminar</a>';
			
			$tempRow['id'] = $row['id'];
			$tempRow['name'] = $row['name'];
			$tempRow['subtitle'] = $row['subtitle'];
			$tempRow['image'] = "<a data-lightbox='category' href='".$row['image']."' data-caption='".$row['name']."'><img src='".$row['image']."' title='".$row['name']."' height='50' /></a>";
			$tempRow['operate'] = $operate;
			$rows[] = $tempRow;
		}
		$bulkData['rows'] = $rows;
		print_r(json_encode($bulkData));
	}
	
	// data of 'SUBCATEGORY' table goes here
	if(isset($_GET['table']) && $_GET['table'] == 'subcategory'){
		
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
		
		if(isset($_GET['search'])){
			$search = $_GET['search'];
			$where = " Where s.`id` like '%".$search."%' OR `name` like '%".$search."%' OR `subtitle` like '%".$search."%' OR `image` like '%".$search."%'";
		}
		
// 		$sql = "SELECT COUNT(*) as total FROM `subcategory` ".$where;
		$sql = "SELECT COUNT(*) as total FROM `subcategory` s".$where;
		$db->sql($sql);
		$res = $db->getResult();
		foreach($res as $row)
			$total = $row['total'];
		
		$sql = "SELECT s.*,(SELECT name FROM category c WHERE c.id=s.category_id) as category_name FROM `subcategory` s".$where." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit;
		$db->sql($sql);
		$res = $db->getResult();
		$bulkData = array();
		$bulkData['total'] = $total;
		$rows = array();
		$tempRow = array();
		
		foreach($res as $row){
			
			$operate = '<a href="ver-subcategoria-producto.php?id='.$row['id'].'"><i class="fa fa-folder-open-o"></i>Ver Productos</a>';
			$operate .= ' <a href="editar-subcategoria.php?id='.$row['id'].'"><i class="fa fa-edit"></i>Editar</a>';
			$operate .= ' <a class="btn-xs btn-danger" href="eliminar-subcategoria.php?id='.$row['id'].'"><i class="fa fa-trash-o"></i>Eliminar</a>';
			$tempRow['id'] = $row['id'];
			$tempRow['name'] = $row['name'];
			$tempRow['category_name'] = $row['category_name'];
			$tempRow['subtitle'] = $row['subtitle'];
			$tempRow['image'] = "<a data-lightbox='category' href='".$row['image']."' data-caption='".$row['name']."'><img src='".$row['image']."' title='".$row['name']."' height='50' /></a>";
			$tempRow['operate'] = $operate;
			$rows[] = $tempRow;
		}
		$bulkData['rows'] = $rows;
		print_r(json_encode($bulkData));
	}
	
	// data of 'PRODUCTS' table goes here

	if(isset($_GET['table']) && $_GET['table'] == 'products'){
// 		print_r($_GET);
		
		$offset = 0; $limit = 10;
		$sort = 'id'; $order = 'ASC';
		$where = '';
		
		if(isset($_GET['offset']))
			$offset = $_GET['offset'];
		if(isset($_GET['limit']))
			$limit = $_GET['limit'];
		
		if(isset($_GET['sort']))
		if($_GET['sort']=='id'){
		    $sort="id";
		}else{
			$sort = $_GET['sort'];
		}
		if(isset($_GET['order']))
			$order = $_GET['order'];
		
		if(isset($_GET['search']) AND $_GET['search']!=''){
			$search = $_GET['search'];
			$where = " where (p.`id` like '%".$search."%' OR p.`name` like '%".$search."%' OR pv.`measurement` like '%".$search."%' OR u.`short_code` like '%".$search."%' )";
		}

		if(isset($_GET['category_id']) && $_GET['category_id'] !=''){
			$category_id = $_GET['category_id'];
			if(isset($_GET['search']) AND $_GET['search']!='')
				$where .=' and p.`category_id`='.$category_id;
			else
				$where =' where p.`category_id`='.$category_id;
		}
		
		$join = "JOIN `product_variant` pv ON pv.product_id = p.id
            LEFT JOIN `unit` u ON u.id = pv.measurement_unit_id";
		
		$sql = "SELECT COUNT(p.id) as `total` FROM `products` p $join ".$where."" ;
// 		echo $sql;
		$db->sql($sql);
		$res = $db->getResult();
		foreach($res as $row)
			$total = $row['total'];
		
// 		$sql = "SELECT * FROM products ".$where." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit;
        $sql = "SELECT p.id AS id, p.name, p.image, pv.price, pv.discounted_price, pv.measurement, pv.serve_for, pv.stock, u.short_code 
            FROM `products` p
            $join 
            $where ORDER BY $sort $order LIMIT $offset, $limit";
        // echo $sql;
		$db->sql($sql);
		$res = $db->getResult();
		// print_r($res);
		$bulkData = array();
		$bulkData['total'] = $total;
		$rows = array();
		$tempRow = array();
		
		$currency = $fn->get_settings('currency',false);
		
		foreach($res as $row){
			
			$operate = '<a href="ver-variantes-producto.php?id='.$row['id'].'"><i class="fa fa-folder-open"></i>View</a>';
			$operate .= ' <a href="editar-producto.php?id='.$row['id'].'"><i class="fa fa-edit"></i>Editar</a>';
			$operate .= ' <a class="btn-xs btn-danger" href="eliminar-producto.php?id='.$row['id'].'"><i class="fa fa-trash-o"></i>Eliminar</a>';
			
			$tempRow['id'] = $row['id'];
			$tempRow['name'] = $row['name'];
			$tempRow['measurement'] = $row['measurement']." ".$row['short_code'];
			$tempRow['price'] = $currency." ".$row['price'];
			$tempRow['discounted_price'] = $currency." ".$row['discounted_price'];
			$tempRow['serve_for'] = $row['serve_for'];
			$tempRow['stock'] = $row['stock'];
			$tempRow['image'] = "<a data-lightbox='product' href='".$row['image']."' data-caption='".$row['name']."'><img src='".$row['image']."' title='".$row['name']."' height='50' /></a>";
			$tempRow['operate'] = $operate;
			$rows[] = $tempRow;
		}
		$bulkData['rows'] = $rows;
		print_r(json_encode($bulkData));
	}
	
	// data of 'USERS' table goes here
	if(isset($_GET['table']) && $_GET['table'] == 'users'){
		
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
		
		if(isset($_GET['search'])){
			$search = $_GET['search'];
			$where = " Where `id` like '%".$search."%' OR `name` like '%".$search."%' OR `email` like '%".$search."%' OR `mobile` like '%".$search."%' OR `city` like '%".$search."%' OR `area` like '%".$search."%' OR `street` like '%".$search."%' OR `status` like '%".$search."%' OR `created_at` like '%".$search."%'";
		}
		if(isset($_GET['filter_order_status']) && $_GET['filter_order_status'] !=''){
			$filter_order = $_GET['filter_order'];
			if(isset($_GET['search']) AND $_GET['search']!='')
				$where .=' and active_status='.$filter_order;
			else
				$where =' where active_status='.$filter_order;
		}
		
		$sql = "SELECT COUNT(*) as total FROM `users` ".$where;
		$db->sql($sql);
		$res = $db->getResult();
		// print_r($res);
		foreach($res as $row)
			$total = $row['total'];
		
		$sql = "SELECT *,(SELECT name FROM area a WHERE a.id=u.area) as area_name,(SELECT name FROM city c WHERE c.id=u.city) as city_name FROM `users` u ".$where." ORDER BY `".$sort."` ".$order." LIMIT ".$offset.", ".$limit;
		$db->sql($sql);
		$res = $db->getResult();
// 		print_r($res);
		
		$bulkData = array();
		$bulkData['total'] = $total;
		$rows = array();
		$tempRow = array();
		
		foreach($res as $row){
			$tempRow['id'] = $row['id'];
			$tempRow['name'] = $row['name'];
			$tempRow['email'] = $row['email'];
			$tempRow['mobile'] = $row['mobile'];
			$tempRow['balance'] = $row['balance'];
			$tempRow['referral_code'] = $row['referral_code'];
			$tempRow['friends_code'] = !empty($row['friends_code'])?$row['friends_code']:'-';
			$tempRow['city_id'] = $row['city'];
			$tempRow['city'] = $row['city_name'];
			$tempRow['area_id'] = $row['area'];
			$tempRow['area'] = $row['area_name'];
			$tempRow['street'] = $row['street'];
			$tempRow['apikey'] = $row['apikey'];
			$tempRow['status'] = $row['status'];
			$tempRow['created_at'] = $row['created_at'];
			// $tempRow['operate'] = $operate;
			$rows[] = $tempRow;
		}
		$bulkData['rows'] = $rows;
		print_r(json_encode($bulkData));
	}
	// data of 'notification' table goes here
	if(isset($_GET['table']) && $_GET['table'] == 'notifications'){
		
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
		
		if(isset($_GET['search'])){
			$search = $_GET['search'];
			$where = " Where `id` like '%".$search."%' OR `title` like '%".$search."%' OR `message` like '%".$search."%' OR `image` like '%".$search."%' OR `date_sent` like '%".$search."%' ";
		}
		
		$sql = "SELECT COUNT(*) as total FROM `notifications` ".$where;
		$db->sql($sql);
		$res = $db->getResult();
		foreach($res as $row)
			$total = $row['total'];
		
		$sql = "SELECT * FROM `notifications` ".$where." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit;
		$db->sql($sql);
		$res = $db->getResult();
		
		$bulkData = array();
		$bulkData['total'] = $total;
		$rows = array();
		$tempRow = array();
		
		foreach($res as $row){
			

			$operate = " <a class='btn btn-xs btn-danger delete-notification' data-id='".$row['id']."' data-image='".$row['image']."' title='Delete'><i class='fa fa-trash-o'></i>Eliminar</a>";
			
			$tempRow['id'] = $row['id'];
			$tempRow['name'] = $row['title'];
			$tempRow['subtitle'] = $row['message'];
			$tempRow['type'] = $row['type'];
			$tempRow['type_id'] = $row['type_id'];
			$tempRow['image'] = (!empty($row['image']))?"<a data-lightbox='slider' href='".$row['image']."' data-caption='".$row['title']."'><img src='".$row['image']."' title='".$row['title']."' width='50' /></a>" : "No Image";
			$tempRow['operate'] = $operate;
			$rows[] = $tempRow;
		}
		$bulkData['rows'] = $rows;
		print_r(json_encode($bulkData));
	}
	
	if(isset($_GET['table']) && $_GET['table'] == 'slider'){
		
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
		
		if(isset($_GET['search'])){
			$search = $_GET['search'];
			$where = " Where `id` like '%".$search."%' OR `image` like '%".$search."%' OR `date_added` like '%".$search."%' ";
		}
		
		$sql = "SELECT COUNT(*) as total FROM `slider` ".$where;
		$db->sql($sql);
		$res = $db->getResult();
		foreach($res as $row)
			$total = $row['total'];
		
		$sql = "SELECT * FROM `slider` ".$where." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit;
		$db->sql($sql);
		$res = $db->getResult();
		
		$bulkData = array();
		$bulkData['total'] = $total;
		$rows = array();
		$tempRow = array();
		
		foreach($res as $row){
			$operate = " <a class='btn btn-xs btn-danger delete-slider' data-id='".$row['id']."' data-image='".$row['image']."' title='Delete'><i class='fa fa-trash-o'></i>Eliminar</a>";
			
			
			$tempRow['id'] = $row['id'];
			$tempRow['type'] = $row['type'];
			$tempRow['type_id'] = $row['type_id'];
			$tempRow['image'] = (!empty($row['image']))?"<a data-lightbox='slider' href='".$row['image']."'><img src='".$row['image']."' width='40'/></a>" : "sin Imagen";
			$tempRow['operate'] = $operate;
			$rows[] = $tempRow;
		}
		$bulkData['rows'] = $rows;
		print_r(json_encode($bulkData));
	}
		if(isset($_GET['table']) && $_GET['table'] == 'offers'){
		
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
		
		if(isset($_GET['search'])){
			$search = $_GET['search'];
			$where = " Where `id` like '%".$search."%' OR `date_added` like '%".$search."%' ";
		}
		
		$sql = "SELECT COUNT(id) as total FROM `offers` ".$where;
		$db->sql($sql);
		$res = $db->getResult();
		foreach($res as $row)
			$total = $row['total'];
		
		$sql = "SELECT * FROM `offers` ".$where." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit;
		$db->sql($sql);
		$res = $db->getResult();
		
		$bulkData = array();
		$bulkData['total'] = $total;
		$rows = array();
		$tempRow = array();
		foreach($res as $row){
		    $operate = " <a class='btn btn-xs btn-danger delete-offer' data-id='".$row['id']."' data-image='".$row['image']."' title='Eliminar'><i class='fa fa-trash-o'></i>Eliminar</a>";
		    
			$tempRow['id'] = $row['id'];
			$tempRow['image'] = (!empty($row['image']))?"<a data-lightbox='offer' href='".$row['image']."'><img src='".$row['image']."' width='40'/></a>" : "No Image";
			$tempRow['date_created'] = date('d-m-Y h:i:sa',strtotime($row['date_added']));
			$tempRow['operate'] = $operate;
			
			$rows[] = $tempRow;
		}
		$bulkData['rows'] = $rows;
		print_r(json_encode($bulkData));
	}
	if(isset($_GET['table']) && $_GET['table'] == 'sections'){
		
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
		
		if(isset($_GET['search'])){
			$search = $_GET['search'];
			$where = " Where `id` like '%".$search."%' OR `title` like '%".$search."%' OR `date_added` like '%".$search."%' ";
		}
		
		$sql = "SELECT COUNT(*) as total FROM `sections` ".$where;
		$db->sql($sql);
		$res = $db->getResult();
		foreach($res as $row)
			$total = $row['total'];
		
		$sql = "SELECT * FROM `sections` ".$where." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit;
		$db->sql($sql);
		$res = $db->getResult();
		
		$bulkData = array();
		$bulkData['total'] = $total;
		$rows = array();
		$tempRow = array();
		
		foreach($res as $row){
			$operate = "<a class='btn btn-xs btn-primary edit-section' data-id='".$row['id']."' title='Editar'><i class='fa fa-pencil-square-o'></i></a>";

			$operate .= " <a class='btn btn-xs btn-danger delete-section' data-id='".$row['id']."' title='Eliminar'><i class='fa fa-trash-o'></i></a>";
			
			$tempRow['id'] = $row['id'];
			$tempRow['title'] = $row['title'];
			$tempRow['short_description'] = $row['short_description'];
			$tempRow['style'] = $row['style'];
			$tempRow['product_ids'] = $row['product_ids'];
			$tempRow['operate'] = $operate;
			$rows[] = $tempRow;
		}
		$bulkData['rows'] = $rows;
		print_r(json_encode($bulkData));
	}
	
	
	if(isset($_GET['table']) && $_GET['table'] == 'seller_request'){
// 		print_r($_GET);
		$offset = 0; $limit = 10;
		$sort = 'id'; $order = 'DESC';
		$where = ' where status='.$_GET['status'];
		if(isset($_GET['offset']))
			$offset = $_GET['offset'];
		if(isset($_GET['limit']))
			$limit = $_GET['limit'];
		
		if(isset($_GET['sort']))
			$sort = $_GET['sort'];
		if(isset($_GET['order']))
			$order = $_GET['order'];
		
		if(isset($_GET['search'])){
			$search = $_GET['search'];
			$where .= "  and (`id` like '%".$search."%' OR `name` like '%".$search."%')";
		}
		
		$sql = "SELECT COUNT(*) as total FROM `seller` ".$where;
		$db->sql($sql);
		$res = $db->getResult();
		foreach($res as $row)
			$total = $row['total'];
		
		$sql = "SELECT * FROM `seller` ".$where." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit;
		$db->sql($sql);
		$res = $db->getResult();
// 		echo $sql;
// 		print_r($res);
		$bulkData = array();
		$bulkData['total'] = $total;
		$rows = array();
		$tempRow = array();
		
		foreach($res as $row){
			$operate = ' <a href="edit-request.php?id='.$row['id'].'"><i class="fa fa-edit"></i>Editar</a>';
			$tempRow['id'] = $row['id'];
			$tempRow['name'] = $row['name'];
			$tempRow['mobile'] = $row['mobile'];
			$tempRow['email'] = $row['email'];
			$tempRow['company'] = $row['company_name'];
			$tempRow['address'] = $row['company_address'];
			$tempRow['gst_no'] = $row['gst_no'];
			$tempRow['pan_no'] = $row['pan_no'];
			if($row['status']==0){
			    $tempRow['status'] = "<span class='label label-warning'>Pendiente</span>";
			}elseif($row['status']==1){
			    $tempRow['status'] =  "<span class='label label-success'>Exito</span>";
			}else{
			    $tempRow['status'] =  "<span class='label label-danger'>Negado</span>";
			}
			$tempRow['date_created'] = $row['date_created'];
			$tempRow['operate'] = $operate;
			$rows[] = $tempRow;
		}
		$bulkData['rows'] = $rows;
		print_r(json_encode($bulkData));
	}
		// data of 'Delivery Boy' table goes here
	if(isset($_GET['table']) && $_GET['table'] == 'delivery-boys'){
		
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
			$where = " Where `id` like '%".$search."%' OR `name` like '%".$search."%' OR `mobile` like '%".$search."%' OR `address` like '%".$search."%'";
		}
		
		$sql = "SELECT COUNT(*) as total FROM `delivery_boys` ".$where;
		$db->sql($sql);
		$res = $db->getResult();
		foreach($res as $row)
			$total = $row['total'];
		
		$sql = "SELECT * FROM `delivery_boys` ".$where." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit;
		//echo $sql;
		$db->sql($sql);
		$res = $db->getResult();
		
		$bulkData = array();
		$bulkData['total'] = $total;
		$rows = array();
		$tempRow = array();
		
		foreach($res as $row){
			
	
			$operate = "<a class='btn btn-xs btn-primary edit-delivery-boy' data-id='".$row['id']."' data-toggle='modal' data-target='#editDeliveryBoyModal' title='Editar'><i class='fa fa-pencil-square-o'></i></a>";

			$operate .= " <a class='btn btn-xs btn-danger delete-delivery-boy' data-id='".$row['id']."' title='Eliminar'><i class='fa fa-trash-o'></i></a>";

			$operate .= " <a class='btn btn-xs btn-primary transfer-fund' data-id='".$row['id']."' data-name='".$row['name']."' data-mobile='".$row['mobile']."' data-address='".$row['address']."' data-balance='".$row['balance']."' data-toggle='modal' data-target='#fundTransferModal' title='Fund Transfer'><i class='fa fa-chevron-circle-right'></i></a>";
			// $operate .= "<a class='btn btn-xs btn-danger delete-district' data-id='".$row['id']."' title='Delete'><i class='fas fa-trash'></i></a>";
			
			$tempRow['id'] = $row['id'];
			$tempRow['name'] = $row['name'];
			$tempRow['mobile'] = $row['mobile'];
			$tempRow['address'] = $row['address'];
			$tempRow['bonus'] = $row['bonus'];
			$tempRow['balance'] = $row['balance'];
			if($row['status']==0)
			    $tempRow['status']="<label class='label label-danger'>Desactivo</label>";
            else
                $tempRow['status']="<label class='label label-success'>Activo</label>";
			$tempRow['operate'] = $operate;
			$rows[] = $tempRow;
		}
		$bulkData['rows'] = $rows;
		print_r(json_encode($bulkData));
	}
		// data of 'Payment Request' table goes here
	if(isset($_GET['table']) && $_GET['table'] == 'payment-requests'){
		
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
			$where = " Where p.`id` like '%".$search."%' OR `user_id` like '%".$search."%' OR `payment_type` like '%".$search."%' OR `amount_requested` like '%".$search."%' OR `remarks` like '%".$search."%' OR `name` like '%".$search."%' OR `email` like '%".$search."%' OR `date_created` like '%".$search."%' OR `payment_address` like '%".$search."%'";
		}
		
		$sql = "SELECT COUNT(*) as total FROM `payment_requests` p JOIN users u ON p.user_id=u.id".$where;
		$db->sql($sql);
		$res = $db->getResult();
		foreach($res as $row)
			$total = $row['total'];
		
		$sql = "SELECT p.*,u.name,u.email FROM payment_requests p JOIN users u ON u.id=p.user_id".$where." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit;
		//echo $sql;
		$db->sql($sql);
		$res = $db->getResult();
		
		$bulkData = array();
		$bulkData['total'] = $total;
		$rows = array();
		$tempRow = array();
		
		foreach($res as $row){

		
			$operate = "<a class='btn btn-xs btn-primary edit-payment-request' data-id='".$row['id']."' data-toggle='modal' data-target='#editPaymentRequestModal' title='Ediart'><i class='fa fa-pencil-square-o'></i></a>";
			// $operate .= "<a class='btn btn-xs btn-danger delete-district' data-id='".$row['id']."' title='Delete'><i class='fas fa-trash'></i></a>";
			
			$tempRow['id'] = $row['id'];
			$tempRow['user_id'] = $row['user_id'];
			$tempRow['payment_type'] = $row['payment_type'];
			if($row['payment_type']=='bank'){
				$payment_address = json_decode($row['payment_address'],true);
				$tempRow['payment_address'] = '<b>Titular de A / C</b><br>'.$payment_address[0][1].'<br>'.'<b>A/C Numero</b><br>'.$payment_address[1][1].'<br>'.'<b>IFSC Código</b><br>'.$payment_address[2][1].'<br>'.'<b>Nombre de banco</b><br>'.$payment_address[3][1];
			}else{
				$tempRow['payment_address'] = $row['payment_address'];
			}
			$tempRow['amount_requested'] = $row['amount_requested'];
			$tempRow['remarks'] = $row['remarks'];
			$tempRow['name'] = $row['name'];
			$tempRow['email'] = $row['email'];
			if($row['status']==0)
			    $tempRow['status']="<label class='label label-warning'>Pendiente</label>";
			if($row['status']==1)
				$tempRow['status']="<label class='label label-primary'>Éxito</label>";
            if($row['status']==2)
                $tempRow['status']="<label class='label label-danger'>cancelado</label>";
			$tempRow['operate'] = $operate;
			$tempRow['date_created'] = $row['date_created'];
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
			$where = " Where `id` like '%".$search."%' OR `name` like '%".$search."%' OR `mobile` like '%".$search."%' OR `address` like '%".$search."%'";
		}
		
		$sql = "SELECT COUNT(*) as total FROM `fund_transfers` f JOIN `delivery_boys` d ON f.delivery_boy_id=d.id".$where;
		$db->sql($sql);
		$res = $db->getResult();
		foreach($res as $row)
			$total = $row['total'];
		
		$sql = "SELECT f.*,d.name,d.mobile,d.address FROM `fund_transfers` f JOIN `delivery_boys` d ON f.delivery_boy_id=d.id ".$where." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit;
		//echo $sql;
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
	

			$rows[] = $tempRow;
		}
		$bulkData['rows'] = $rows;
		print_r(json_encode($bulkData));
	}
// data of 'Promo Codes' table goes here
	if(isset($_GET['table']) && $_GET['table'] == 'promo-codes'){
		
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
			$where = " Where `id` like '%".$search."%' OR `promo_code` like '%".$search."%' OR `message` like '%".$search."%' OR `start_date` like '%".$search."%' OR `end_date` like '%".$search."%'";
		}
		
		$sql = "SELECT COUNT(id) as total FROM `promo_codes`".$where;
		$db->sql($sql);
		$res = $db->getResult();
		foreach($res as $row)
			$total = $row['total'];
		
		$sql = "SELECT * FROM `promo_codes`".$where." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit;
		//echo $sql;
		$db->sql($sql);
		$res = $db->getResult();
		
		$bulkData = array();
		$bulkData['total'] = $total;
		$rows = array();
		$tempRow = array();
		
		foreach($res as $row){
		    
		    $operate = "<a class='btn btn-xs btn-primary edit-promo-code' data-id='".$row['id']."' data-toggle='modal' data-target='#editPromoCodeModal' title='Editar'><i class='fa fa-pencil-square-o'></i></a>";
			$operate .= " <a class='btn btn-xs btn-danger delete-promo-code' data-id='".$row['id']."' title='Eliminar'><i class='fa fa-trash-o'></i></a>";
			
			
			$tempRow['id'] = $row['id'];
			$tempRow['promo_code'] = $row['promo_code'];
			$tempRow['message'] = $row['message'];
			$tempRow['start_date'] = $row['start_date'];
			$tempRow['end_date'] = $row['end_date'];
			$tempRow['no_of_users'] = $row['no_of_users'];
			$tempRow['minimum_order_amount'] = $row['minimum_order_amount'];
			$tempRow['discount'] = $row['discount'];
			$tempRow['discount_type'] = $row['discount_type'];
			$tempRow['max_discount_amount'] = $row['max_discount_amount'];
			$tempRow['repeat_usage'] = $row['repeat_usage']==1?'Allowed':'No permitido';
			$tempRow['no_of_repeat_usage'] = $row['no_of_repeat_usage'];
		    if($row['status']==0)
			    $tempRow['status']="<label class='label label-danger'>Desactivo</label>";
            else
                $tempRow['status']="<label class='label label-success'>Activo</label>";
			$tempRow['date_created'] = date('d-m-Y h:i:sa',strtotime($row['date_created']));
			$tempRow['operate'] = $operate;
	
			$rows[] = $tempRow;
		}
		$bulkData['rows'] = $rows;
		print_r(json_encode($bulkData));
	}
	if(isset($_GET['table']) && $_GET['table'] == 'time-slots'){
		
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
			$where = " Where `id` like '%".$search."%' OR `title` like '%".$search."%' OR `from_time` like '%".$search."%' OR `to_time` like '%".$search."%' OR `last_order_time` like '%".$search."%'";
		}
		
		$sql = "SELECT COUNT(*) as total FROM `time_slots` ".$where;
		$db->sql($sql);
		$res = $db->getResult();
		foreach($res as $row)
			$total = $row['total'];
		
		$sql = "SELECT * FROM `time_slots` ".$where." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit;
		//echo $sql;
		$db->sql($sql);
		$res = $db->getResult();
		
		$bulkData = array();
		$bulkData['total'] = $total;
		$rows = array();
		$tempRow = array();
		
		foreach($res as $row){
			
			
			$operate = "<a class='btn btn-xs btn-primary edit-time-slot' data-id='".$row['id']."' data-toggle='modal' data-target='#editTimeSlotModal' title='Editar'><i class='fa fa-pencil-square-o'></i></a>";

			$operate .= " <a class='btn btn-xs btn-danger delete-time-slot' data-id='".$row['id']."' title='Eliminar'><i class='fa fa-trash-o'></i></a>";
			// $operate .= "<a class='btn btn-xs btn-danger delete-district' data-id='".$row['id']."' title='Delete'><i class='fas fa-trash'></i></a>";
			
			$tempRow['id'] = $row['id'];
			$tempRow['title'] = $row['title'];
			$tempRow['from_time'] = $row['from_time'];
			$tempRow['to_time'] = $row['to_time'];
			$tempRow['last_order_time'] = $row['last_order_time'];
			if($row['status']==0)
			    $tempRow['status']="<label class='label label-danger'>Desactivo</label>";
            else
                $tempRow['status']="<label class='label label-success'>Activo</label>";
			$tempRow['operate'] = $operate;
			$rows[] = $tempRow;
		}
		$bulkData['rows'] = $rows;
		print_r(json_encode($bulkData));
	}
		// data of 'Return Request' table goes here
	if(isset($_GET['table']) && $_GET['table'] == 'return-requests'){
		
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
			$where = " Where r.`id` like '%".$search."%' OR r.`user_id` like '%".$search."%' OR r.`order_id` like '%".$search."%' OR p.`name` like '%".$search."%' OR u.`name` like '%".$search."%' OR r.`status` like '%".$search."%' OR r.`date_created` like '%".$search."%'";
		}
		
		$sql = "SELECT COUNT(*) as total FROM `return_requests` r LEFT JOIN users u ON r.user_id=u.id".$where;
		$db->sql($sql);
		$res = $db->getResult();
		foreach($res as $row)
			$total = $row['total'];
		
		$sql = "SELECT r.*,u.name,oi.product_variant_id,oi.quantity,p.id as product_id,p.name as product_name,pv.price,pv.discounted_price FROM return_requests r LEFT JOIN users u ON u.id=r.user_id LEFT JOIN order_items oi ON oi.id=r.order_item_id LEFT JOIN products p ON p.id = r.product_id LEFT JOIN product_variant pv ON pv.id=r.product_variant_id".$where." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit;
// 		echo $sql;
		$db->sql($sql);
		$res = $db->getResult();
		
		$bulkData = array();
		$bulkData['total'] = $total;
		$rows = array();
		$tempRow = array();
		
		foreach($res as $row){
		    

			
			$operate = "<a class='btn btn-xs btn-primary edit-return-request' data-id='".$row['id']."' data-toggle='modal' data-target='#editReturnRequestModal' title='Editar'><i class='fa fa-pencil-square-o'></i></a>";
			$operate .= " <a class='btn btn-xs btn-danger delete-return-request' data-id='".$row['id']."' title='Eliminar'><i class='fa fa-trash'></i></a>";
			
			$tempRow['id'] = $row['id'];
			$tempRow['user_id'] = $row['user_id'];
			$tempRow['order_id'] = $row['order_id'];
			$tempRow['order_item_id'] = $row['order_item_id'];
			$tempRow['product_id'] = $row['product_id'];
			$tempRow['price'] = $row['price'];
			$tempRow['discounted_price'] = $row['discounted_price'];
			
			$tempRow['name'] = $row['name'];
			$tempRow['product_name'] = $row['product_name'];
			$tempRow['product_variant_id'] = $row['product_variant_id'];
			$tempRow['quantity'] = $row['quantity'];
			$tempRow['total'] = $row['discounted_price']==0?$row['price']*$row['quantity']:$row['discounted_price']*$row['quantity'];

			if($row['status']==0)
			    $tempRow['status']="<label class='label label-warning'>Pendiente</label>";
			if($row['status']==1)
				$tempRow['status']="<label class='label label-primary'>Aprobado</label>";
            if($row['status']==2)
                $tempRow['status']="<label class='label label-danger'>cancelado</label>";
			$tempRow['operate'] = $operate;
			// $tempRow['date_created'] = date('d-M-Y h:i A',strtotime($row['date_created']));
			$tempRow['date_created'] = $row['date_created'];
			$rows[] = $tempRow;
		}
		$bulkData['rows'] = $rows;
		print_r(json_encode($bulkData));
	}
	// data of 'Promo Codes' table goes here
	if(isset($_GET['table']) && $_GET['table'] == 'system-users'){
		
		$offset = 0; $limit = 10;
		$sort = 'id'; $order = 'ASC';
		$where = '';
		$condition = '';
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
			$where = " Where `id` like '%".$search."%' OR `username` like '%".$search."%' OR `email` like '%".$search."%' OR `role` like '%".$search."%' OR `date_created` like '%".$search."%'";
		}
		if($_SESSION['role']!='super admin'){
			if(empty($where)){
				$condition .= ' where created_by='.$_SESSION['id'];

			}else{
				$condition .= ' and created_by='.$_SESSION['id'];
			}
		}
		
		$sql = "SELECT COUNT(id) as total FROM `admin`".$where."".$condition;
		$db->sql($sql);
		$res = $db->getResult();
		foreach($res as $row)
			$total = $row['total'];
		
		$sql = "SELECT * FROM `admin`".$where."".$condition." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit;
		$db->sql($sql);
		$res = $db->getResult();
		
		$bulkData = array();
		$bulkData['total'] = $total;
		$rows = array();
		$tempRow = array();
		
		foreach($res as $row){
			if($row['created_by']!=0){
				$sql = "SELECT username FROM admin WHERE id=".$row['created_by'];
				$db->sql($sql);
				$created_by=$db->getResult();
			}
			
			// print_r($city_names);
			
			
			if($row['role'] != 'super admin'){
				$operate = "<a class='btn btn-xs btn-primary edit-system-user' data-id='".$row['id']."' data-toggle='modal' data-target='#editSystemUserModal' title='Editar'><i class='fa fa-pencil-square-o'></i></a>";
				$operate .= " <a class='btn btn-xs btn-danger delete-system-user' data-id='".$row['id']."' title='Delete'><i class='fa fa-trash-o'></i></a>";
			}else{
				$operate='';
			}
			if($row['role']=='super admin'){
				$role = '<span class="label label-success"> administrador Tambo</span>';
			}
			if($row['role']=='admin'){
				$role = '<span class="label label-primary">Administrador</span>';
			}
			if($row['role']=='editor'){
				$role = '<span class="label label-warning">Vendedor</span>';
			}
			$tempRow['id'] = $row['id'];
			$tempRow['username'] = $row['username'];
			$tempRow['email'] = $row['email'];
			$tempRow['permissions'] = $row['permissions'];
			$tempRow['role'] = $role;
			$tempRow['created_by_id'] = $row['created_by']!=0?$row['created_by']:'-';
			$tempRow['created_by'] = $row['created_by']!=0?$created_by[0]['username']:'-';
			$tempRow['date_created'] = date('d-m-Y h:i:sa',strtotime($row['date_created']));
			$tempRow['operate'] = $operate;
	
			$rows[] = $tempRow;
		}
		$bulkData['rows'] = $rows;
		print_r(json_encode($bulkData));
	}
	
	// data of 'Wallet Transactions' table goes here
	if(isset($_GET['table']) && $_GET['table'] == 'wallet-transactions'){
		
		$offset = 0; $limit = 10;
		$sort = 'w.id'; $order = 'DESC';
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
			$where = " Where w.`id` like '%".$search."%' OR `user_id` like '%".$search."%' OR `message` like '%".$search."%' OR `name` like '%".$search."%' OR `date_created` like '%".$search."%'";
		}
		
		$sql = "SELECT COUNT(*) as total FROM `wallet_transactions` w JOIN `users` u ON u.id=w.user_id ".$where;
		$db->sql($sql);
		$res = $db->getResult();
		foreach($res as $row)
			$total = $row['total'];
		
		$sql = "SELECT w.*,u.name FROM `wallet_transactions` w JOIN `users` u ON u.id=w.user_id ".$where." ORDER BY ".$sort." ".$order." LIMIT ".$offset.", ".$limit;
		// echo $sql;
		$db->sql($sql);
		$res = $db->getResult();
		
		$bulkData = array();
		$bulkData['total'] = $total;
		$rows = array();
		$tempRow = array();
		
		foreach($res as $row){
			
	
			
			$tempRow['id'] = $row['id'];
			$tempRow['user_id'] = $row['user_id'];
			$tempRow['name'] = $row['name'];
			$tempRow['type'] = $row['type'];
			$tempRow['amount'] = $row['amount'];
			$tempRow['message'] = $row['message'];
			$tempRow['date_created'] = $row['date_created'];
			$tempRow['las_updated'] = $row['last_updated'];
			if($row['status']==0)
			    $tempRow['status']="<label class='label label-danger'>Desactivo</label>";
            else
                $tempRow['status']="<label class='label label-success'>Activo</label>";
			$rows[] = $tempRow;
		}
		$bulkData['rows'] = $rows;
		print_r(json_encode($bulkData));
	}
	$db->disconnect();
?>