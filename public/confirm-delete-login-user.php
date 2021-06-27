<div id="content" class="container col-md-12">
	<?php 
		if(isset($_POST['btnDelete'])){
			if(isset($_GET['id'])){
				$ID = $_GET['id'];
			}else{
				$ID = "";
			}
			
		
			$sql_query = "DELETE FROM tbl_phone 
					WHERE Number_ID =".$ID;
			
				
			
				$db->sql($sql_query);
				
				$delete_result = $db->getResult();
				if(!empty($delete_result)){
					$delete_result=0;
				}else{
					$delete_result=1;
				}
			
		
			if($delete_result==1){
				header("location: loginvarification.php");
			}
		}

		if(isset($_POST['btnNo'])){
			header("location: loginvarification.php");
		}


	?>
	<h1>Confirmar acción</h1>
	<hr />
	<form method="post">
		<p>¿Seguro que quieres eliminar estos datos?</p>
		<input type="submit" class="btn btn-primary" value="Eliminar" name="btnDelete"/>
		<input type="submit" class="btn btn-danger" value="Cancelar" name="btnNo"/>
	</form>
	<div class="separator"> </div>
</div>
			
<?php $db->disconnect(); ?>