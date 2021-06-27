<?php
	include_once('includes/functions.php'); 
?>
	<?php 
		if(isset($_GET['id'])){
			$ID = $_GET['id'];
		}else{
			$ID = "";
		}
		
	
		$category_data = array();
		
			
		if(isset($_POST['btnEdit'])){
			if($permissions['locations']['update']==1){
		    
			$city_name = $_POST['city_name'];
		
			$error = array();
				
			if(empty($city_name)){
				$error['city_name'] = " <span class='label label-danger'>obligatorio!</span>";
			}
				
			if(!empty($city_name) ){
					
					$sql_query = "UPDATE city 
							SET name = '".$city_name."' 
							WHERE id =".$ID;
					
						
						$db->sql($sql_query);
					
						$update_result = $db->getResult();
						if(!empty($update_result)){
							$update_result =0;
						}else{
							$update_result =1;
						}
						
				}
				
				// check update result
				if($update_result==1){
					$error['update_city'] = " <section class='content-header'>
												<span class='label label-success'>Ciudad actualizada con éxito</span>
												<h4><small><a  href='ciudad.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;ver ciudades</a></small></h4>
												
												</section>";
				}else{
					$error['update_city'] = " <span class='label label-danger'>Ciudad de actualización fallida</span>";
				}
				
			}
			else{
				$error['update_city'] = " <span class='label label-danger'>No tienes permiso para actualizar la ciudad</span>";
			}

				
			}
		
				
			
		// create array variable to store previous data
		$data = array();
		
		$sql_query = "SELECT * 
				FROM city
				WHERE id =".$ID;	
			$db->sql($sql_query);
			// store result 
			
			$res=$db->getResult();
		

		if(isset($_POST['btnCancel'])) { ?>
			<script>
			window.location.href = "ciudad.php";
		</script>
		<?php }; ?>
	<section class="content-header">
          <h1>
            Editar ciudad</h1>
            <small><?php echo isset($error['update_city']) ? $error['update_city'] : '';?></small>
          <ol class="breadcrumb">
            <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
          </ol>
        </section>
	<section class="content">
          <!-- Main row -->
		 
          <div class="row">
		  <div class="col-md-6">
		  	<?php if($permissions['locations']['update']==0) { ?>
		  		<div class="alert alert-danger">No tienes permiso para actualizar la ciudad</div>
		  	<?php } ?>
              <!-- general form elements -->
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Editar Ciudad</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form  method="post"
			enctype="multipart/form-data">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Nombre ciudad</label><?php echo isset($error['city_name']) ? $error['city_name'] : '';?>
                      <input type="text" class="form-control"  name="city_name" value="<?php echo $res[0]['name']; ?>">
                    </div>
                  </div><!-- /.box-body -->

                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary" name="btnEdit">Actualizar</button>
					<button type="submit" class="btn btn-danger" name="btnCancel">Cancelar</button>
                  </div>
                </form>
              </div><!-- /.box -->
			 </div>
		  </div>
	</section>

	<div class="separator"> </div>
	
<?php $db->disconnect(); ?>