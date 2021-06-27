
<div id="content" class="container col-md-12">
	<?php 
		
		if(isset($_POST['btnDelete'])){
		
			if(isset($_GET['id'])){
				$ID = $_GET['id'];
			}else{
				$ID = "";
			}
		
			
			$sql_query = "DELETE FROM area 
					WHERE id =".$ID;
			
				$db->sql($sql_query);
			
				$delete_result = $db->getResult();
				if(!empty($delete_result)){
					$delete_result=0;
				}else{
					$delete_result=1;
				}
		
			if($delete_result==1){
				header("location: distritos.php");
			}
		}		

		if(isset($_POST['btnNo'])){
			header("location: distritos.php");
		}
		if(isset($_POST['btncancel'])){
			header("location: distritos.php");
		}

	?>
	<?php if($permissions['locations']['delete']==1){?>
	<h1>Confirmar acción</h1>
	<hr />
	<form method="post">
		<p>¿Seguro que quieres eliminar esta área?</p>
		<input type="submit" class="btn btn-primary" value="Eliminar" name="btnDelete"/>
		<input type="submit" class="btn btn-danger" value="Cancelar" name="btnNo"/>
	</form>
	<div class="separator"> </div>
	<?php } else { ?>
		<div class="alert alert-danger topmargin-sm">No tienes permiso para eliminar el área.</div>
		<form method="post">
		<input type="submit" class="btn btn-danger" value="Regresar" name="btncancel"/>
	</form>
	<?php } ?>
</div>
			
<?php $db->disconnect(); ?>