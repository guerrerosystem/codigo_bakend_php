<?php
    include_once('includes/functions.php');
    
    $function = new Functions;
    
    if(isset($_GET['id'])){
        $ID = $_GET['id'];
    }else{
        
        return false;
        exit(0);
    }
   
    $category_data = array();
    $sql = "select id,name from category order by id asc";
    $db->sql($sql);
    $category_data = $db->getResult();
    $sql = "select * from subcategory";
    $db->sql($sql);
    $subcategory = $db->getResult();
    $sql = "SELECT image, other_images FROM products WHERE id =".$ID;
    $db->sql($sql);
    $res = $db->getResult();
    foreach($res as $row){
        $previous_menu_image = $row['image'];
        $other_images = $row['other_images'];
    }
    if(isset($_POST['btnEdit'])){
        if($permissions['products']['update']==1){
              $name = $_POST['name'];
              if (strpos($name, '-') !== false) {
                    $temp=(explode("-", $name)[1]);
            }else{
                $temp=$name;
            }
              
              $slug = $function->slugify($temp);
              $sql = "SELECT slug FROM products where id!=".$_GET['id'];
              $db->sql($sql);
              $res = $db->getResult();
              $i=1;
              foreach($res as $row){
                  if($slug==$row['slug']){
                    $slug = $slug.'-'.$i;
                    $i++;  
                  }
              }
        
            $subcategory_id = $_POST['subcategory_id'];
            $category_id = $_POST['category_id'];
            $serve_for = $_POST['serve_for'];
            $description = $_POST['description'];
          
            
           
            $image = $_FILES['image']['name'];
            $image_error = $_FILES['image']['error'];
            $image_type = $_FILES['image']['type'];
                
          
            $error = array();
            
            if(empty($name)){
                $error['name'] = " <span class='label label-danger'>obligatorio!</span>";
            }
                
            if(empty($category_id)){
                $error['category_id'] = " <span class='label label-danger'>obligatorio!</span>";
            }
            if(empty($measurement)){
             $error['measurement'] = " <span class='label label-danger'>obligatorio!</span>";
            }
                
            if(empty($price)){
             $error['price'] = " <span class='label label-danger'>obligatorio!</span>";
            }/* else if(!is_numeric($price)){
             $error['price'] = " <span class='label label-danger'>Price in number!</span>";
            } */
                
            if(empty($discounted_price)){
             $error['discounted_price'] = " <span class='label label-danger'>¡Necesario! Al menos cero(0)</span>";
            }
            
            if(empty($stock)){
             $error['stock'] = " <span class='label label-danger'>obligatorio!</span>";
            }else if(!is_numeric($stock)){
             $error['stock'] = " <span class='label label-danger'>stock in numero!</span>";
            }
                
            if(empty($serve_for)){
             $error['serve_for'] = " <span class='label label-danger'>No cambio</span>";
            }            
    
            if(empty($description)){
                $error['description'] = " <span class='label label-danger'>obligatorio!</span>";
            }
            
            
            $allowedExts = array("gif", "jpeg", "jpg", "png");
            
           
            error_reporting(E_ERROR | E_PARSE);
            $extension = end(explode(".", $_FILES["image"]["name"]));
            
            if(!empty($image)){
                if(!(($image_type == "image/gif") || 
                    ($image_type == "image/jpeg") || 
                    ($image_type == "image/jpg") || 
                    ($image_type == "image/x-png") ||
                    ($image_type == "image/png") || 
                    ($image_type == "image/pjpeg")) &&
                    !(in_array($extension, $allowedExts))){
                    
                    $error['image'] = "*<span class='label label-danger'>¡El tipo de imagen debe ser jpg, jpeg, gif o png!</span>";
                }
            }
           
            
            if(isset($_FILES['other_images']) && ($_FILES['other_images']['size'][0] > 0 )){
                // print_r($_FILES);
                $file_data = array();
                $target_path = 'upload/other_images/';
                for($i=0;$i<count($_FILES["other_images"]["name"]);$i++){
                    
                    if($_FILES["other_images"]["error"][$i] > 0){
                        $error['other_images'] = " <span class='label label-danger'>Images no subdido</span>";
                    }else if(!(($_FILES["other_images"]["type"][$i] == "image/gif") || 
                        ($_FILES["other_images"]["type"][$i] == "image/jpeg") || 
                        ($_FILES["other_images"]["type"][$i] == "image/jpg") || 
                        ($_FILES["other_images"]["type"][$i] == "image/x-png") ||
                        ($_FILES["other_images"]["type"][$i] == "image/png") || 
                        ($_FILES["other_images"]["type"][$i] == "image/pjpeg")) &&
                        !(in_array($_FILES["other_images"]["type"][$i], $allowedExts))){
                        $error['other_images'] = " <span class='label label-danger'>¡El tipo de imagen debe ser jpg, jpeg, gif o png!</span>";
                    }
                    $filename = $_FILES["other_images"]["name"][$i];
                    $temp = explode('.',$filename);
                    $filename = microtime(true) . '.' . end($temp);
                    $file_data[] = $target_path.''.$filename;
                    if(!move_uploaded_file($_FILES["other_images"]["tmp_name"][$i], $target_path.''.$filename))
                        echo "{$_FILES['image']['name'][$i]} no subido<br/>";
                }
                if(!empty($other_images)){
                    $arr_old_images = json_decode($other_images);
                    $all_images = array_merge($arr_old_images,$file_data);
                    $all_images = json_encode(array_values($all_images));
                }else{
                    $all_images = json_encode($file_data);
                }
                
                $sql = "update `products` set `other_images`='".$all_images."' where `id`=".$ID;
                $db->sql($sql);
                echo $all_images;
            }
                if(!empty($name) && !empty($category_id) &&  !empty($serve_for) && !empty($description) && empty($error['image'])){
                     if (strpos($name, "'") !== false) {
                     $name=str_replace("'", "''", "$name");
                     if(strpos($description, "'") !== false)
                     $description=str_replace("'", "''", "$description");
            }
                if(!empty($image)){
                    
                  
                    $string = '0123456789';
                    $file = preg_replace("/\s+/", "_", $_FILES['image']['name']);
                    $function = new functions;
                    $image = $function->get_random_string($string, 4)."-".date("Y-m-d").".".$extension;
                
              
                    $delete = unlink("$previous_menu_image");
                    
                    
                    $upload = move_uploaded_file($_FILES['image']['tmp_name'], 'upload/images/'.$image);
      
                    $upload_image = 'upload/images/'.$image;
                    $sql_query = "UPDATE products SET name = '$name' ,slug = '$slug' , subcategory_id = '$subcategory_id', image = '$upload_image', description = '$description' WHERE id = $ID";
                    $db->sql($sql_query);
                }else{
                    $sql_query = "UPDATE products SET name = '$name' ,slug = '$slug' ,category_id = '$category_id' ,subcategory_id = '$subcategory_id' ,description = '$description' WHERE id = $ID";
                    $db->sql($sql_query);
                }
                $res = $db->getResult();
                for($i=0;$i<count($_POST['product_variant_id']);$i++){
                    if($_POST['type']=="packet"){
                        $data = array(
                            'type'=>$db->escapeString($_POST['type']),
                            'measurement'=>$db->escapeString($_POST['packate_measurement'][$i]),
                            'measurement_unit_id'=>$db->escapeString($_POST['packate_measurement_unit_id'][$i]),
                            'price'=>$db->escapeString($_POST['packate_price'][$i]),
                            'discounted_price'=>$db->escapeString($_POST['packate_discounted_price'][$i]),
                            'stock'=>$db->escapeString($_POST['packate_stock'][$i]),
                            'stock_unit_id'=>$db->escapeString($_POST['packate_stock_unit_id'][$i]),
                            'serve_for'=>$db->escapeString($_POST['packate_serve_for'][$i]),

                        );
                        $db->update('product_variant',$data,'id='.$_POST['product_variant_id'][$i]);
                        $res = $db->getResult();
                        // print_r($res);
                    }else if($_POST['type']=="loose"){
                        $data = array(
                            'type'=>$db->escapeString($_POST['type']),
                            'measurement'=>$db->escapeString($_POST['loose_measurement'][$i]),
                            'measurement_unit_id'=>$db->escapeString($_POST['loose_measurement_unit_id'][$i]),
                            'price'=>$db->escapeString($_POST['loose_price'][$i]),
                            'discounted_price'=>$db->escapeString($_POST['loose_discounted_price'][$i]),
                            'stock'=>$db->escapeString($_POST['loose_stock']),
                            'stock_unit_id'=>$db->escapeString($_POST['loose_stock_unit_id']),
                            'serve_for'=>$db->escapeString($_POST['serve_for']),
                        );  
                        $db->update('product_variant',$data,'id='.$_POST['product_variant_id'][$i]);
                        $res = $db->getResult();
                    }
                }
                if(isset($_POST['insert_packate_measurement']) && isset($_POST['insert_packate_measurement_unit_id']) 
                && isset($_POST['insert_packate_price']) && isset($_POST['insert_packate_discounted_price'])
                && isset($_POST['insert_packate_stock']) && isset($_POST['insert_packate_stock_unit_id'])){
                    for($i=0;$i<count($_POST['insert_packate_measurement']);$i++){
                        $data = array(
                            "product_id"=>$db->escapeString($ID),
                            "type"=>$db->escapeString($_POST['type']),
                            "measurement"=>$db->escapeString($_POST['insert_packate_measurement'][$i]),
                            "measurement_unit_id"=>$db->escapeString($_POST['insert_packate_measurement_unit_id'][$i]),
                            "price"=>$db->escapeString($_POST['insert_packate_price'][$i]),
                            "discounted_price"=>$db->escapeString($_POST['insert_packate_discounted_price'][$i]),
                            "stock"=>$db->escapeString($_POST['insert_packate_stock'][$i]),
                            "stock_unit_id"=>$db->escapeString($_POST['insert_packate_stock_unit_id'][$i]),
                            "serve_for"=>$db->escapeString($_POST['serve_for']),
                        );
                        $db->insert('product_variant',$data);
                        $res = $db->getResult();
                //      print_R($res);
                    }
                }

                if(isset($_POST['insert_loose_measurement']) && isset($_POST['insert_loose_measurement_unit_id']) 
                && isset($_POST['insert_loose_price']) && isset($_POST['insert_loose_discounted_price'])){
                    for($i=0;$i<count($_POST['insert_loose_measurement']);$i++){
                        $data = array(
                            "product_id"=>$db->escapeString($ID),
                            "type"=>$db->escapeString($_POST['type']),
                            "measurement"=>$db->escapeString($_POST['insert_loose_measurement'][$i]),
                            "measurement_unit_id"=>$db->escapeString($_POST['insert_loose_measurement_unit_id'][$i]),
                            "price"=>$db->escapeString($_POST['insert_loose_price'][$i]),
                            "discounted_price"=>$db->escapeString($_POST['insert_loose_discounted_price'][$i]),
                            "stock"=>$db->escapeString($_POST['loose_stock']),
                            "stock_unit_id"=>$db->escapeString($_POST['loose_stock_unit_id']),
                            "serve_for"=>$db->escapeString($_POST['serve_for']),
                        );
                        $db->insert('product_variant',$data);
                        $res = $db->getResult();
                    }
                }
                $error['update_data'] = "<span class='label label-success'>
                Producto actualizado con éxito</span>";
                
            }
            }else{
            $error['check_permission'] = " <section class='content-header'>
                                                <span class='label label-danger'>No tienes permiso para actualizar el producto</span>
                                                
                                                
                                                </section>";
            }
        }
        // create array variable to store previous data
        $data = array();
        $sql_query = "SELECT v.*,p.*,v.id as product_variant_id FROM product_variant v JOIN products p ON p.id=v.product_id WHERE p.id=".$ID;
        $db->sql($sql_query);
        $res = $db->getResult();
        foreach($res as $row)
            $data = $row;
        
        //to check if the string is json or not
        function isJSON($string){
            return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
        }
