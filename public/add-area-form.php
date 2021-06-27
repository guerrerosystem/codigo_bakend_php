<?php 
	include_once('includes/functions.php'); 
?>
	<?php 
		$sql_query = "SELECT id, name 
			FROM city where name!='Choose Your City'
			ORDER BY id ASC";
			
		
			$db->sql($sql_query);
		
			$res_city=$db->getResult();	
		
		
			
		if(isset($_POST['btnAdd'])){
			if($permissions['locations']['create']==1){
			$area_name = $_POST['area_name'];
			$city_ID = $_POST['city_ID'];
			$sql_query = "SELECT * 
			FROM area WHERE city_id=".$city_ID;	
			
			$db->sql($sql_query);
		
			$res_area=$db->getResult();
				$TOTAL=$db->numRows($res_area);
			
			$error = array();
			
			if(empty($area_name)){
				$error['area_name'] = " <span class='label label-danger'>obligatorio!</span>";
			}
				
			if(empty($city_ID)){
				$error['city_ID'] = " <span class='label label-danger'>obligatorio!</span>";
			}
				if($TOTAL==0)
				{
				
				if(!empty($area_name) && !empty($city_ID) ){
			$area_name = $_POST['area_name'];
			$city_ID = $_POST['city_ID'];	
				
				$sql_query = "INSERT INTO area (name, city_id)
						VALUES('$area_name', '$city_ID')";
				
					$db->sql($sql_query);
			
					$result = $db->getResult();
					if(!empty($result)){
						$result=0;
					}else{
						$result=1;
					}
				
				
				if($result==1){
					$error['add_area'] = "<section class='content-header'>
												<span class='label label-success'>Área añadida con éxito</span>
												<h4><small><a  href='distritos.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Ver distritos</a></small></h4>
												
												</section>";
				}else {
					$error['add_area'] = " <span class='label label-danger'>Failed</span>";
				}
			}
				}
			else
			{
			if(!empty($area_name) && !empty($city_ID) ){
			$area_name = $_POST['area_name'];
			$city_ID = $_POST['city_ID'];	
			
				$sql_query = "INSERT INTO area (name, city_id)
						VALUES('$area_name', '$city_ID')";
				
					$db->sql($sql_query);
					
					$result = $db->getResult();
					if(!empty($result)){
						$result=0;
					}else{
						$result=1;
					}
				
				if($result==1){
					$error['add_area'] = "<section class='content-header'>
												<span class='label label-success'>Área añadida con éxito</span>
												<h4><small><a  href='distritos.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Ver distritos</a></small></h4>
												
												</section>";
				}else {
					$error['add_area'] = " <span class='label label-danger'>Failed</span>";
				}
			}
			}
		}else{
			$error['add_area'] = "<section class='content-header'>
												<span class='label label-danger'>No tienes permiso para crear área</span>
												</section>";

			}
		}
	?>
	<section class="content-header">
          <h1>Agregar Distrito <small><a  href='distritos.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Regrtesar</a></small></h1>
		  
			<?php echo isset($error['add_area']) ? $error['add_area'] : '';?>
			<ol class="breadcrumb">
            <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
          </ol>
		<hr />
        </section>
	<section class="content">

<div class="row">
		  <div class="col-md-6">
		  	<?php if($permissions['locations']['create']==0){?>
		  		<div class="alert alert-danger">No tienes permiso para crear área</div>
		  	<?php } ?>
           
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Agregar distrito</h3>
                </div>
                <form  method="post" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class="form-group">
						<label for="exampleInputEmail1">Ciudad :</label><?php echo isset($error['city_ID']) ? $error['city_ID'] : '';?>
						<select name="city_ID" class="form-control" required>
						<option default>Selecciona tu ciudad</option>

						<?php 
						if($permissions['locations']['read']==1){
							foreach($res_city as $row){ ?>
							<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
							<?php } }?>
						
						
						</select>
						<br/>
					</div>
					<div class="form-group">
                      <label for="exampleInputEmail1">Nombre de disitrito</label><?php echo isset($error['area_name']) ? $error['area_name'] : '';?>
                      <input type="text" class="form-control"  name="area_name" required/>
                    </div>
					
                  </div>

                  <div class="box-footer">
                    <input type="submit" class="btn-primary btn" value="Agregar" name="btnAdd"/>&nbsp;
					<input type="reset" class="btn-danger btn" value="Limpiar"/>
                  </div>
                </form>
              </div>
			 </div>
		  </div>
	</section>

	<div class="separator"> </div>
	
<?php $db->disconnect(); ?>