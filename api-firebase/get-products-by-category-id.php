<?php
    header('Access-Control-Allow-Origin: *');
	include_once('../includes/crud.php');
	include_once('../includes/variables.php');
	include_once('verify-token.php');
	// $function = new custom_functions;
    $db = new Database();
    $db->connect();
    date_default_timezone_set('Asia/Kolkata');

	/* accesskey:90336
  	 category_id:28 */
    if(!verify_token()){
    	return false;
    }
	if(isset($_POST['accesskey']) && isset($_POST['category_id'])) {
		$access_key_received = $_POST['accesskey'];
		$category_id = $db->escapeString($_POST['category_id']);
		$sort = (isset($_POST['sort']) && !empty($_POST['sort']))?$_POST['sort']:'id';
	    $limit = (isset($_POST['limit']) && !empty($_POST['limit']) && is_numeric($_POST['limit']))?$_POST['limit']:'10';
		$offset = (isset($_POST['offset']) && !empty($_POST['offset']) && is_numeric($_POST['offset']))?$_POST['offset']:'0';
	
		if($access_key_received == $access_key){
		    
            if($sort=='new'){
                $sort = 'ORDER BY date_added DESC';
                $price = 'MIN(price)';
                $price_sort = 'pv.price ASC';
            }elseif($sort=='old'){
                $sort = 'ORDER BY date_added ASC';
                $price = 'MIN(price)';
                $price_sort = 'pv.price ASC';
            }elseif($sort=='high'){
                $sort = 'ORDER BY price DESC';
                $price = 'MAX(price)';
                $price_sort = 'pv.price DESC';
            }elseif($sort=='low'){
                $sort = 'ORDER BY price ASC';
                $price = 'MIN(price)';
                $price_sort = 'pv.price ASC';
            }else{
                $sort = 'ORDER BY name ASC';
                $price = 'MIN(price)';
                $price_sort = 'pv.price ASC';
            }
		    
		    if(!empty($category_id)){ 
		         $sql = "SELECT count(id) as total from products p where category_id='".$category_id."'";
		         $db->sql($sql);
		         $total = $db->getResult();
		         
		         $sql="SELECT *,(SELECT ".$price." FROM product_variant pv WHERE pv.product_id=p.id) as price FROM products p WHERE category_id='".$category_id."' ".$sort." LIMIT $offset, $limit";
		        
		    }else{
                $sql="SELECT *,(SELECT ".$price." FROM product_variant pv WHERE pv.product_id=p.id) as price FROM products p ".$sort." LIMIT $offset, $limit";
                $sql = "SELECT count(id) as total from products ";
                $db->sql($sql);
                $total = $db->getResult();
            }
                
            $db->sql($sql);
            $res = $db->getResult();
            // return $res;
            $product = array();
            $i = 0;
            foreach($res as $row){
                
                $sql = "SELECT *,(SELECT short_code FROM unit u WHERE u.id=pv.measurement_unit_id) as measurement_unit_name,(SELECT short_code FROM unit u WHERE u.id=pv.stock_unit_id) as stock_unit_name FROM product_variant pv WHERE pv.product_id=".$row['id']." ORDER BY serve_for ASC,".$price_sort."";
                
                //echo $sql;
                $db->sql($sql);
                
                $row['other_images'] = json_decode($row['other_images'],1);
                $row['other_images'] = (empty($row['other_images']))?array():$row['other_images'];
                
                for($j=0;$j<count($row['other_images']);$j++){
                    $row['other_images'][$j] = DOMAIN_URL.$row['other_images'][$j];
                }
                
                $row['image'] = DOMAIN_URL.$row['image'];
                $product[$i] = $row;
                
                $variants = $db->getResult();
                for($k=0;$k<count($variants);$k++){
        		    if($variants[$k]['stock']<=0){
        		        $variants[$k]['serve_for']='agotado';
        		    }else{
        		        $variants[$k]['serve_for']='disponible';
        		    }
        		        
        		}
                /* temporarily added to ignore stock */
                // for($k=0;$k<count($variants);$k++){
                //     $variants[$k]['serve_for'] = "Available";
                //     $variants[$k]['stock'] = 100;
                // }
                
                $product[$i]['variants'] = $variants;
                $i++;
            }
		    
			// create json output
			if(!empty($product)){
			    $output = json_encode(array('error' => false,
			    'total' => $total[0]['total'],
			    'data' => $product));
			}else{
			    $output = json_encode(array('error' => true,
			    'total' => $total[0]['total'],
			    'data' => 'No hay productos disponibles.'));
			}
		}else{
			$output = json_encode(array('error' => true,
				'message' => 'accesskey is incorrect.'));
		}
	} else {
		$output = json_encode(array('error' => true,
			'message' => 'Se requiere clave de acceso e ID de categoría.'));
	}
 
	//Output the output.
	echo $output;
	
	$db->disconnect();
	
	//to check if the string is json or not
	function isJSON($string){
		return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
	}
?>