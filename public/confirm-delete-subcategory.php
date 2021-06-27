<div id="content" class="container col-md-12">
	<?php 
		
		if(isset($_POST['btnDelete'])){
		    
			if(isset($_GET['id'])){
				$ID = $_GET['id'];
			}else{
				$ID = "";
			}
		
			$sql_query = "SELECT image
					FROM subcategory 
					WHERE id =".$ID;
				
			
				$db->sql($sql_query);
				// store result 
				$res=$db->getResult();
			
			
		
			$delete = unlink($res[0]['image']);
			
			// delete data from menu table
			$sql_query = "DELETE FROM subcategory 
					WHERE id =".$ID;	
				
				// Execute query
				$db->sql($sql_query);
				// store result 
				$delete_subcategory_result = $db->getResult();
				if(!empty($delete_subcategory_result)){
					$delete_subcategory_result=0;
				}
					$delete_subcategory_result=1;
			
			// get image file from table
			$sql_query = "SELECT image,other_images 
					FROM products 
					WHERE subcategory_id =".$ID;
				// Execute query
				$db->sql($sql_query);
				// store result 
				$res=$db->getResult();
			// delete all menu image files from directory
			foreach($res as $row){
					unlink($res[0]['image']);
			}
			
			// delete data from menu table
			$sql_query = "DELETE FROM products 
					WHERE subcategory_id =".$ID;
				// Execute query
				$db->sql($sql_query);
				// store result 
				$delete_product_result = $db->getResult();
				if(!empty($delete_product_result)){
					$delete_product_result=0;
				}
					$delete_product_result=1;
			if($delete_subcategory_result==1 && $delete_product_result=1){
				header("location: subcategorias.php");
			}
		}		
		
		if(isset($_POST['btnNo'])){
			header("location: subcategorias.php");
		}
		if(isset($_POST['btncancel'])){
			header("location: subcategorias.php");
		}

		
	?>
	<?php 
	if($permissions['subcategories']['delete']==1){?>
	<h1>Confirmar acción</h1>
	<hr />
	<form method="post">
		<p>¿Seguro que desea eliminar esta subcategoría? Todos los productos también se eliminarán.</p>
		<input type="submit" class="btn btn-primary" value="Eliminar" name="btnDelete"/>
		<input type="submit" class="btn btn-danger" value="Cancelar" name="btnNo"/>
	</form>
	<div class="separator"> </div>
	<?php } else { ?>
		<div class="alert alert-danger topmargin-sm" style="margin-top: 20px;">No tienes permiso para eliminar una subcategoría.</div>
		<form method="post">
		<input type="submit" class="btn btn-danger" value="Atras" name="btncancel"/>
	</form>
	<?php }  ?>
</div>
			
<?php $db->disconnect(); ?>