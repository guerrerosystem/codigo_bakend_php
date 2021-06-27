
<?php 
	include_once('includes/functions.php'); 
	$function = new functions;
	
?>
	<?php 
		if(isset($_POST['btnAdd'])){
			if($permissions['categories']['create']==1){

			$category_name = $_POST['category_name'];
			$category_subtitle = $_POST['category_subtitle'];
			
			
			$menu_image = $_FILES['category_image']['name'];
			$image_error = $_FILES['category_image']['error'];
			$image_type = $_FILES['category_image']['type'];

			$error = array();
			
			if(empty($category_name)){
				$error['category_name'] = " <span class='label label-danger'>obligatorio!</span>";
			}
			if(empty($category_subtitle)){
				$error['category_subtitle'] = " <span class='label label-danger'>obligatorio!</span>";
			}
		
			$allowedExts = array("gif", "jpeg", "jpg", "png");
			
		
			error_reporting(E_ERROR | E_PARSE);
			$extension = end(explode(".", $_FILES["category_image"]["name"]));
					
			if($image_error > 0){
				$error['category_image'] = " <span class='label label-danger'>¡No subido!!</span>";
			}else if(!(($image_type == "image/gif") || 
				($image_type == "image/jpeg") || 
				($image_type == "image/jpg") || 
				($image_type == "image/x-png") ||
				($image_type == "image/png") || 
				($image_type == "image/pjpeg")) &&
				!(in_array($extension, $allowedExts))){
			
				$error['category_image'] = " <span class='label label-danger'>¡El tipo de imagen debe ser jpg, jpeg, gif o png!</span>";
			}
			
			if(!empty($category_name) && !empty($category_subtitle) && empty($error['category_image'])){
				
			
				$string = '0123456789';
				$file = preg_replace("/\s+/", "_", $_FILES['category_image']['name']);
			
				$menu_image = $function->get_random_string($string, 4)."-".date("Y-m-d").".".$extension;
					
			
				$upload = move_uploaded_file($_FILES['category_image']['tmp_name'], 'upload/images/'.$menu_image);
		
			
				$upload_image = 'upload/images/'.$menu_image;
				$sql_query = "INSERT INTO category (name,subtitle, image)
						VALUES('$category_name', '$category_subtitle', '$upload_image')";
				
					$db->sql($sql_query);
				
					$result = $db->getResult();
					if(!empty($result)){
						$result=0;
					}else{
						$result=1;
					}
				
				
				if($result==1){
					$error['add_category'] = " <section class='content-header'>
												<span class='label label-success'>Categoría añadida con éxito</span>
												
												
												</section>";
				}else{
					$error['add_category'] = " <span class='label label-danger'>Error al agregar categoría</span>";
				}
			}
		}else{
			$error['check_permission'] = " <section class='content-header'>
												<span class='label label-danger'>No tienes permiso para crear categoría</span>
												
												
												</section>";
		}
		}
	?>
	<section class="content-header">
          <h1>añadir categoría <small><a  href='categorias.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Regresar a las categorías</a></small></h1>

			<?php echo isset($error['add_category']) ? $error['add_category'] : '';?>
			<ol class="breadcrumb">
            <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
          </ol>
		<hr />
        </section>
	<section class="content">
	 <div class="row">
		  <div class="col-md-6">
		  	<?php if($permissions['categories']['create']==0) { ?>
        	<div class="alert alert-danger">No tienes permiso para crear una categoría.</div>
        <?php } ?>
              <!-- general form elements -->
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">añadir categoría</h3>

                </div><!-- /.box-header -->
                <!-- form start -->
                <form  method="post"
			enctype="multipart/form-data">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="exampleInputEmail1">nombre de la categoría</label><?php echo isset($error['category_name']) ? $error['category_name'] : '';?>
                      <input type="text" class="form-control"  name="category_name" required>
                    </div>
					<div class="form-group">
                      <label for="exampleInputEmail1">Subtítulo de categoría</label><?php echo isset($error['category_subtitle']) ? $error['category_subtitle'] : '';?>
                      <input type="text" class="form-control"  name="category_subtitle" required>
                    </div>
                    
                    <div class="form-group">
                      <label for="exampleInputFile">Imagen&nbsp;&nbsp;&nbsp;*Elija una imagen cuadrada de más de 350 px * 350 px y más pequeña que 550 px * 550 px.</label><?php echo isset($error['category_image']) ? $error['category_image'] : '';?>
                      <input type="file" name="category_image" id="category_image" required/>
                    </div>
                  </div><!-- /.box-body -->

                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary" name="btnAdd">Agregar</button>
					<input type="reset" class="btn-warning btn" value="Limpiar"/>
				
                  </div>

                </form>

              </div><!-- /.box -->
              <?php echo isset($error['check_permission']) ? $error['check_permission'] : '';?>
			 </div>
		  </div>
	</section>

	<div class="separator"> </div>
	
<?php $db->disconnect(); ?>
	