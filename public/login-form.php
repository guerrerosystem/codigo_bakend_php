<?php
	
	
	include('./includes/variables.php'); 
	
	
	if(isset($_POST['btnLogin'])){
	
	
		$username = $_POST['username'];
		$password = $_POST['password'];
	
		$currentTime = time() + 25200;
		$expired = 3600;
		
	
		$error = array();
		
		
		if(empty($username)){
			$error['username'] = "*El nombre de usuario debe completarse.";
		}
		
		
		if(empty($password)){
			$error['password'] = "*la contraseña  debe completarse.";
		}
		
		
		if(!empty($username) && !empty($password)){
			
			
			$username = strtolower($username);
			
			
		    $password = md5($password);
			
		
			$sql_query = "SELECT * 
				FROM admin 
				WHERE username = '".$username."' AND password = '".$password."'";
				
				$db->sql($sql_query);
			
				$res=$db->getResult();
				$num = $db->numRows($res);
				
				if($num == 1){
					$_SESSION['id'] = $res[0]['id'];
					$_SESSION['role'] = $res[0]['role'];
					$_SESSION['user'] = $username;
					$_SESSION['timeout'] = $currentTime + $expired;
					header("location: home.php");
				}else{
					$error['failed'] = "<span class='label label-danger'>¡Usuario o contraseña invalido!</span>";
				}
			
			
		}	
	}
	?>
	<?php $sql_logo="select value from `settings` where variable='Logo' OR variable='logo'";
	    $db->sql($sql_logo);
	    $res_logo=$db->getResult();
	    
	    ?>
		<?php echo isset($error['update_user']) ? $error['update_user'] : '';?>
		   
		
			 <div class="container-login100" style="background-image: url('log/images/uno1.jpg');">
			<div class="wrap-login100 p-t-30 p-b-50">
			<meta charset="UTF-8">
				<span class="login100-form-title p-b-41">
				<h3><?=$settings['app_name']?> Bienvenido</h3>
				<center><?php echo isset($error['failed']) ? $error['failed'] : '';?></center>
				</span>
                <div>
			
				</div>
				<form   method="post"  class="login100-form validate-form p-b-33 p-t-5">

					<div class="wrap-input100 validate-input" data-validate = "Enter username">
						<input class="input100" type="text" name="username" placeholder="Usuario" required>
						<span class="focus-input100" data-placeholder="&#xe82a;"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Enter password">
						<input class="input100" type="password" name="password" placeholder="password" required>
						<span class="focus-input100" data-placeholder="&#xe80f;"></span>
					</div>

					<div class="container-login100-form-btn m-t-32">
						<button type="submit" name="btnLogin"  class="login100-form-btn">
							Login
						</button>
					</div>

				</form>
			</div>
		</div>
			</div>
