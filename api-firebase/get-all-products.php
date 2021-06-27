<?php
    header('Access-Control-Allow-Origin: *');
	include_once('../includes/variables.php');
	include_once('../includes/crud.php');
	include_once('verify-token.php');
    $db = new Database();
    $db->connect();
    date_default_timezone_set('Asia/Kolkata');
    
  	/* accesskey:90336
  	 product_id:230 */
  	if(!verify_token()){
		return false;
	}
	if(isset($_POST['accesskey']) && isset($_POST['get_all_products'])) {
		$access_key_received = isset($_POST['accesskey']) && !empty($_POST['accesskey'])?$_POST['accesskey']:'';
		
		if($access_key_received == $access_key){
		    $limit = (isset($_POST['limit']) && !empty($_POST['limit']) && is_numeric($_POST['limit']))?$_POST['limit']:10;
		    $offset = (isset($_POST['offset']) && !empty($_POST['offset']) && is_numeric($_POST['offset']))?$_POST['offset']:0;
		    
		    $sort = (isset($_POST['sort']) && !empty($_POST['sort']))?$_POST['sort']:"row_order + 0 ";
		    $order = (isset($_POST['order']) && !empty($_POST['order']))?$_POST['order']:"ASC";
		    
		    $sql = "SELECT count(id) as total FROM products ";
            $db->sql($sql);
            $total = $db->getResult();
            
		    
            $sql = "SELECT * FROM products ORDER BY $sort $order LIMIT $offset,$limit ";
            $db->sql($sql);
            $res = $db->getResult();
            // return $res;
            $product = array();
            $i = 0;
            foreach($res as $row){
                
                $sql = "SELECT *,(SELECT short_code FROM unit u WHERE u.id=pv.measurement_unit_id) as measurement_unit_name,(SELECT short_code FROM unit u WHERE u.id=pv.stock_unit_id) as stock_unit_name FROM product_variant pv WHERE pv.product_id=".$row['id']." ";
                $db->sql($sql);
                $row['other_images'] = json_decode($row['other_images'],1);
                $row['other_images'] = (empty($row['other_images']))?array():$row['other_images'];
                for($j=0;$j<count($row['other_images']);$j++){
                    $row['other_images'][$j] = DOMAIN_URL.$row['other_images'][$j];
                }
                
                $row['image'] = DOMAIN_URL.$row['image'];
                $product[$i] = $row;
                
                /* temporarily added to ignore stock */
                // for($k=0;$k<count($variants);$k++){
                //     $variants[$k]['serve_for'] = "Available";
                //     $variants[$k]['stock'] = 100;
                // }
                $variants = $db->getResult();
                for($k=0;$k<count($variants);$k++){
        		    if($variants[$k]['stock']<=0){
        		        $variants[$k]['serve_for']='agotado';
        		    }else{
        		        $variants[$k]['serve_for']='disponible';
        		    }
        		        
        		}
                
                $product[$i]['variants'] = $variants;
                $i++;
            }
		    
			// create json output
			if(!empty($product)){
			    $output = json_encode(
			        array('error' => false,
			            'total' => $total[0]['total'],
			            'limit' => $limit,
			            'offset' => $offset,
			            'sort' => $sort,
			            'order' => $order,
			            'message' => "Productos recuperados con éxito",
			            'data' => $product
			     )
			    );
			}else{
			    $output = json_encode(array('error' => true,
    			    'total' => $total[0]['total'],
    			    'limit' => $limit,
    	            'offset' => $offset,
    	            'sort' => $sort,
    	            'order' => $order,
    			    'message' => 'No hay productos disponibles.',
    			    'data' => array()
			    )
			  );
			}
		}else{
			die('la clave de acceso es incorrecta.');
		}
	} else {
		die('Se requiere clave de acceso.');
	}
 
	//Output the output.
	echo $output;

	$db->disconnect(); 
	//to check if the string is json or not
	function isJSON($string){
		return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
	}
?>