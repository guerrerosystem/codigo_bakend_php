<?php
	include_once('includes/functions.php');
	$function = new functions;
?>
	<?php 
		if(isset($_GET['id'])){
			$ID = $_GET['id'];
		}else{
			$ID = "";
		}
		
		$subcategory_data = array();
				$db->select('subcategory','image',null,'id='.$ID);
			
			$res=$db->getResult();
			$previous_subcategory_image=$res[0]['image'];
		if(isset($_POST['btnEdit'])){
			if($exists==1 || $_SESSION['role']=='super admin'){
			if($permissions['subcategories']['update']==1){
		  
			$category = $_POST['category'];
			$name = $_POST['name'];
			$slug = $function->slugify($_POST['name']);
    		  $sql = "SELECT slug,city_ids FROM subcategory where id=".$_GET['id'];
              $db->sql($sql);
              $res = $db->getResult();
              $i=1;
              foreach($res as $row){
                  if($slug==$row['slug']){
                    $slug = $slug.'-'.$i;
                    $i++;  
                  }
              }
			$subtitle = $_POST['subtitle'];
			
			$menu_image = $_FILES['image']['name'];
			$image_error = $_FILES['image']['error'];
			$image_type = $_FILES['image']['type'];
				
			
			$error = array();
				
			if(empty($name)){
				$error['name'] = " <span class='label label-danger'>obigatorio!</span>";
			}
			if(empty($subtitle)){
				$error['subtitle'] = " <span class='label label-danger'>obigatorio!</span>";
			}
			
		
			$allowedExts = array("gif", "jpeg", "jpg", "png");
			
		
			error_reporting(E_ERROR | E_PARSE);
			$extension = end(explode(".", $_FILES["image"]["name"]));
			
			if(!empty($menu_image)){
				if(!(($image_type == "image/gif") || 
					($image_type == "image/jpeg") || 
					($image_type == "image/jpg") || 
					($image_type == "image/x-png") ||
					($image_type == "image/png") || 
					($image_type == "image/pjpeg")) &&
					!(in_array($extension, $allowedExts))){
					$error['image'] = " <span class='label label-danger'>¡El tipo de imagen debe ser jpg, jpeg, gif o png!</span>";
				}
			}
				
			if(!empty($name) && !empty($subtitle) && empty($error['image'])){
					
				if(!empty($menu_image)){
					
					
					$string = '0123456789';
					$file = preg_replace("/\s+/", "_", $_FILES['image']['name']);
					
					$image = $function->get_random_string($string, 4)."-".date("Y-m-d").".".$extension;
				
				
					$delete = unlink("$previous_subcategory_image");
					
				
					$upload = move_uploaded_file($_FILES['image']['tmp_name'], 'upload/images/'.$image);
	  				$upload_image = 'upload/images/'.$image;
					$sql_query = "UPDATE subcategory 
							SET category_id='".$category."', name = '".$name."', slug = '".$slug."',  subtitle = '".$subtitle."',image = '".$upload_image."'
							WHERE id =".$ID;
					
						$db->sql($sql_query);
				
						$update_result = $db->getResult();
						if(!empty($update_result)){
							$update_result=0;
						}
						else{
							$update_result=1;
						}
			
				}else{
					
					$sql_query = "UPDATE subcategory 
							SET category_id='".$category."', name = '".$name."', slug = '".$slug."', subtitle = '".$subtitle."', image = '".$previous_subcategory_image."'
							WHERE id = '".$ID."'";
						// Execute query
							$db->sql($sql_query);

						// store result 
						$update_result = $db->getResult();
						if(!empty($update_result)){
							$update_result=0;
						}
						else{
							$update_result=1;
						}
					
				}
				
				if($update_result==1){
					$error['update_subcategory'] = " <section class='content-header'>
												<span class='label label-success'>Subcategoría actualizada con éxito</span>
												<h4><small><a  href='subcategorias.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Volver a las subcategorías</a></small></h4>
												
												</section>";
				}else{
					$error['update_subcategory'] = " <span class='label label-danger'>Subcategoría de actualización fallida</span>";
				}
			}
		}else{
			$error['check_permission'] = " <section class='content-header'>
												<span class='label label-danger'>No tienes permiso para actualizar la subcategoría</span>
												
												
												</section>";
		} } else {
			$error['update_subcategory'] = " <section class='content-header'>
												<span class='label label-danger'>No estás autorizado para editar esta subcategoría</span>
												<h4><small><a  href='subcategorias.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Volver a las subcategorías</a></small></h4>
												
												</section>";

		}
		}
		
		$data = array();
		
		$sql_query = "SELECT * 
				FROM subcategory 
				WHERE id =".$ID;
			$db->sql($sql_query);
	
			$res_query=$db->getResult();
		if(isset($_POST['btnCancel'])){?>
			<script>
			window.location.href = "subcategorias.php";
		</script>
		<?php } ?>
	<?php if($exists==1 || $_SESSION['role']=='super admin'){?>
	<section class="content-header">
          <h1>
		  Editar subcategoría <small><a  href='subcategorias.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Volver a las subcategorías</a></small></h1>
            <small><?php echo isset($error['update_subcategory']) ? $error['update_subcategory'] : '';?></small>
          <ol class="breadcrumb">
            <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
          </ol>
        </section>
        
	<section class="content">
         
		 
          <div class="row">
		  <div class="col-md-6">
		  	<?php if($permissions['subcategories']['update']==0) { ?>
        	<div class="alert alert-danger topmargin-sm">No tienes permiso para actualizar la subcategoría.</div>
        	<?php } ?>
           
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Editar subcategoría</h3>
                </div>
          
                <form  method="post"
			enctype="multipart/form-data">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="exampleInputEmail1">categoria</label><?php echo isset($error['name']) ? $error['name'] : '';?>
                      <?php 
                      // $sql="SELECT * FROM category WHERE  id=".$res_query[0]['category_id'];
                      $db->select("category",'id,name');
                      $res = $db->getResult();
                      // print_r($res);
                      ?>
                      <select class="form-control" id="category" name="category">
                          <?php foreach($res as $row){
                            echo "<option value=".$row['id'];
                            if($row['id']==$res_query[0]['category_id'])
                            {
                                echo " selected";
                            }
                            echo ">".$row['name']."</option>";
                           } ?>
                      </select>
                      <?php 
                      	$db->select('subcategory','*',null,'id='.$ID);
                      	$res=$db->getResult();
                      ?>
                      
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail1">Nombre de subcategoría</label><?php echo isset($error['name']) ? $error['name'] : '';?>
                      <input type="text" class="form-control"  name="name" value="<?php echo $res[0]['name']; ?>">
                    </div>
					<div class="form-group">
                      <label for="exampleInputEmail1">Subcategoría Subtítulo</label><?php echo isset($error['subtitle']) ? $error['subtitle'] : '';?>
                      <input type="text" class="form-control"  name="subtitle" value="<?php echo $res[0]['subtitle']; ?>">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputFile">Image&nbsp;&nbsp;&nbsp;*Elija una imagen cuadrada de más de 350 px * 350 px y más pequeña que 550 px * 550 px.</label><?php echo isset($error['image']) ? $error['image'] : '';?>
                      <input type="file" name="image" id="image" title="Elija una imagen cuadrada de más de 350 px * 350 px y más pequeña que 550 px * 550 px." value="<img src='<?php echo $res[0]['image']; ?>'/>">
                      <p class="help-block"><img src="<?php echo $res[0]['image']; ?>" width="280" height="190"/></p>
                    </div>
                  </div><!-- /.box-body -->

                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary" name="btnEdit">Actualizar</button>
					<button type="submit" class="btn btn-danger" name="btnCancel">Cancelar</button>
                  </div>
                </form>
              </div><!-- /.box -->
              <?php echo isset($error['check_permission']) ? $error['check_permission'] : '';?>	
			 </div>
		  </div>
	</section>
	<?php } else { ?>
    <div class="alert alert-danger topmargin-sm">No tiene autorización para actualizar esta subcategoría.</div>
    <?php } ?>

	<div class="separator"> </div>
<?php $db->disconnect(); ?>