<div id="content" class="container col-md-12">
	<?php 
		
		if(isset($_POST['btnDelete'])){
		    
			if(isset($_GET['id'])){
				$ID = $_GET['id'];
			}else{
				$ID = "";
			}
			
			$sql_query = "SELECT name 
					FROM city 
					WHERE id =".$ID;
			
				$db->sql($sql_query);
				
				$res=$db->getResult();
				$sql_query = "DELETE FROM city 
					WHERE id =".$ID;
				
				$db->sql($sql_query);
			
				$delete_city_result = $db->getResult();
				if(!empty($delete_city_result)){
					$delete_city_result =0;
				}else{
					$delete_city_result =1;
				}
			
			
			$sql_query = "SELECT name 
					FROM area 
					WHERE city_id =".$ID;
				
				$db->sql($sql_query);
				// store result 
				$res=$db->getResult();
			// delete data from menu table
				$sql_query = "DELETE FROM area 
					WHERE city_id =".$ID;
				// Execute query
				$db->sql($sql_query);
				// store result 
				$delete_area_result = $db->getResult();
				if(!empty($delete_area_result)){
					$delete_area_result =0;
				}else{
					$delete_area_result =1;
				}
			
				
			// if delete data success back to reservation page
			if($delete_city_result==1 && $delete_area_result==1){
				header("location: ciudad.php");
			}
		}		
		
		if(isset($_POST['btnNo'])){
			header("location: ciudad.php");
		}
		if(isset($_POST['btncancel'])){
			header("location: ciudad.php");
		}
		
	?>
	<?php if($permissions['locations']['delete']==1){?>
	<h1>Confirmar acción</h1>
	<hr />
	<form method="post">
		<p>¿Estás seguro de que quieres eliminar esta ciudad? El área de la ciudad específica también se eliminará.</p>
		<input type="submit" class="btn btn-primary" value="Eliminar" name="btnDelete"/>
		<input type="submit" class="btn btn-danger" value="Cancelar" name="btnNo"/>
	</form>
	<div class="separator"> </div>
	<?php } else { ?>
		<div class="alert alert-danger topmargin-sm">No tienes permiso para eliminar ciudad.</div>
		<form method="post">
		<input type="submit" class="btn btn-danger" value="Regresar" name="btncancel"/>
	</form>
	<?php } ?>
</div>
			
<?php $db->disconnect(); ?>