?>
<section class="content-header">
    <!-- <?php //print_r($res);?> -->
    <h1>Editar Producto <small><a  href='productos.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;ver productos</a></small></h1>
    <small><?php echo isset($error['update_data']) ? $error['update_data'] : '';?></small>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
</section>
<section class="content">
    <!-- Main row -->
    <div class="row">
        <div class="col-md-12">
            <?php if($permissions['products']['update']==0) { ?>
            <div class="alert alert-danger topmargin-sm">No tiene permiso para actualizar el producto.</div>
        <?php } ?>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Editar Producto</h3>
            </div>
            <!-- form start -->
            <form id='edit_product_form' method="post" enctype="multipart/form-data">
                <?php 
                    $db->select('unit','*');
                    $unit_data = $db->getResult();
                 ?>
              
            <div class="box-body">
                <div class="form-group">
                    <label for="exampleInputEmail1">Nombre prodcuto</label><?php echo isset($error['name']) ? $error['name'] : '';?>
                    <input type="text" name="name" class="form-control" value="<?php echo $data['name']; ?>"/>
                </div>
                <label for="type">Tipo</label><?php echo isset($error['type']) ? $error['type'] : '';?>
                    <div class="form-group">
                      <label class="radio-inline"><input type="radio" name="type"  id="packate" value="packet" <?=($res[0]['type']=="packet")?"checked":""; ?> >Packetr</label>
                      <label class="radio-inline"><input type="radio" name="type" id ="loose" value="loose" <?=($res[0]['type']=="loose")?"checked":""; ?> >Suelto</label>
                    </div>
                <hr>
                <div id="variations">
                    <h5>Variaciones de producto</h5>
                    <hr>
                    <?php 
                        if(isJSON($data['price'])){
                            $price = json_decode($data['price'],1);
                            $measurement = json_decode($data['measurement'],1);
                            $discounted_price = json_decode($data['discounted_price'],1);
                        }else{
                            $price = array('0'=>$data['price']);
                            $measurement = array('0'=>$data['measurement']);
                            $discounted_price = array('0'=>$data['discounted_price']);
                        }
                //      for($i=0;$i<count($price);$i++){
                        $i=0;
                        if($res[0]['type']=="packet"){
                        foreach($res as $row){
                        ?>
                            <!-- <div id="packate_div" style="display:block"> -->
                            <div class="row packate_div">
                            <input type="hidden" class="form-control" name="product_variant_id[]" id="product_variant_id" value='<?=$row['product_variant_id'];?>'/>
                                <div class="col-md-2">
                                    <div class="form-group packate_div">
                                        <label for="exampleInputEmail1">Medicion</label><input type="text" class="form-control" name="packate_measurement[]" value='<?=$row['measurement'];?>' required />
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group packate_div">
                                        <label for="unit">unidad:</label>
                                        <select class="form-control" name="packate_measurement_unit_id[]">
                                            <?php
                                            // print_r($unit_data);
                                                foreach($unit_data as  $unit){
                                                    echo "<option";
                                                    if($unit['id'] ==$row['measurement_unit_id']){
                                                        echo " selected ";
                                                    }
                                                    echo " value='".$unit['id']."'>".$unit['short_code']."</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group packate_div">
                                        <label for="price">Precio  (<?=$settings['currency']?>):</label><input type="text" class="form-control" name="packate_price[]" id="packate_price"  value='<?=$row['price'];?>' required />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group packate_div">
                                        <label for="discounted_price">Precio descontado(<?=$settings['currency']?>):</label>
                                        <input type="text" class="form-control" name="packate_discounted_price[]" id="discounted_price" value='<?=$row['discounted_price'];?>'/>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group packate_div">
                                        <label for="qty">Stock:</label>
                                        <input type="text" class="form-control" name="packate_stock[]" value='<?=$row['stock'];?>'/>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group packate_div">
                                        <label for="unit">unidad:</label>
                                        <select class="form-control" name="packate_stock_unit_id[]">
                                            <?php
                                            // echo $row['stock_unit_id'];
                                                foreach($unit_data as  $unit){
                                                    echo "<option";
                                                    if($unit['id'] ==$row['stock_unit_id']){
                                                        echo " selected ";
                                                    }
                                                    echo " value='".$unit['id']."'>".$unit['short_code']."</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                            	    <div class="form-group packate_div">
                            	        
                                        <label for="qty">Estado:</label>
                                        <select name="packate_serve_for[]" class="form-control">
                                            <option value="disponible" <?php if(strtolower($row['serve_for'])=="availabel"){ echo "selected"; }?> >disponible</option>
                                            <option value="agotado" <?php if(strtolower($row['serve_for'])=="agotado"){ echo "selected"; }?> >agotado</option>
                                        </select>
                            		</div>
                            	</div>
                                <?php if($i==0){?>
                                    <div class='col-md-1'>
                                        <label>Variacion</label>
                                        <a id='add_packate_variation' title='Agregar variación de producto' style='cursor: pointer;'><i class="fa fa-plus-square-o fa-2x"></i></a>
                                    </div>
                                    <?php }else{?>
                                    <div class="col-md-1" style="display: grid;">
                                        <label>Eliminar</label>
                                        <a class="remove_variation text-danger" data-id="data_delete" title="Eliminar la variación del producto" style="cursor: pointer;"><i class="fa fa-times fa-2x"></i></a>
                                    </div>
                                <?php }?>
                        </div>
                        <!-- </div> -->
                        <?php $i++;
                        }
                        }else{
                        $db->select('unit','*');
                        $resedit = $db->getResult();
                            ?>
						    <div id="packate_div" style="display:none">
							<div class="row">
							    <div class="col-md-2">
							        <div class="form-group packate_div">
	                                    <label for="exampleInputEmail1">Medicion</label><input type="text" class="form-control" name="packate_measurement[]" required />
	                                </div>
	                            </div>
	                            <div class="col-md-1">
                            	    <div class="form-group packate_div">
                                        <label for="unit">unidad:</label>
                                        <select class="form-control" name="packate_measurement_unit_id[]">
                                            <?php
                                                foreach($resedit as  $row){
                                                    echo "<option value='".$row['id']."'>".$row['short_code']."</option>";
                                                }
                                            ?>
                                        </select>
                            		</div>
                            	</div>
	                            <div class="col-md-2">
	                                <div class="form-group packate_div">
	                                    <label for="price">Precio  (S/.):</label><input type="text" class="form-control" name="packate_price[]" id="packate_price" required />
                            	    </div>
                            	</div>
                            	<div class="col-md-1">
                                    <div class="form-group packate_div">
                            	        <label for="discounted_price">Descuento:</label>
                            	        <input type="text" class="form-control" name="packate_discounted_price[]" id="discounted_price"/>
                            	    </div>
                            	</div>
                            	<div class="col-md-2">
                            	    <div class="form-group packate_div">
                                        <label for="qty">Stock:</label>
                                        <input type="text" class="form-control" name="packate_stock[]" />
                            		</div>
                            	</div>
                            	<div class="col-md-1">
                            	    <div class="form-group packate_div">
                                        <label for="unit">Unidad:</label>
                                        <select class="form-control" name="packate_stock_unit_id[]">
                                            <?php
                                                foreach($resedit as  $row){
                                                    echo "<option value='".$row['id']."'>".$row['short_code']."</option>";
                                                }
                                            ?>
                                        </select>
                            		</div>
                            	</div>
                            	<div class="col-md-2">
                            	    <div class="form-group packate_div">
                                        <label for="qty">Estado:</label>
                                        <select name="packate_serve_for[]" class="form-control" required>
                                            <option value="disponible">Disponible</option>
                                            <option value="agotado">Agotado</option>
                                        </select>
                            		</div>
                            	</div>
                            	<div class="col-md-1">
                                    <label>Variación</label>
                                    <a id="add_packate_variation" title="Agregar variación de producto" style="cursor: pointer;"><i class="fa fa-plus-square-o fa-2x"></i></a>
                            	</div>
                            </div>
                        </div>
					    <?php }?>
                        <div id="packate_variations"></div>
                <?php 
                $i=0;
                if($res[0]['type']=="loose"){
                    foreach($res as $row){
                ?>
                        
                <!-- <div id="loose_div" style="display:block;"> -->
                            <div class="row loose_div">
                <input type="hidden" class="form-control" name="product_variant_id[]" id="product_variant_id" value='<?=$row['product_variant_id'];?>'/>
                                <div class="col-md-4">
                                    <div class="form-group loose_div">
                                        <label for="exampleInputEmail1">Medicion</label>
                                        <input type="text" class="form-control" name="loose_measurement[]" required="" value='<?=$row['measurement'];?>'>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group loose_div">
                                        <label for="unit">Unit:</label>
                                        <select class="form-control" name="loose_measurement_unit_id[]">
                                            <?php
                                                foreach($unit_data as  $unit){
                                                    echo "<option";
                                                    if($unit['id'] ==$row['measurement_unit_id']){
                                                        echo " selected ";
                                                    }
                                                    echo " value='".$unit['id']."'>".$unit['short_code']."</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group loose_div">
                                        <label for="price">precio  (<?=$settings['currency']?>):</label>
                                        <input type="text" class="form-control" name="loose_price[]" id="loose_price" required="" value='<?=$row['price'];?>'>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group loose_div">
                                        <label for="discounted_price">Precio descontado(<?=$settings['currency']?>):</label>
                                        <input type="text" class="form-control" name="loose_discounted_price[]" id="discounted_price"  value='<?=$row['discounted_price'];?>'/>
                                    </div>
                                </div>
                                <?php if($i==0){?>
                                    <div class='col-md-1'>
                                        <label>Variacion</label>
                                        <a id='add_loose_variation' title='Agregar variación de producto' style='cursor: pointer;'><i class="fa fa-plus-square-o fa-2x"></i></a>
                                    </div>
                                    <?php }else{?>
                                    <div class="col-md-1" style="display: grid;">
                                        <label>Eliminar</label>
                                        <a class="remove_variation text-danger" data-id="data_delete" title="Eliminar la variacion del producto" style="cursor: pointer;"><i class="fa fa-times fa-2x"></i></a>
                                    </div>
                                <?php }
                                $i++;?>
                            </div>
                            <!-- </div> -->
                        <?php } ?>
                        <div id="loose_variations"></div>
                        <hr>
                        <div class="form-group" id="loose_stock_div" style="display:block;">
                            <label for="quantity">Stock :</label><?php echo isset($error['quantity']) ? $error['quantity']:'';?>
                            <input type="text" class="form-control" name="loose_stock" required value='<?=$row['stock'];?>'>

                            <label for="stock_unit">Unidad :</label><?php echo isset($error['stock_unit']) ? $error['stock_unit']:'';?>
                            <select class="form-control" name="loose_stock_unit_id" id="loose_stock_unit_id">
                                <?php                                
                                foreach($unit_data as  $unit){
                                    echo "<option";
                                    if($unit['id'] ==$row['stock_unit_id']){
                                        echo " selected ";
                                    }
                                    echo " value='".$unit['id']."'>".$unit['short_code']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                            <?php }else{ 
							$db->select('unit','*');
                            $resedit = $db->getResult();
                        ?>
							    <div id="loose_div" style="display:none;">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group loose_div">
                        		        <label for="exampleInputEmail1">Medicion</label>
                        		        <input type="text" class="form-control" name="loose_measurement[]" required="">
                        		    </div>
                        		</div>
                        		<div class="col-md-2">
                            	    <div class="form-group loose_div">
                                        <label for="unit">Unidad:</label>
                                        <select class="form-control" name="loose_measurement_unit_id[]">
                                            <?php
                                                foreach($resedit as  $row){
                                                    echo "<option value='".$row['id']."'>".$row['short_code']."</option>";
                                                }
                                            ?>
                                        </select>
                            		</div>
                            	</div>
                        		<div class="col-md-3">
                        		    <div class="form-group loose_div">
                            		    <label for="price">Precio (S/.):</label>
                            		    <input type="text" class="form-control" name="loose_price[]" id="loose_price" required="">
                        		    </div>
                        		</div>
                        		<div class="col-md-2">
                        		    <div class="form-group loose_div">
                                		<label for="discounted_price">Precio descontado:</label>
                                		<input type="text" class="form-control" name="loose_discounted_price[]" id="discounted_price"/>
                        		    </div>
                        		</div>
                        		<div class="col-md-1">
                        	        <label>Variacion</label>
                        	        <a id="add_loose_variation" title="Agregar variación de producto" style="cursor: pointer;"><i class="fa fa-plus-square-o fa-2x"></i></a>
                        		</div>
                        	</div>
                        </div>
                         <div id="variations">
						</div>
						<hr>
                        <div class="form-group" id="loose_stock_div" style="display:none;">
                            <label for="quantity">Stock :</label><?php echo isset($error['quantity']) ? $error['quantity']:'';?>
                            <input type="text" class="form-control" name="loose_stock" required>

                            <label for="stock_unit">Unidad :</label><?php echo isset($error['stock_unit']) ? $error['stock_unit']:'';?>
                            <select class="form-control" name="loose_stock_unit_id" id="loose_stock_unit_id">
                                <?php
                                foreach($resedit as $row){
                                    echo "<option value='".$row['id']."'>".$row['short_code']."</option>";
                                }
                                ?>
                            </select>
                        </div>
							<?php } ?>
                <hr>

                <div class="form-group">
                    <div class="form-group" id="status_div" <?php if($res[0]['type']=="packet"){ echo "style='display:none'"; }?>>
                    <label for="exampleInputEmail1">Estado :</label><?php echo isset($error['serve_for']) ? $error['serve_for'] : '';?>
                    <select name="serve_for" class="form-control">
                        <option value="disponible" <?php if(strtolower($res[0]['serve_for'])=="disponible"){ echo "selected"; }?> >Disponible</option>
                        <option value="agotado" <?php if(strtolower($res[0]['serve_for'])=="agotado"){ echo "selected"; }?> >Agotado</option>
                    </select>
                    </div>
                    <div class="form-group">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Categoria:</label><?php echo isset($error['category_id']) ? $error['category_id'] : '';?>
                            <select name="category_id"  id="category_id" class="form-control">
                            <?php 
                                if($permissions['categories']['read']==1){ 
                                foreach($category_data as $row){ ?>
                                <option value="<?php echo $row['id']; ?>" <?=($row['id'] == $data['category_id'])?"selected":"";?>><?php echo $row['name']; ?></option>
                            <?php } }else{ ?>
                                    <option value="" >---selecciona una categoría---</option>
                                <?php } ?>?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Sub Categoria:</label><?php echo isset($error['subcategory_id']) ? $error['subcategory_id'] : '';?>
                            <select name="subcategory_id" id="subcategory_id" class="form-control">

                                <?php 
                                if($permissions['subcategories']['read']==1){
                                foreach($subcategory as $subcategories){ 
                                    $selected = "";
                                    if($res[0]['subcategory_id']==$subcategories['id']){ 
                                            $selected = "selected";                                
                                    } ?>
                                    <option value="<?=$subcategories['id'];?>" <?=$selected?>><?=$subcategories['name']; ?></option>
                                <?php } } else {?>
                                    <option value="">---Seleccionar subcategoría---</option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputFile">Image&nbsp;&nbsp;&nbsp;*Elija una imagen cuadrada de más de 350 px * 350 px y más pequeña que 550 px * 550 px.</label><?php echo isset($error['image']) ? $error['image'] : '';?>
                            <input type="file" name="image" id="image" title="Elija una imagen cuadrada de más de 350 px * 350 px y más pequeña que 550 px * 550 px."/><br />
                            <img src="<?php echo $data['image']; ?>" width="210" height="160"/>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputFile">Other Images *Elija una imagen cuadrada de más de 350 px * 350 px y más pequeña que 550 px * 550 px.</label><?php echo isset($error['other_images']) ? $error['other_images'] : '';?>
                            <input type="file" name="other_images[]" id="other_images" multiple title="Elija una imagen cuadrada de más de 350 px * 350 px y más pequeña que 550 px * 550 px."/><br />
                            <?php
                            if(!empty($data['other_images'])){
                                $other_images = json_decode($data['other_images']);
                            
                            for($i=0;$i<count($other_images);$i++){ ?>
                            <img src="<?=$other_images[$i]; ?>" height="160"/>
                            <a class='btn btn-xs btn-danger delete-image' data-i='<?=$i;?>' data-pid='<?=$_GET['id'];?>'>Eliminar</a>
                            <?php }
                            }?>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Descripcion :</label><?php echo isset($error['description']) ? $error['description'] : '';?>
                            <textarea name="description" id="description" class="form-control" rows="16"><?php echo $data['description']; ?></textarea>
                            <script type="text/javascript" src="css/js/ckeditor/ckeditor.js"></script>
                            <script type="text/javascript">                        
                                CKEDITOR.replace( 'description' );
                            </script>
                        </div>
                    </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <input type="submit" class="btn-primary btn" value="Actualizar" name="btnEdit" />
                    </div>
        </form>
        </div><!-- /.box -->
        </div>
    </div>
</section>
<div class="separator"> </div>
<script>
$(document).on('click','.delete-image',function(){
    var pid = $(this).data('pid');
    var i = $(this).data('i');
    if(confirm('¿Seguro que quieres eliminar la imagen?')){
        $.ajax({
            type:'POST',
            url: 'public/delete-other-images.php',
            data:'i='+i+'&pid='+pid,
            // beforeSend:function(){$('#submit_btn').html('Please wait..');},
            // cache:false,
            // contentType: false,
            // processData: false,
            success:function(result){
                if(result == '1'){
                    alert('Imagen eliminada exitosamente');
                    window.location.replace("ver-variantes-producto.php?id="+pid);
                }
                else
                    alert('¡La imagen no se pudo eliminar!');
                    
            }
        });
    }
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script>
$.validator.addMethod('lessThanEqual', function(value, element, param) {
    return this.optional(element) || parseInt(value) < parseInt($(param).val());
}, "El precio con descuento debe ser menor que el precio");
</script>
<script>
$('#edit_product_form').validate({
    rules:{
        name:"required",
        measurement:"required",
        price:"required",
        quantity:"required",
        // image:"required",
        discounted_price: { lessThanEqual: "#price" }
    }
});
</script>
<script>
$('#add_loose_variation').on('click',function(){
    html = '<div class="row"><div class="col-md-4"><div class="form-group loose_div">'
    +'<label for="exampleInputEmail1">Medicion</label><input type="text" class="form-control" name="insert_loose_measurement[]" required="">'
    +'</div></div>'
    +'<div class="col-md-2"><div class="form-group loose_div">'
    +'<label for="unit">Unidad:</label>'
    +'<select class="form-control" name="insert_loose_measurement_unit_id[]">'
    +'<?php foreach($unit_data as  $unit){ echo "<option value=".$unit['id'].">".$unit['short_code']."</option>";}?>'
    +'</select></div></div>'
    +'<div class="col-md-3"><div class="form-group loose_div">'
    +'<label for="price">Price  (<?=$settings['currency']?>):</label>'
    +'<input type="text" class="form-control" name="insert_loose_price[]" id="loose_price" required="">'
    +'</div></div>'
    +'<div class="col-md-2"><div class="form-group loose_div">'
    +'<label for="discounted_price">Precio descontado(<?=$settings['currency']?>):</label>'
    +'<input type="text" class="form-control" name="insert_loose_discounted_price[]" id="discounted_price"/>'
    +'</div></div>'
    +'<div class="col-md-1" style="display: grid;">'
    +'<label>Eliminar</label><a class="remove_variation text-danger" data-id="remove" title="Remove variation of product" style="cursor: pointer;"><i class="fa fa-times fa-2x"></i></a>'
    +'</div></div>';
    $('#loose_variations').append(html);
});

$('#add_packate_variation').on('click',function(){
    html = '<div class="row"><div class="col-md-2"><div class="form-group packate_div">'
        +'<label for="exampleInputEmail1">Medicion</label><input type="text" class="form-control" name="insert_packate_measurement[]" required />'
        +'</div></div>'
        +'<div class="col-md-2"><div class="form-group packate_div">'
        +'<label for="unit">Unidad:</label>'
        +'<select class="form-control" name="insert_packate_measurement_unit_id[]">'
        +'<?php foreach($unit_data as  $unit){echo "<option value=".$unit['id'].">".$unit['short_code']."</option>";}?>'
        +'</select></div></div>'
        +'<div class="col-md-2"><div class="form-group packate_div">'
        +'<label for="price">Precio  (<?=$settings['currency']?>):</label><input type="text" class="form-control" name="insert_packate_price[]" id="packate_price" required />'
        +'</div></div>'
        +'<div class="col-md-2"><div class="form-group packate_div">'
        +'<label for="discounted_price">Precio descontado(<?=$settings['currency']?>):</label>'
        +'<input type="text" class="form-control" name="insert_packate_discounted_price[]" id="discounted_price"/>'
        +'</div></div>'
        +'<div class="col-md-1"><div class="form-group packate_div">'
        +'<label for="qty">Stock:</label>'
        +'<input type="text" class="form-control" name="insert_packate_stock[]"/>'
        +'</div></div>'
        +'<div class="col-md-2"><div class="form-group packate_div">'
        +'<label for="unit">Unit:</label><select class="form-control" name="insert_packate_stock_unit_id[]">'
        +'<?php foreach($unit_data as  $unit){echo "<option value=".$unit['id'].">".$unit['short_code']."</option>";}?>'
        +'</select></div></div>'
        +'<div class="col-md-1" style="display: grid;">'
        +'<label>Eliminar</label><a class="remove_variation text-danger" data-id="remove" title="Eliminar la variación del producto" style="cursor: pointer;"><i class="fa fa-times fa-2x"></i></a>'
        +'</div></div>';
    $('#packate_variations').append(html);
});
</script>
<script>
$(document).on('click','.remove_variation',function(){
    if($(this).data('id')=='data_delete'){
        if(confirm('¿Estás seguro? ¿Quieres eliminar esta fila?')){
            // id = $('#product_variant_id').val();
            var id = $(this).closest('div.row').find("input[id='product_variant_id']").val();
            $.ajax({
                url:'public/db-operation.php',
                type: "post",
                data: 'id='+id+'&delete_variant=1',
                success: function(result){
                    //alert(result);
                    // $('#class_list').bootstrapTable("refresh");
                    location.reload();
                }
            });
        }
    }else{
        $(this).closest('.row').remove();
    }
});




$(document).on('change','#category_id',function(){
//   alert('change');
    $.ajax({
       url:'public/db-operation.php',
       method:'POST',
       data:'category_id='+$('#category_id').val()+'&find_subcategory=1',
       success:function(data){
          // alert(data);
           $('#subcategory_id').html(data);
       }
    });
});
$(document).on('change','#packate',function(){
    // alert('packate');
    // $('#variations').html("");
    $('#packate_div').show();
    $('.packate_div').show();
    // $('.packate_div').children(":input").prop('disabled',false);
     $('#loose_div').hide();
    $('.loose_div').hide();
    
    $('#status_div').hide();
    // $('.loose_div').children(":input").prop('disabled',true);
    $('#loose_stock_div').hide();
    // $('#loose_stock_div').children(":input").prop('disabled',true);
    
});
$(document).on('change','#loose',function(){
    // $('#variations').html("");
    // alert('loose');
    $('#loose_div').show();
    $('.loose_div').show();
    // $('.loose_div').children(":input").prop('disabled',false);
    $('#loose_stock_div').show();
    // $('#loose_stock_div').children(":input").prop('disabled',false);
    $('#status_div').show();
    $('#packate_div').hide();
    $('.packate_div').hide();
    // $('.packate_div').children(":input").prop('disabled',true);
    
});
</script>