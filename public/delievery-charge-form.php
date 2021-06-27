<?php
include_once('includes/functions.php');
if (isset($_POST['btnChange'])) {
    $charge = $_POST['charge'];
    $charge1 = $_POST['charge1'];
  
    $error  = array();
    if (empty($charge)) {
        $tax = 0;
    } else if (!is_numeric($charge)) {
        $error['charge'] = "*El cargo debe ser numérico..";
    }
    if (empty($charge1)) {
        $tax = 0;
    } else if (!is_numeric($charge1)) {
        $error['charge'] = "*El cargo debe ser numérico.";
    }
    if (is_numeric($charge)) {
      
        $sql_query = "UPDATE settings SET Value = ".$charge."  WHERE Variable = 'Delievery Charge'";
       
            $db->sql($sql_query);
          
            $update_result = $db->getResult();
        
    }
    if (is_numeric($charge1)) {
   
        $sql_query = "UPDATE settings SET Value = ".$charge1."  WHERE Variable = 'Delievery Charge 1'";
       
            $db->sql($sql_query);
        
            $update_result = $db->getResult();
            if(!empty($update_result)){
                $update_result=0;
            }else{
                $update_result=1;
            }
    }
   
    if ($update_result==1) {
        $error['update_setting'] = " <h4><div class='alert alert-success'>
		* La configuración se actualizó correctamente</div></h4>";
    } else {
        $error['update_setting'] = "*Error al actualizar los datos de configuración";
    }
}

$sql = "select Value from `settings` where id in (3,4)";
$db->sql($sql);
$res = $db->getResult();
$previous_charge = $res[0]['Value'];
$previous_charge1 = $res[1]['Value'];


?>
<section class="content-header">
	<h1>Comisiones de envío</h1>
	<?php echo isset($error['update_setting']) ? $error['update_setting'] : '';?>
	<ol class="breadcrumb">
		<li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
	</ol>
	<hr/>
</section>
<section class="content">
	<div class="row">
		<div class="col-md-6">
	
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Comiciones de envío</h3>
				</div><!-- /.box-header -->
				<!-- form start -->
				<form  method="post" enctype="multipart/form-data">
					<div class="box-body">
						<div class="form-group">
							<label for="exampleInputEmail1">Comiciones de envío 1:</label><?php echo isset($error['charge']) ? $error['charge'] : '';?>
							<input type="text" class="form-control" name="charge" value="<?php echo $previous_charge; ?>" />
							<label for="exampleInputEmail1">Comiciones de envío 2:</label><?php echo isset($error['charge']) ? $error['charge'] : '';?>
							<input type="text" class="form-control" name="charge1" value="<?php echo $previous_charge1; ?>" />
						</div>
					</div>
					<div class="box-footer">
						<input type="submit" class="btn-primary btn" value="Actualizar" name="btnChange"/>
					</div>
				</form>
			</div><!-- /.box -->
		</div>
	</div>
</section>
<div class="separator"> </div>
<?php $db->disconnect();?>