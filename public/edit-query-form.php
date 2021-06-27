<?php
 
	include_once('includes/functions.php');
?>
	<?php 
	
		if(isset($_GET['id'])){
			$ID = $_GET['id'];
		}else{
			$ID = "";
		}
		
		$faq_data = array();
			
		$sql_query = "SELECT id, question, answer, status 
				FROM faq
				ORDER BY id ASC";
			
			$db->sql($sql_query);
			
			$res=$db->getResult();
			
		
		if(isset($_POST['btnEdit'])){
			if($permissions['faqs']['update']==1){
			
			$question = $_POST['question'];
			$answer = $_POST['answer'];
			$status = $_POST['status'];
			
			$error = array();
			
			if(empty($question)){
				$error['question'] = " <span class='label label-danger'>Obligaotrio!</span>";
			}
			if(empty($answer)){
				$error['answer'] = " <span class='label label-danger'>Obligaotrio!</span>";
			}
			if(empty($status)){
				$error['status'] = " <span class='label label-danger'>Obligaotrio!</span>";
			}
			
					
			if(!empty($question) && !empty($answer) && !empty($status)){
				
				
					
					$function = new functions;
					
					$sql_query = "UPDATE faq 
							SET question = '".$question."' , answer = '".$answer."' , status = '".$status."' WHERE id =".$ID;
					
						
						$db->sql($sql_query);
						
						$update_result = $db->getResult();

						if(!empty($update_result)){
							$update_result =0;
						}else{
							$update_result =1;
						}
	
				
				if($update_result==1){
					$error['update_data'] = "<section class='content-header'>
												<span class='label label-success'>Consulta actualizada correctamente</span>
												<h4><small><a  href='preguntas.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Ver preguntas</a></small></h4>
												
												</section>";
				}else{
					$error['update_data'] = " <span class='label label-danger'>error actualizacion</span>";
				}
			
			
		}
		}else{
		$error['update_data'] = "<section class='content-header'>
												<span class='label label-danger'>No tienes permiso para editar preguntas frecuentes</span>
												
												</section>";

	}
	}
		

		$data = array();
			
		$sql_query = "SELECT * FROM faq WHERE id =".$ID;	
		
			$db->sql($sql_query);
		
			$res=$db->getResult();
		
		
			
	?>
	<section class="content-header">
          <h1>
		  Editar preguntas frecuentes</h1>
            <small><?php echo isset($error['update_data']) ? $error['update_data'] : '';?></small>
			 <ol class="breadcrumb">
            <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
          </ol>
        </section>
	<section class="content">
       
		 
          <div class="row">
		  <div class="col-md-6">
		  	<?php if($permissions['faqs']['update']==0) { ?>
		  		<div class="alert alert-danger topmargin-sm">No tienes permiso para editar preguntas frecuentes</div>
		  	<?php } ?>
              
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Editar preguntas frecuentes</h3>
                </div><!-- /.box-header -->
               
                <form  method="post"
			enctype="multipart/form-data">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Pregunta</label><?php echo isset($error['question']) ? $error['question'] : '';?>
			<input type="text" name="question" class="form-control" value="<?php echo $res[0]['question']; ?>"/>
					</div>
					  <div class="form-group">
					  <label for="exampleInputEmail1">Responder :</label><?php echo isset($error['answer']) ? $error['answer'] : '';?>
		<input type="text" name="answer" class="form-control" value="<?php echo $res[0]['answer'];?>"/>
					   </div>
					   <div class="form-group">
					  <label for="exampleInputEmail1">Status :</label><?php echo isset($error['status']) ? $error['status'] : '';?>
					  <select name="status" class="form-control">	
						<?php if($res[0]['status'] == 1){ ?>
							<option value="1" selected="selected">Pendiente</option>
							<option value="2" >Contestado</option>
						<?php }else{ ?>
							<option value="1" >Pendiente</option>
							<option value="2" selected="selected">Contestado</option>
						<?php } ?>
					</select>
					   </div>
                  <div class="box-footer">
                    <input type="submit" class="btn-primary btn" value="Actualizar" name="btnEdit" />
                  </div>
                </form>
              </div>
			 </div>
		  </div>
	</section>

	<div class="separator"> </div>
<?php 
	$db->disconnect(); ?>