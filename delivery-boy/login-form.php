<?php
        if(isset($_SESSION['delivery_boy_id']) && isset($_SESSION['name'])){
        header("location:home.php");
    }
    
	
	/* include($_SERVER['DOCUMENT_ROOT'].'/admin/includes/connect_database.php'); 
	include($_SERVER['DOCUMENT_ROOT'].'/admin/includes/variables.php');  */
	//include('./includes/variables.php'); 
	
	// start session
	// if user click Login button
	if(isset($_POST['btnLogin'])){
	
		// get username and password
		$mobile = $_POST['mobile'];
		$password = $_POST['password'];
		
		// set time for session timeout
		$currentTime = time() + 25200;
		$expired = 3600;
		
		// create array variable to handle error
		$error = array();
		
		// check whether $username is empty or not
		if(empty($mobile)){
			$error['mobile'] = " <span class='label label-danger'>¡El Telefono  es obligatorio</span>";
		}
		
		// check whether $password is empty or not
		if(empty($password)){
			$error['password'] = " <span class='label label-danger'>La contraseña debe ser completada!</span>";
		}
		
		// if username and password is not empty, check in database
		if(!empty($mobile) && !empty($password)){
						
			// change username to lowercase
			
			//encript password to sha256
		    $password = md5($password);
			
			// get data from user table
			$sql_query = "SELECT * 
				FROM delivery_boys 
				WHERE mobile = '".$mobile."' AND password = '".$password."'";
				// Bind your variables to replace the ?s
				// Execute query
				$db->sql($sql_query);
				/* store result */
				$res=$db->getResult();
				$num = $db->numRows($res);
				// Close statement object
				if($num == 1){
						$sql = "SELECT status FROM delivery_boys WHERE mobile=".$mobile;
						$db->sql($sql);
						$result=$db->getResult();
						//$num_rows = $db->numRows($result);
						if($result[0]['status'] == 0){
							// $error['failed_status'] = "<span class='label label-danger'>Account is not active!</span>";
							$error['failed_status'] = "<span class='label label-danger'>Parece que su cuenta no está activa, ¡póngase en contacto con el administrador para obtener más información!</span>";
					}else{
						$_SESSION['name'] = $res[0]['name'];
						$_SESSION['delivery_boy_id'] = $res[0]['id'];
						$_SESSION['timeout'] = $currentTime + $expired;
						header("location: home.php");
					}
					
				}else{
					$error['failed'] = "<span class='label label-danger'>Telefono o contraseña no válidos!</span>";
				}
			
			
		}	
	}
	?>
		    <div class="col-md-4 col-md-offset-4 " style="margin-top:150px;">
			<!-- general form elements -->
				<div class='row'>
				<div class="col-md-12 text-center">
					<img src="<?='../dist/img/'.$logo;?>" height="110">
					<h3>repartidor</h3>
				</div>
				<div class="box box-info col-md-12">
                <div class="box-header with-border">
                  <h3 class="box-title">repartidor Login</h3>
				  
                </div><!-- /.box-header -->
                <!-- form start -->
                <form  method="post" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Telefono :</label><?php echo isset($error['mobile']) ? $error['mobile'] : '';?>
				            <input type="text" name="mobile" class="form-control" >
					</div>
					  <div class="form-group">
					  <label for="exampleInputEmail1">Contraseña:</label><?php echo isset($error['password']) ? $error['password'] : '';?>
				            <input type="password" class="form-control" name="password" >
					   </div>
					   <center><?php echo isset($error['failed']) ? $error['failed'] : '';?></center>
					   <center><?php echo isset($error['failed_status']) ? $error['failed_status'] : '';?></center>
                  <div class="box-footer">
                    <button type="submit" name="btnLogin" class="btn btn-info pull-right">Login</button><br><br>
                  </div>
                </form>
              </div><!-- /.box -->
			 </div>
			 </div>
			</div>
