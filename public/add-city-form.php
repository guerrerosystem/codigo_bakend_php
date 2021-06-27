<?php 
	include_once('includes/functions.php'); 
?>
	<?php 
		if(isset($_POST['btnAdd'])){
			if($permissions['locations']['create']==1){
			$city_name = $_POST['city_name'];
			
		
			$error = array();
			
			if(empty($city_name)){
				$error['city_name'] = " <span class='label label-danger'>obligatorio!</span>";
			}		
			if(!empty($city_name)){
				
		
				$sql_query = "INSERT INTO city (name)
						VALUES('$city_name')";
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
					$error['add_city'] = "<section class='content-header'>
												<span class='label label-success'>Ciudad añadida con éxito</span>
												<h4><small><a  href='ciudad.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Ver los ciudades</a></small></h4>
												
												</section>";
				}else{
					$error['add_city'] = " <span class='label label-danger'>Error al agregar ciudad</span>";
				}
			}
			}else{
			$error['add_city'] = "<section class='content-header'>
												<span class='label label-danger'>No tienes permiso para crear ciudad</span>
												
												
												</section>";

		}
			
		}

		if(isset($_POST['btnCancel'])){
			header("location:city-table.php");
		}

	?>
	<section class="content-header">
          <h1>Agregar ciudad</h1>
			<?php echo isset($error['add_city']) ? $error['add_city'] : '';?>
			<ol class="breadcrumb">
            <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
          </ol>
		<hr />
        </section>
	<section class="content">
	 <div class="row">
		  <div class="col-md-6">
		  	<?php if($permissions['locations']['create']==0){?>
		  		<div class="alert alert-danger">No tienes permiso para crear ciudad</div>
		  	<?php } ?>
              <!-- general form elements -->
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Agregar Ciudad</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form  method="post"
			enctype="multipart/form-data">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Nombre ciudad</label><?php echo isset($error['city_name']) ? $error['city_name'] : '';?>
                      <input type="text" class="form-control"  name="city_name">
                    </div>
                  </div><!-- /.box-body -->

                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary" name="btnAdd">Agregar</button>
					<input type="reset" class="btn-warning btn" value="Limpiar"/>
                  </div>
                </form>
              </div><!-- /.box -->
			 </div>
		  </div>
	</section>

	<div class="separator"> </div>
	
<?php $db->disconnect(); ?>
	