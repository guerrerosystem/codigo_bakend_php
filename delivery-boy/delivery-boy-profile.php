<?php
	
	
	session_start();
	

	$currentTime = time() + 25200;
	$expired = 3600;
	
	
	if(!isset($_SESSION['delivery_boy_id']) && !isset($_SESSION['name'])){
        header("location:index.php");
    }
	
	
	if($currentTime > $_SESSION['timeout']){
		session_destroy();
		header("location:index.php");
	}
	
	
	unset($_SESSION['timeout']);
	$_SESSION['timeout'] = $currentTime + $expired;
	
    include"header.php";?>
<html>
<head>
<title>Perfil de repartidor | <?=$settings['app_name']?> - Dashboard</title>
</head>
<body>

	<div class="content-wrapper">
	<?php $id = $_SESSION['delivery_boy_id'];
	$sql_query = "SELECT * FROM delivery_boys 
	WHERE id ='".$id."'";
	
	$data = array();			
		
		$db->sql($sql_query);
		
	$res=$db->getResult();
	$previous_password = $res[0]['password'];
?>

	<section class="content-header">
          <h1>repartidor</h1>
          <ol class="breadcrumb">
                    <li>
                        <a href="home.php"> <i class="fa fa-home"></i> Home</a>
                    </li>
                </ol>
		<?php echo isset($error['update_user']) ? $error['update_user'] : '';?>
		<hr />
        </section>
		<section class="content">
          <!-- Main row -->
		 
          <div class="row">
		  <div class="col-md-6">
              <!-- general form elements -->
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">editar perfil de repartidor</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form id='update_form' method="post" action="db-operation.php">
                	<input type='hidden' name="delivery_boy_id" id="delivery_boy_id" value='<?=$res[0]['id'];?>'/>
                    <input type='hidden' name="update_delivery_boy" id="update_delivery_boy" value='1'/>
				<div class="box-body">
                    <div class="form-group">
						<label for="">nombre :</label>
						<input type="text" class="form-control" name="update_name" id="update_name" value="<?php echo $res[0]['name']; ?>"/>
					</div>
					<div class="form-group">
						<label for="">telefono :</label>
						<input type="number" class="form-control" name="mobile" value="<?php echo $res[0]['mobile']; ?>" readonly/>
					</div>
					<div class="form-group">
                        <label class="" for="">direccion</label>
                        <textarea name="update_address" id="update_address" style=" min-width:500px; max-width:100%;min-height:100px;height:100%;width:100%;"><?=$res[0]['address'];?></textarea>
                    </div>
					<div class="form-group">
						<label for="">Contraseña anterior :</label><?php echo isset($error['old_password']) ? $error['old_password'] : '';?><small>( Déjalo en blanco sin cambios )</small>
						<input type="password" class="form-control" name="old_password" id="old_password"/>
					</div>
					<div class="form-group">
						<label for="">Nueva contraseña :</label>
						<input type="password" class="form-control" name="update_password" id="update_password"/>
					</div>
					<div class="form-group">
						<label for="">Reescriba nueva contraseña :</label>
						<input type="password" class="form-control" name="confirm_password" id="confirm_password"/>
					</div>
					<div class="box-footer">
						<input type="submit" class="btn-primary btn" value="Cambiar" id="btnChange"/>
					</div>
					<div class="form-group">
                      
	                    <div class="row"><div  class="col-md-offset-3 col-md-8" style ="display:none;" id="update_result"></div></div>
	                </div>
				</div><!-- /.box -->
				</form>
			</div>
		  </div>
	</section>
	<div class="separator"> </div>
</div><!-- /.content-wrapper -->
  </body>
</html>
<?php include"footer.php";?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script>
$('#update_form').validate({
	rules:{
		update_name:"required",
		update_address:"required",
		confirm_password:{equalTo : '#update_password'},
	}
});
</script>
  <script>
      $('#update_form').on('submit',function(e){
        e.preventDefault();
        var formData = new FormData(this);
      if( $("#update_form").validate().form() ){
            //if(confirm('Are you sure?Want to Update Delivery Boy')){
            $.ajax({
            type:'POST',
            url: $(this).attr('action'),
            data:formData,
            beforeSend:function(){$('#btnChange').html('Por favor espera..');},
            cache:false,
            contentType: false,
            processData: false,
            success:function(result){
                $('#update_result').html(result);
                $('#update_result').show().delay(6000).fadeOut();
                $('#btnChange').html('cambiar');
            }
            });
            //}
              }
        }); 
  </script>