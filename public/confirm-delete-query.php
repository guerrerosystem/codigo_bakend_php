<div id="content" class="container col-md-12">
	<?php 
		
		if(isset($_POST['btnDelete'])){
			if(isset($_GET['id'])){
				$ID = $_GET['id'];
			}else{
				$ID = "";
			}
			
			$sql_query = "DELETE FROM faq 
					WHERE id =".$ID;
				
				// Execute query
				$db->sql($sql_query);
				// store result 
				$delete_query_result=$db->getResult();
				if(!empty($delete_query_result)){
					$delete_query_result=0;
				}
					$delete_query_result=1;
			
		
			if($delete_query_result==1){
				header("location: preguntas.php");
			}
		}		
		
		if(isset($_POST['btnNo'])){
			header("location: preguntas.php");
		}
		if(isset($_POST['btncancel'])){
			header("location: preguntas.php");
		}
		
	?>
	<?php if($permissions['faqs']['delete']==1){?>
	<h1>Confirmar acción</h1>
	<hr />
	<form method="post">
		<p>¿Estás seguro de que quieres eliminar esta consulta?</p>
		<input type="submit" class="btn btn-primary" value="Eliminar" name="btnDelete"/>
		<input type="submit" class="btn btn-danger" value="Cancelar" name="btnNo"/>
	</form>
	<div class="separator"> </div>
	<?php } else { ?>
		<div class="alert alert-danger topmargin-sm" style="margin-top: 20px;">No tienes permiso para eliminar preguntas frecuentes.</div>
		<form method="post">
		<input type="submit" class="btn btn-danger" value="Back" name="btncancel"/>
	</form>
	<?php } ?>
</div>
			
<?php $db->disconnect(); ?>