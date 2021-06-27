<div id="content" class="container col-md-12">
	<?php 
		if(isset($_POST['btnDelete'])){
			if(isset($_GET['id'])){
				$ID = $_GET['id'];
			}else{
				$ID = "";
			}
			

			$sql_query = "DELETE FROM orders 
					WHERE ID =".$ID;	
				$db->sql($sql_query);
				
				
				$delete_result = $db->getResult();
				$sql = "DELETE FROM order_items 
					WHERE order_id =".$ID;	
				$db->sql($sql);
				if(!empty($delete_result)){
					$delete_result=0;
				}else{
					$delete_result=1;
				}
			
			
			if($db->sql($sql_query)){
				header("location: pedidos.php");
			}
		}
		if(isset($_POST['btnNo'])){
			header("location: pedidos.php");
		}
		if(isset($_POST['btncancel'])){
			header("location: pedidos.php");
		}


	?>
	<?php if($permissions['orders']['delete']==1){?>
	<h1>Confirmar acción</h1>
	<hr />
	<form method="post">
		<p>¿Seguro que quieres eliminar este pedido?</p>
		<input type="submit" class="btn btn-primary" value="Eliminar" name="btnDelete"/>
		<input type="submit" class="btn btn-danger" value="Cancelar" name="btnNo"/>
	</form>
	<div class="separator"> </div>
	<?php } else { ?>
		<div class="alert alert-danger topmargin-sm">¡Lo siento! no tienes permiso para eliminar pedidos.</div>
		<form method="post">
		<input type="submit" class="btn btn-danger" value="Regresar" name="btncancel"/>
	</form>
	<?php } ?>
</div>
			
<?php $db->disconnect(); ?>