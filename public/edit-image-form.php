<?php
	include_once('includes/functions.php'); 
?>
	<?php 
	
		if(isset($_GET['id'])){
			$ID = $_GET['id'];
		}else{
			$ID = "";
		}
		
			
		$sql_query = "SELECT image FROM tbl_image WHERE id =".$ID;	
			
			$db->sql($sql_query);
		
			$res=$db->getResult();
		if(isset($_POST['btnEdit'])){
			
			$image = $_FILES['image']['name'];
			$image_error = $_FILES['image']['error'];
			$image_type = $_FILES['image']['type'];
				
			
			$error = array();
			
			
		
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
			
					
			if(empty($error['image'])){
				
				if(!empty($image)){
					
					
					$string = '0123456789';
					$file = preg_replace("/\s+/", "_", $_FILES['image']['name']);
					$function = new functions;
					$image = $function->get_random_string($string, 4)."-".date("Y-m-d").".".$extension;
				
				
					$delete = unlink($res[0]['image']);
					
				
					$upload = move_uploaded_file($_FILES['image']['tmp_name'], 'upload/notification/'.$image);
	  
					
					$sql_query = "UPDATE tbl_image
							SET image = '".$upload_image."'
							WHERE id =".$ID;
					
					$upload_image = 'upload/notification/'.$image;
						$db->sql($sql_query);
						
						$update_result = $db->getResult();
					
					
				}
				else
				{
				
					$string = '0123456789';
					$file = preg_replace("/\s+/", "_", $_FILES['image']['name']);
					$function = new functions;
					$image = $function->get_random_string($string, 4)."-".date("Y-m-d").".".$extension;
				
				
					$delete = unlink($res[0]['image']);
					
				
					$upload = move_uploaded_file($_FILES['image']['tmp_name'], 'upload/notification/'.$image);
	  
					
					$sql_query = "UPDATE tbl_image
							SET image = '".$upload_image."'
							WHERE id =".$ID;
					
					$upload_image = 'upload/notification/'.$image;
					
						
						$db->sql($sql_query);
						
						$update_result = $db->getResult();
						if(!empty($update_result)){
							$update_result =0;
						}else{
							$update_result =1;
						}
						
					
				}
					
					
				
				if($update_result==1){
					$error['update_data'] = "<section class='content-header'>
												<span class='label label-success'>Imagen actualizada con éxito</span>
												<h4><small><a  href='notification.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Volver a la notificación</a></small></h4>
												</section>";
				}else{
					$error['update_data'] = " <span class='label label-danger'>actualizacion fallida</span>";
				}
			}
			
		}
		
		
		$data = array();
			
		$sql_query = "SELECT * FROM tbl_image WHERE id =".$ID;
			
			$db->sql($sql_query);
			
		
			$res=$db->getResult();
		
		
			
	?>
	<section class="content-header">
          <h1>
            Editar Imagen</h1>
            <small><?php echo isset($error['update_data']) ? $error['update_data'] : '';?></small>
          <ol class="breadcrumb">
            <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
          </ol>
        </section>
	<section class="content">
         
          <div class="row">
		  <div class="col-md-6">
            
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Editar Imagen</h3>
                </div>
                <form  method="post"
			enctype="multipart/form-data">
                  <div class="box-body">
                    
					<div class="form-group">
                      <label for="exampleInputFile">Imagen</label><?php echo isset($error['image']) ? $error['image'] : '';?>
		<input type="file" name="image" id="image"/><br />
		<img src="<?php echo $res[0]['image']; ?>" width="210" height="160"/>
                    </div>
					
                  </div>

                  <div class="box-footer">
                    <input type="submit" class="btn-primary btn" value="Actualizar" name="btnEdit" />
                  </div>
                </form>
              </div>
			 </div>
		  </div>
	</section>

	<div class="separator"> </div>
<?php 
	
	$db->disconnect(); ?>