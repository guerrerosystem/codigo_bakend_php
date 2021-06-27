<?php 
    include_once('includes/functions.php'); 
	date_default_timezone_set('Asia/Kolkata');
	$function = new functions;
    
    $sql_query = "SELECT id, name 
    	FROM category 
    	ORDER BY id ASC";	
    	
    	$db->sql($sql_query);
    	
    	$res=$db->getResult();
    $sql_query = "SELECT value FROM settings WHERE variable = 'Currency'";
  
        $db->sql($sql_query);
    	
    	
        $res_cur=$db->getResult();
    	
    	
    if(isset($_POST['btnAdd'])){
        if($permissions['products']['create']==1){
        // print_r($_POST);
		$name = $_POST['name'];
		$slug = $function->slugify($_POST['name']);
    	$category_id = $_POST['category_id'];
    	$subcategory_id = $_POST['subcategory_id'];
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
    		
    	if(empty($price)){
    		$error['price'] = " <span class='label label-danger'>obligatorio!</span>";
    	}/* else if(!is_numeric($price)){
    		$error['price'] = " <span class='label label-danger'>Price in number!</span>";
    	} */
    	
    	if(empty($measurement)){
    		$error['measurement'] = " <span class='label label-danger'>obligatorio!</span>";
    	}
    	
    	if(empty($quantity)){
    		$error['quantity'] = " <span class='label label-danger'>obligatorio!</span>";
    	}else if(!is_numeric($quantity)){
    		$error['quantity'] = " <span class='label label-danger'>Cantodad en numero</span>";
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
		
    	if($image_error > 0){
    		$error['image'] = " <span class='label label-danger'>No subio</span>";
    	}else if(!(($image_type == "image/gif") || 
    		($image_type == "image/jpeg") || 
    		($image_type == "image/jpg") || 
    		($image_type == "image/x-png") ||
    		($image_type == "image/png") || 
    		($image_type == "image/pjpeg")) &&
    		!(in_array($extension, $allowedExts))){
    	
    		$error['image'] = " <span class='label label-danger'>¡El tipo de imagen debe ser jpg, jpeg, gif o png!</span>";
    	}
		$error['other_images'] = '';
		if($_FILES["other_images"]["error"][0] == 0){
			for($i=0;$i<count($_FILES["other_images"]["name"]);$i++){
				$_FILES["other_images"]["type"][$i];
				if($_FILES["other_images"]["error"][$i] > 0){
					$error['other_images'] = " <span class='label label-danger'>¡Imágenes no cargadas!</span>";
				}else if(!(($_FILES["other_images"]["type"][$i] == "image/gif") || 
					($_FILES["other_images"]["type"][$i] == "image/jpeg") || 
					($_FILES["other_images"]["type"][$i] == "image/jpg") || 
					($_FILES["other_images"]["type"][$i] == "image/x-png") ||
					($_FILES["other_images"]["type"][$i] == "image/png") || 
					($_FILES["other_images"]["type"][$i] == "image/pjpeg")) &&
					!(in_array($_FILES["other_images"]["type"][$i], $allowedExts))){
					$error['other_images'] = " <span class='label label-danger'>El tipo de imágenes debe jpg, jpeg, gif o png!</span>";
				}
			}
		}
    
    if(!empty($name) && !empty($category_id) && !empty($serve_for) && empty($error['image']) && empty($error['other_images']) && !empty($description)){
    		
			// create random image file name
    		$string = '0123456789';
    		$file = preg_replace("/\s+/", "_", $_FILES['image']['name']);
    		
    		$image = $function->get_random_string($string, 4)."-".date("Y-m-d").".".$extension;
    			
    		// upload new image
    		$upload = move_uploaded_file($_FILES['image']['tmp_name'], 'upload/images/'.$image);
			$other_images = '';
			if(isset($_FILES['other_images']) && ($_FILES['other_images']['size'][0] > 0 )){
				//Upload other images
				$file_data = array();
				$target_path = 'upload/other_images/';
				for($i=0;$i<count($_FILES["other_images"]["name"]);$i++){
					
					$filename = $_FILES["other_images"]["name"][$i];
					$temp = explode('.',$filename);
					$filename = microtime(true) . '.' . end($temp);
					$file_data[] = $target_path.''.$filename;
					if(!move_uploaded_file($_FILES["other_images"]["tmp_name"][$i], $target_path.''.$filename))
						echo "{$_FILES['image']['name'][$i]} not uploaded<br/>";
				}
				$other_images = json_encode($file_data);
			}
			
			$upload_image = 'upload/images/'.$image;
            if (strpos($name, "'") !== false) {
                $name=str_replace("'", "''", "$name");
                if(strpos($description, "'") !== false)
                    $description=str_replace("'", "''", "$description");
            }
    		// insert new data to product table
                $sql="INSERT INTO products (name,slug,category_id,subcategory_id,image,other_images,description) VALUES('$name','$slug','$category_id','$subcategory_id','$upload_image','$other_images','$description')";
                $db->sql($sql);
    			$product_id = $db->getResult();
                 if(!empty($product_id)){
                    $product_id=0;
                }
                else{
                    $product_id=1;

                }
                // print_r($product_id);
                $sql="SELECT id from products ORDER BY id DESC";
                $db->sql($sql);
                $res_inner=$db->getResult();
			if($_POST['type']=='packet'){
			    for($i=0;$i<count($_POST['packate_measurement']);$i++){
                    $product_id=$db->escapeString($res_inner[0]['id']);
                    $type=$db->escapeString($_POST['type']);
                    $measurement=$db->escapeString($_POST['packate_measurement'][$i]);
                    $measurement_unit_id=$db->escapeString($_POST['packate_measurement_unit_id'][$i]);
                    $price=$db->escapeString($_POST['packate_price'][$i]);
                    $discounted_price=!empty($_POST['packate_discounted_price'][$i])?$db->escapeString($_POST['packate_discounted_price'][$i]):0;
                    $serve_for=$db->escapeString($_POST['packate_serve_for'][$i]);
                    $stock=$db->escapeString($_POST['packate_stock'][$i]);
                    $stock_unit_id=$db->escapeString($_POST['packate_stock_unit_id'][$i]);

                    $sql="INSERT INTO product_variant (product_id,type,measurement,measurement_unit_id,price,discounted_price,serve_for,stock,stock_unit_id) VALUES('$product_id','$type','$measurement','$measurement_unit_id','$price','$discounted_price','$serve_for','$stock','$stock_unit_id')";
                     $db->sql($sql);
                     $product_variant = $db->getResult();   
			    }
                    if(!empty($product_variant)){
                    $product_variant=0;
                }
                else{
                    $product_variant=1;
                }
                // print_r($product_variant);
			    
			}elseif($_POST['type']=="loose"){
			    for($i=0;$i<count($_POST['loose_measurement']);$i++){
                    $product_id=$db->escapeString($res_inner[0]['id']);
                    $type=$db->escapeString($_POST['type']);
                    $measurement=$db->escapeString($_POST['loose_measurement'][$i]);
                    $measurement_unit_id=$db->escapeString($_POST['loose_measurement_unit_id'][$i]);
                    $price=$db->escapeString($_POST['loose_price'][$i]);
                    $discounted_price=!empty($_POST['loose_discounted_price'][$i])?$db->escapeString($_POST['loose_discounted_price'][$i]):0;
                    $serve_for=$db->escapeString($_POST['serve_for']);
                    $stock=$db->escapeString($_POST['loose_stock']);
                    $stock_unit_id=$db->escapeString($_POST['loose_stock_unit_id']);

                    $sql="INSERT INTO product_variant (product_id,type,measurement,measurement_unit_id,price,discounted_price,serve_for,stock,stock_unit_id) VALUES('$product_id','$type','$measurement','$measurement_unit_id','$price','$discounted_price','$serve_for','$stock','$stock_unit_id')";
                     $db->sql($sql);
                     $product_variant = $db->getResult();
               
			    }
                     if(!empty($product_variant)){
                     $product_variant=0;
                    }
                else{
                    $product_variant=1;
                }
                // print_r($product_variant);
			}
    		if($product_variant==1){
    			$error['add_menu'] = "<section class='content-header'>
                                                <span class='label label-success'>Producto agregado exitosamente</span>
                                                <h4><small><a  href='productos.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Volver a productos</a></small></h4>
                                                
                                                </section>";
    		}else {
    			$error['add_menu'] = " <span class='label label-danger'>Failed</span>";
    		}
    	}
        }else{
        $error['check_permission'] = " <section class='content-header'>
                                                <span class='label label-danger'>No tienes permiso para crear producto</span>
                                                
                                                
                                                </section>";

        
    }
    }
    ?>
<section class="content-header">
    <h1>Añadir Producto</h1>
    <?php echo isset($error['add_menu']) ? $error['add_menu'] : '';?>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
    <hr />
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
             <?php if(!isset($permissions['products']['create']) || $permissions['products']['create']==0) { ?>
                <div class="alert alert-danger">No tienes permiso para crear producto.</div>
            <?php } ?>
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Añadir Producto</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form id='add_product_form' method="post" enctype="multipart/form-data">
                     <?php 
                        // $db->select('unit','*');
                     $sql="SELECT * FROM unit";
                     $db->sql($sql);
                     $res_unit = $db->getResult();
                     ?>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Nombre del producto</label><?php echo isset($error['name']) ? $error['name'] : '';?>
                            <input type="text" class="form-control"  name="name" required>
                        </div>
                        
                        <label for="type">Tipo</label><?php echo isset($error['type']) ? $error['type'] : '';?>
                        <div class="form-group">
                          <label class="radio-inline"><input type="radio" name="type"  id="packate" value="packet" checked>Paquete</label>
                          <label class="radio-inline"><input type="radio" name="type" id ="loose" value="loose">Suelto</label>
                        </div>
                        <hr>
						<div id="packate_div" style="display:none">
							<div class="row">
							    <div class="col-md-2">
							        <div class="form-group packate_div">
	                                    <label for="exampleInputEmail1">Medicion</label><input type="text" class="form-control" name="packate_measurement[]" required />
	                                </div>
	                            </div>
	                            <div class="col-md-1">
                            	    <div class="form-group packate_div">
                                        <label for="unit">Unidad:</label>
                                        <select class="form-control" name="packate_measurement_unit_id[]">
                                            <?php
                                                foreach($res_unit as  $row){
                                                    echo "<option value='".$row['id']."'>".$row['short_code']."</option>";
                                                }
                                            ?>
                                        </select>
                            		</div>
                            	</div>
	                            <div class="col-md-2">
	                                <div class="form-group packate_div">
	                                    <label for="price">Precio  (<?=$settings['currency']?>):</label><input type="text" class="form-control" name="packate_price[]" id="packate_price" required />
                            	    </div>
                            	</div>
                            	<div class="col-md-2">
                                    <div class="form-group packate_div">
                            	        <label for="discounted_price">Precio descontado(<?=$settings['currency']?>):</label>
                            	        <input type="text" class="form-control" name="packate_discounted_price[]" id="discounted_price"/>
                            	    </div>
                            	</div>
                            	<div class="col-md-1">
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
                                                foreach($res_unit as  $row){
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
                                    <label>Variation</label>
                                    <a id="add_packate_variation" title="Agregar variación de producto" style="cursor: pointer;"><i class="fa fa-plus-square-o fa-2x"></i></a>
                            	</div>
                            </div>
                        </div>
                            
                            
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
                                                foreach($res_unit as  $row){
                                                    echo "<option value='".$row['id']."'>".$row['short_code']."</option>";
                                                }
                                            ?>
                                        </select>
                            		</div>
                            	</div>
                        		<div class="col-md-3">
                        		    <div class="form-group loose_div">
                            		    <label for="price">Precio  (<?=$settings['currency']?>):</label>
                            		    <input type="text" class="form-control" name="loose_price[]" id="loose_price" required="">
                        		    </div>
                        		</div>
                        		<div class="col-md-2">
                        		    <div class="form-group loose_div">
                                		<label for="discounted_price">Precio descontado(<?=$settings['currency']?>):</label>
                                		<input type="text" class="form-control" name="loose_discounted_price[]" id="discounted_price"/>
                        		    </div>
                        		</div>
                        		<div class="col-md-1">
                        	        <label>Variación</label>
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
                                foreach($res_unit as $row){
                                    echo "<option value='".$row['id']."'>".$row['short_code']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group" id="packate_server_hide">
                            <label for="serve_for">Status :</label><?php echo isset($error['serve_for']) ? $error['serve_for'] : '';?>
                            <select name="serve_for" class="form-control" required>
                                <option value="disponible">Disponible</option>
                                <option value="agotado">Agotado</option>
                            </select>
                            <br/>
                        </div>
                        <div class="form-group">
                            <label for="category_id">Categoria :</label><?php echo isset($error['category_id']) ? $error['category_id'] : '';?>
                             <select name="category_id" id="category_id" class="form-control" required>
                                <option value="">--selecciona una categoría--</option>
                                <?php if($permissions['categories']['read']==1) { ?>
                                <?php foreach($res as $row){ ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                <?php } 

                            }?>
                            </select>
                            <br/>
                        </div>
                        <div class="form-group">
                            <label for="subcategory_id">Sub Categoria :</label><?php echo isset($error['subcategory_id']) ? $error['subcategory_id'] : '';?>
                            <select name="subcategory_id" id="subcategory_id" class="form-control" required>
                                <option value="">--Seleccionar categoría principal--</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="image">Imagen principal :&nbsp;&nbsp;&nbsp;*Elija una imagen cuadrada de más de 350 px * 350 px y más pequeña que 550 px * 550 px.</label><?php echo isset($error['image']) ? $error['image'] : '';?>
                            <input type="file" name="image" id="image" required>
                        </div>
                        <div class="form-group">
                            <label for="other_images">Otras imágenes del producto: *Elija una imagen cuadrada de más de 350 px * 350 px y más pequeña que 550 px * 550 px..</label><?php echo isset($error['other_images']) ? $error['other_images'] : '';?>
							<input type="file" name="other_images[]" id="other_images" multiple>
                        </div>
                        <div class="form-group">
                            <label for="description">Descripcion:</label><?php echo isset($error['description']) ? $error['description'] : '';?>
                            <textarea name="description" id="description" class="form-control" rows="8"></textarea>
                            <script type="text/javascript" src="css/js/ckeditor/ckeditor.js"></script>
							<script type="text/javascript">CKEDITOR.replace('description');</script>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <input type="submit" class="btn-primary btn" value="Agregar" name="btnAdd" />&nbsp;
                        <input type="reset" class="btn-danger btn" value="Limpiar"/>
                        <!--<div  id="res"></div>-->
                    </div>
                </form>
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>
<div class="separator"> </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script>

 if($('#packate').prop('checked')){
     $('#packate_div').show();
    $('#packate_server_hide').hide();
     $('.loose_div').children(":input").prop('disabled',true);
    $('#loose_stock_div').children(":input").prop('disabled',true);
 }
 
$.validator.addMethod('lessThanEqual', function(value, element, param) {
    return this.optional(element) || parseInt(value) < parseInt($(param).val());
}, "Discounted Price should be lesser than Price");
</script>
<script>
$('#add_product_form').validate({
    ignore: [],
    debug: false,
	rules:{
		name:"required",
		measurement:"required",
		price:"required",
		quantity:"required",
		image:"required",
		discounted_price: { lessThanEqual: "#price" },
		description: {
              required: function(textarea) {
              CKEDITOR.instances[textarea.id].updateElement();
              var editorcontent = textarea.value.replace(/<[^>]*>/gi, '');
              return editorcontent.length === 0;
            }
        }
	}
});
</script>
<script>
var num = 2;
$('#add_packate_variation').on('click',function(){     
	html = '<div class="row"><div class="col-md-2"><div class="form-group"><label for="measurement">Medicion</label>'
		+'<input type="text" class="form-control" name="packate_measurement[]" required=""></div></div>'
	    +'<div class="col-md-1"><div class="form-group">'
	    +'<label for="measurement_unit">Unit</label><select class="form-control" name="packate_measurement_unit_id[]">'
        +'<?php
            foreach($res_unit as $row){
                echo "<option value=".$row['id'].">".$row['short_code']."</option>";
            }   
            ?>'
        +'</select></div></div>'
		+'<div class="col-md-2"><div class="form-group"><label for="price">precio(<?=$settings['currency']?>):</label>'
		+'<input type="text" class="form-control" name="packate_price[]" required=""></div></div>'
		+'<div class="col-md-2"><div class="form-group"><label for="discounted_price">Precio descontado(<?=$settings['currency']?>):</label>'
		+'<input type="text" class="form-control" name="packate_discounted_price[]" /></div></div>'
		+'<div class="col-md-1"><div class="form-group"><label for="stock">Stock:</label>'
		+'<input type="text" class="form-control" name="packate_stock[]" /></div></div>'
		+'<div class="col-md-1"><div class="form-group"><label for="unit">unidad:</label>'
        +'<select class="form-control" name="packate_stock_unit_id[]">'
        +'<?php
            foreach($res_unit as  $row){
                echo "<option value=".$row['id'].">".$row['short_code']."</option>";
            }
        ?>'
        +'</select>'
        +'</div></div>'
        +'<div class="col-md-2"><div class="form-group packate_div"><label for="qty">Status:</label><select name="packate_serve_for[]" class="form-control" required><option value="disponible">disponible</option><option value="agotado">agotado</option></select></div></div>'
		+'<div class="col-md-1" style="display: grid;"><label>Eliminar</label><a class="remove_variation text-danger" title="Eliminar la variación del producto" style="cursor: pointer;"><i class="fa fa-times fa-2x"></i></a></div>'
		+'</div>';
		
	$('#variations').append(html);
	$('#add_product_form').validate();
});

$('#add_loose_variation').on('click',function(){
	html = '<div class="row"><div class="col-md-4"><div class="form-group"><label for="measurement">Meidicion</label>'
		+'<input type="text" class="form-control" name="loose_measurement[]" required=""></div></div>'
		+'<div class="col-md-2"><div class="form-group loose_div">'
        +'<label for="unit">unidad:</label><select class="form-control" name="loose_measurement_unit_id[]">'
        +'<?php
            foreach($res_unit as  $row){
                echo "<option value=".$row['id'].">".$row['short_code']."</option>";
            }
        ?>'
        +'</select></div></div>'
		+'<div class="col-md-3"><div class="form-group"><label for="price">Precio  (<?=$settings['currency']?>):</label>'
		+'<input type="text" class="form-control" name="loose_price[]" required=""></div></div>'
		+'<div class="col-md-2"><div class="form-group"><label for="discounted_price">Precio descontado(<?=$settings['currency']?>):</label>'
		+'<input type="text" class="form-control" name="loose_discounted_price[]" /></div></div>'
		+'<div class="col-md-1" style="display: grid;"><label>Eliminar</label><a class="remove_variation text-danger" title="Eliminar la variación del producto" style="cursor: pointer;"><i class="fa fa-times fa-2x"></i></a></div>'
		+'</div>';
	$('#variations').append(html);
});
</script>
<script>
$(document).on('click','.remove_variation',function(){
	$(this).closest('.row').remove();
});


$(document).on('change','#category_id',function(){
//   alert("change");
    $.ajax({
    
      url:"public/db-operation.php",
      data:"category_id="+$('#category_id').val()+"&change_category=1",
       method:"POST",
       success:function(data){
        //   alert(data);
           $('#subcategory_id').html(data);
        //   $('#res').html(data);
       }
    });
});

$(document).on('change','#packate',function(){
    $('#variations').html("");
    $('#packate_div').show();
    $('#packate_server_hide').hide();
    $('.packate_div').children(":input").prop('disabled',false);
    $('#loose_div').hide();
    $('.loose_div').children(":input").prop('disabled',true);
    $('#loose_stock_div').hide();
    $('#loose_stock_div').children(":input").prop('disabled',true);
    
});
$(document).on('change','#loose',function(){
    $('#variations').html("");
    $('#loose_div').show();
    $('.loose_div').children(":input").prop('disabled',false);
    $('#loose_stock_div').show();
    $('#loose_stock_div').children(":input").prop('disabled',false);
       $('#packate_server_hide').show();
    $('#packate_div').hide();
    $('.packate_div').children(":input").prop('disabled',true);
    
});
</script>