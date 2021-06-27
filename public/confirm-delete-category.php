<div id="content" class="container col-md-12">
	<?php 
		
		if(isset($_POST['btnDelete'])){
		    
			if(isset($_GET['id'])){
				$ID = $_GET['id'];
			}else{
				$ID = "";
			}
		
			$sql_query = "SELECT image 
					FROM category 
					WHERE id =".$ID;
			
				$db->sql($sql_query);
			
				$res=$db->getResult();
			
				unlink($res[0]['image']);
			
			
			$sql_query = "DELETE FROM category 
					WHERE id =".$ID;
			
				$db->sql($sql_query);
				
				$delete_category_result = $db->getResult();
				if(!empty($delete_category_result)){
					$delete_category_result=0;
				}
				else{
					$delete_category_result=1;
				}
				
			$sql_query = "SELECT image 
					FROM subcategory 
					WHERE category_id =".$ID;
			$db->sql($sql_query);
				
				$res=$db->getResult();
		
			$delete = unlink($res[0]['image']);
				$sql_subcategory="SELECT id FROM subcategory WHERE category_id=".$ID;
				$db->sql($sql_subcategory);
				$res_subcategory=$db->getResult();
			$sql_query = "DELETE FROM subcategory 
					WHERE category_id =".$ID;
			
				$db->sql($sql_query);
			
				$delete_subcategory_result = $db->getResult();
				if(!empty($delete_subcategory_result)){
					$delete_subcategory_result=0;
				}
				else{
					$delete_subcategory_result=1;
				}
			
		
			$sql_query = "SELECT image,other_images 
					FROM products 
					WHERE subcategory_id =".$res_subcategory[0]['id'];
			
				$db->sql($sql_query);
			
				$res=$db->getResult();
		
			foreach($res as $row){
				unlink($res[0]['image']);
			}
		
			$sql_query = "DELETE FROM products 
					WHERE subcategory_id =".$res_subcategory[0]['id'];
				// Execute query
				$db->sql($sql_query);
				// store result 
				$delete_product_result = $db->getResult();

				if(!empty($delete_product_result)){
					$delete_product_result=0;
				}
				else{
					$delete_product_result=1;
				}
			if($delete_category_result==1 && $delete_subcategory_result==1 && $delete_product_result=1){
				header("location: categorias.php");
			}
		}		
		
		if(isset($_POST['btnNo'])){
			header("location: categorias.php");
		}
		if(isset($_POST['btncancel'])){
			header("location: categorias.php");
		}
		
	?>
	<h1>Confirmar acción</h1>
	<?php 
	if($permissions['categories']['delete']==1){?>
	<hr />
	<form method="post">
		<p>¿Seguro que quieres eliminar esta categoría?Todas las subcategorías y productos también serán eliminados.</p>
		<input type="submit" class="btn btn-primary" value="Eliminar" name="btnDelete"/>
		<input type="submit" class="btn btn-danger" value="Cancelar" name="btnNo"/>
	</form>
	<div class="separator"> </div>
	<?php } else { ?>
	<div class="alert alert-danger topmargin-sm">No tienes permiso para eliminar la categoría.</div>
	<form method="post">
	<input type="submit" class="btn btn-danger" value="Regresar" name="btncancel"/>
	</form>
	<?php } ?>
</div>
			
<?php $db->disconnect(); ?>