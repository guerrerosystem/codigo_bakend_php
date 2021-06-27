<div id="content" class="container col-md-12">
	<?php if(isset($_POST['btnDelete'])){
	        if(isset($_GET['id'])){
				$ID = $_GET['id'];
			}else{
				$ID = "";
			}
			$sql_query="DELETE FROM product_variant WHERE product_id=".$ID;
			
				$db->sql($sql_query);
				
				$delete_variant_result=$db->getResult();
				if(!empty($delete_variant_result)){
					$delete_variant_result=0;
				}else{
					$delete_variant_result=1;
				}
		
			$sql_query = "SELECT image, other_images 
					FROM products 
					WHERE id =".$ID;
				// Execute query
				$db->sql($sql_query);
				// store result 
				$res=$db->getResult();
				foreach($res as $row){
					unlink($res[0]['image']);
			}
				
			// delete image file from directory
			
			// delete data from menu table
			$sql_query = "DELETE FROM products 
					WHERE id =".$ID;
				// Execute query
				$db->sql($sql_query);
				// store result 
				$delete_product_result = $db->getResult();
				if(!empty($delete_product_result)){
					$delete_product_result=0;
				}
					$delete_product_result=1;
				
			// if delete data success back to reservation page
			if($delete_product_result==1 && $delete_variant_result==1){
				header("location: productos.php");
			}
		}		

		if(isset($_POST['btnNo'])){
			header("location: productos.php");
		}
		if(isset($_POST['btncancel'])){
			header("location: productos.php");
		}

	?>
	<?php
	if($permissions['products']['delete']==1) { ?>
	<h1>Confirmar acción</h1>
	<hr />
	<form method="post">
		<p>¿Seguro que quieres eliminar este producto?</p>
		<input type="submit" class="btn btn-primary" value="Eliminar" name="btnDelete"/>
		<input type="submit" class="btn btn-danger" value="Cancelar" name="btnNo"/>
	</form>
	<div class="separator"> </div>
	<?php } else { ?>
		<div class="alert alert-danger topmargin-sm" style="margin-top: 20px;">No tienes permiso para eliminar el producto.</div>
		<form method="post">
				<input type="submit" class="btn btn-danger" value="Regresar" name="btncancel"/>
			</form>

		<?php } ?>
</div>
			
<?php $db->disconnect(); ?>