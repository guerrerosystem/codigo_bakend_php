<div id="content" class="container col-md-12">
	<?php 
		if(isset($_POST['btnDelete'])){
			if(isset($_GET['id'])){
				$ID = $_GET['id'];
			}else{
				$ID = "";
			}
			
			// delete data from pemesanan table
			$sql_query = "DELETE FROM orders 
					WHERE ID =".$ID;	
				// Bind your variables to replace the ?s
				// Execute query
				$db->sql($sql_query);
				// store result 
				$delete_result = $db->getResult();
				if(!empty($delete_result)){
					$delete_result=0;
				}else{
					$delete_result=1;
				}
			
			// if delete data success back to pemesanan page
			if($delete_result==1){
				header("location: pedidos.php");
			}
		}
		if(isset($_POST['btnNo'])){
			header("location: pedidos.php");
		}


	?>
	<h1>Confirmar acción</h1>
	<hr />
	<form method="post">
		<p>¿Seguro que quieres eliminar este pedido?</p>
		<input type="submit" class="btn btn-primary" value="Eliminar" name="btnDelete"/>
		<input type="submit" class="btn btn-danger" value="Cancelar" name="btnNo"/>
	</form>
	<div class="separator"> </div>
</div>
			
<?php $db->disconnect(); ?>