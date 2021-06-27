<?php
	include_once('includes/functions.php');
    $function = new functions;
?>
	<?php 
		if(isset($_POST['btnAdd'])){
			if($permissions['subcategories']['create']==1){
			$subcategory_name = $_POST['subcategory_name'];
			$slug = $function->slugify($_POST['subcategory_name']);
    		  $sql = "SELECT slug FROM subcategory";
              $db->sql($sql);
              $res = $db->getResult();
              $i=1;
              foreach($res as $row){
                  if($slug==$row['slug']){
                    $slug = $slug.'-'.$i;
                    $i++;  
                  }
              }
			$category_subtitle = $_POST['category_subtitle'];
			$main_category = $_POST['main_category_name'];
			
			$menu_image = $_FILES['category_image']['name'];
			$image_error = $_FILES['category_image']['error'];
			$image_type = $_FILES['category_image']['type'];
			
	
			$error = array();
			
			if(empty($subcategory_name)){
				$error['subcategory_name'] = " <span class='label label-danger'>obligatorio!</span>";
			}
			if(empty($category_subtitle)){
				$error['category_subtitle'] = " <span class='label label-danger'>obligatorio!</span>";
			}
			
			// common image file extensions
			$allowedExts = array("gif", "jpeg", "jpg", "png");
			
			// get image file extension
			error_reporting(E_ERROR | E_PARSE);
			$extension = end(explode(".", $_FILES["category_image"]["name"]));
					
			if($image_error > 0){
				$error['category_image'] = " <span class='label label-danger'>Nosubido!</span>";
			}else if(!(($image_type == "image/gif") || 
				($image_type == "image/jpeg") || 
				($image_type == "image/jpg") || 
				($image_type == "image/x-png") ||
				($image_type == "image/png") || 
				($image_type == "image/pjpeg")) &&
				!(in_array($extension, $allowedExts))){
			
				$error['category_image'] = " <span class='label label-danger'>¡El tipo de imagen debe ser jpg, jpeg, gif o png!</span>";
			}
			
			if(!empty($subcategory_name) && !empty($category_subtitle) && empty($error['category_image'])){
				
				// create random image file name
				$string = '0123456789';
				$file = preg_replace("/\s+/", "_", $_FILES['category_image']['name']);
				
				$menu_image = $function->get_random_string($string, 4)."-".date("Y-m-d").".".$extension;
					
				// upload new image
				$upload = move_uploaded_file($_FILES['category_image']['tmp_name'], 'upload/images/'.$menu_image);
		
				// insert new data to menu table
				$upload_image = 'upload/images/'.$menu_image;
				$sql_query = "INSERT INTO subcategory (category_id, name, slug, subtitle, image)
						VALUES('$main_category', '$subcategory_name', '$slug', '$category_subtitle', '$upload_image')";
				
				
					// Execute query
					$db->sql($sql_query);
					// store result 
					$result = $db->getResult();
					if(!empty($result)){
						$result=0;
					}else{
						$result=1;
					}
				if($result==1){
					$error['add_category'] = " <section class='content-header'>
												<span class='label label-success'>Sub categoría añadida con éxito</span>
												<h4><small><a  href='subcategorias.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Volver a las subcategorías</a></small></h4>
												
												</section>";
				}else{
					$error['add_category'] = " <span class='label label-danger'>Error al agregar categoría</span>";
				}
			}
			
		}else{
		$error['check_permission'] = " <section class='content-header'>
												<span class='label label-danger'>No tienes permiso para crear una subcategoría</span>
												
												
												</section>";
	}
	}

		if(isset($_POST['btnCancel'])){
			header("location:subcategorias.php");
		}

	?>
	<section class="content-header">
          <h1>Añadir subcategoría<small><a  href='subcategorias.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Volver a las subcategorías</a></small></h1>
			<?php echo isset($error['add_category']) ? $error['add_category'] : '';?>
			<ol class="breadcrumb">
            <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
          </ol>
		<hr />
        </section>
	<section class="content">
	 <div class="row">
		  <div class="col-md-6">
		  	 <?php if($permissions['subcategories']['create']==0) { ?>
        		<div class="alert alert-danger">No tienes permiso para crear una subcategoría.</div>
        	<?php } ?>
              <!-- general form elements -->
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Añadir subcategoría</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form  method="post"
			enctype="multipart/form-data">
                  <div class="box-body">
                       <div class="form-group">
                      <label for="exampleInputEmail1">categoria principal</label><?php echo isset($error['main_category_name']) ? $error['main_category_name'] : '';?>
                        <select class="form-control" id="main_category_name" name="main_category_name" required>
                            <option value="">--Seleccionar categoria principal--</option>
                            <?php
                            if($permissions['categories']['read']==1){
                                $db->select('category','*',null);
                                $res = $db->getResult();
                                foreach($res as $category){
                                    echo "<option value='".$category['id']."'>".$category['name']."</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Nombre de subcategoría</label><?php echo isset($error['subcategory_name']) ? $error['subcategory_name'] : '';?>
                      <input type="text" class="form-control"  name="subcategory_name" required>
                    </div>
					<div class="form-group">
                      <label for="exampleInputEmail1">Subcategoría Subtítulo</label><?php echo isset($error['category_subtitle']) ? $error['category_subtitle'] : '';?>
                      <input type="text" class="form-control"  name="category_subtitle" required>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputFile">Image&nbsp;&nbsp;&nbsp;*Elija una imagen cuadrada de más de 350 px * 350 px y más pequeña que 550 px * 550 px.</label><?php echo isset($error['category_image']) ? $error['category_image'] : '';?>
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
	