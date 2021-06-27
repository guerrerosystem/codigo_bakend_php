<?php 
header('Access-Control-Allow-Origin: *');
include_once('../includes/crud.php');
include_once('../includes/variables.php');
include_once('verify-token.php');
$db = new Database();
$db->connect();
date_default_timezone_set('Asia/Kolkata');
/* accesskey:90336
	type:products-search
	search:Himalaya Baby Powder
	id:227*/
$accesskey = $_POST['accesskey'];

if($access_key != $accesskey){
	$response['error']= true;
	$response['message']="clave de acceso inválida";
	print_r(json_encode($response));
	return false;
}
if(!verify_token()){
        return false;
}
// data of 'PRODUCTS' table goes here
if(isset($_POST['type']) && $_POST['type'] == 'products-search'){
	$offset = 0; $limit = 10;
	$sort = 'id'; $order = 'DESC';
	$where = '';
	if(isset($_POST['offset']))
		$offset = $_POST['offset'];
	if(isset($_POST['limit']))
		$limit = $_POST['limit'];
		
	if(isset($_POST['sort']))
		$sort = $_POST['sort'];
	if(isset($_POST['order']))
		$order = $_POST['order'];
		
	if(isset($_POST['search']) && $_POST['search']!=''){
		$search = $_POST['search'];
				// $where = " Where `id` like '%".$search."%' OR `name` like '%".$search."%' OR `image` like '%".$search."%'";
		$where = "Where `id` like '%".$search."%' OR `name` like '%".$search."%' OR `image` like '%".$search."%' OR `subcategory_id` like '%".$search."%' OR `slug` like '%".$search."%' OR `description` like '%".$search."%'";

		// $where = " Where `id` like '%".$search."%' OR `name` like '%".$search."%' OR `measurement` like '%".$search."%' OR `price` like '%".$search."%' OR `serve_for` like '%".$search."%' OR `image` like '%".$search."%' OR `description` like '%".$search."%' OR `quantity` like '%".$search."%'";
	}
	// if(!empty($search)){
		$sql = "SELECT COUNT(id) as total FROM `products` ".$where;
		$db->sql($sql);
		$res = $db->getResult();

	foreach($res as $row){
		$total = $row['total'];
	}

	$sql = "SELECT * FROM `products` ".$where;
		
	// $sql = "SELECT *,(SELECT name FROM category where id=category_id)as category FROM `products` ".$where." ORDER BY `".$sort."` ".$order." LIMIT ".$offset.", ".$limit;
	$db->sql($sql);
	$res = $db->getResult();
		
	// $bulkData = array();
	// $bulkData['total'] = $total;
	// $rows = array();
	// $tempRow = array();
	// $menus = $variations = array();
	$product = array();
	$i=0;
	
	foreach($res as $row){
		$sql = "SELECT *,(SELECT short_code FROM unit u WHERE u.id=pv.measurement_unit_id) as measurement_unit_name,(SELECT short_code FROM unit u WHERE u.id=pv.stock_unit_id) as stock_unit_name FROM product_variant pv WHERE pv.product_id=".$row['id']."";
        $db->sql($sql);
        $variants = $db->getResult();
        
        $row['other_images'] = json_decode($row['other_images'],1);
		$row['other_images'] = (empty($row['other_images']))?array():$row['other_images'];
		for($j=0;$j<count($row['other_images']);$j++){
		    $row['other_images'][$j] = DOMAIN_URL.$row['other_images'][$j];
		}
	    
	    $row['image'] = DOMAIN_URL.$row['image'];
	    $product[$i] = $row;
	    for($k=0;$k<count($variants);$k++){
		    if($variants[$k]['stock']<=0){
		        $variants[$k]['serve_for']='agotado';
		    }
		    if($variants[$k]['stock']>0){
		        $variants[$k]['serve_for']='disponible';
		        
		    }
        		        
        }
	    $product[$i]['variants'] = $variants;
        $i++;
    
	}
	if(empty($product)){
    	$bulkData['error'] = true;
    	$bulkData['message'] = 'No Productos';
    	print_r(json_encode($bulkData));
	}else{
    	$bulkData['error'] = false;
    	$bulkData['data'] = array_values($product);
    	print_r(json_encode($bulkData));
	}
	// $bulkData['rows'] = $rows;

}
function isJSON($string){
	return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
}
?>