<?php
	include_once('includes/functions.php'); 
?>

    
		<?php 
	
		$function = new functions;
		
	
		$data = array();
		
		if(isset($_GET['keyword']) && !empty($_GET['keyword']) ){	
			
			$keyword = $function->sanitize($_GET['keyword']);
			
		}else{
			$keyword = "";
		}
			
		if(empty($keyword)){
			$sql_query = "SELECT count(*)
				as total_records FROM city WHERE name!='Choose Your City'
				ORDER BY id DESC";
		}else{
			$sql_query = "SELECT count(*)
				as total_records FROM city
				WHERE name LIKE '%".$keyword."%' and name!='Choose Your City'
				ORDER BY id DESC";
		}
		$db->sql($sql_query);
    	$res = $db->getResult();
    	foreach($res as $row){
       	$total_records = $row['total_records'];
    }
			
		
		if(isset($_GET['page'])){
			$page = $_GET['page'];
		}else{
			$page = 1;
		}
						
	
		$offset = 10;
						
	
		if ($page){
			$from 	= ($page * $offset) - $offset;
		}else{
			
			$from = 0;	
		}	
		
		if(empty($keyword)){
			$sql_query = "SELECT id, name
				FROM city where name!='Elige tu ciudad'
				ORDER BY id DESC LIMIT ".$from.",".$offset."";
		}else{
			$sql_query = "SELECT id, name 
				FROM city
				WHERE name LIKE '%".$keyword."%' and name!='Elige tu ciudad'
				ORDER BY id DESC LIMIT ".$from.",".$offset."";
		}
		
		
    	$db->sql($sql_query);
    
    	$res = $db->getResult();

     
        $total_records_paging = $total_records;

		
		if($permissions['locations']['read']==1){
		if($total_records_paging == 0){
	
	?>
	<section class="content-header">
	<h1>Ciudad no disponible</h1>
	<ol class="breadcrumb">
            <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
          </ol>
	<ol class="breadcrumb">
		<a href="agregar-ciudad.php">
			<button class="btn btn-block btn-default"><i class="fa fa-plus-square"></i>Agregar nueva ciudad</button>
		</a>
	</ol>
	<hr />
	<?php 
		
		}else{
			$row_number = $from + 1;
	?>
        <section class="content-header">
          <h1>
            Ciudades
            <small></small>
          </h1>
          <ol class="breadcrumb">
				<a class="btn btn-block btn-default" href="agregar-ciudad.php"><i class="fa fa-plus-square"></i> Agregar nueva ciudad</a>
          </ol>
        </section>

       
			 
        <section class="content">
      
          <div class="row">
          
				<div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Ciudades</h3>
                  <div class="box-tools">
				  <form  method="get">
                    <div class="input-group" style="width: 150px;">
					
                      <input type="text" name="keyword" class="form-control input-sm pull-right" placeholder="Buscar">
                      <div class="input-group-btn">
                        <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                      </div>
					 
                    </div>
					</form>
                  </div>
                </div>
                <div class="box-body table-responsive">
                  <table class="table table-hover">
                    <tr>
					<th> No. </th>
                  <th> Nombre </th>
                   <th> Acción </th>
                    </tr>
					<?php
					$count=1;
					
					foreach($res as $row){?>
                    <tr>
						<td><?=$count;?></td>
                      <td><?php echo $row['name'];?></td>
					  <td>
					   <a onclick="myFunction()" href="ver-ciudad-distrito.php?id=<?php echo $row['id'];?>  "><i class="fa fa-folder-open-o"></i>Ver distritos</a>
							
							<script>
							function myFunction() {
								location.reload();
							}
							</script>
					  <a href="editar-ciudad.php?id=<?php echo $row['id'];?>"><i class="fa fa-edit"></i>Editar</a>&nbsp;&nbsp;&nbsp;&nbsp;
						  <a href="eliminar-ciudad.php?id=<?php echo $row['id'];?>"><i class="fa fa-trash-o"></i>Eliminar</a>&nbsp;&nbsp;&nbsp;&nbsp;
						 
					  </td>
                    </tr>
					<?php $count++; } } } else {?>
						<div class="alert alert-danger topmargin-sm" style="margin-top: 20px;">No tienes permiso para ver ciudades</div>
					<?php } ?>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
			<div class="col-sx-12">
	<h4>
	<?php 

		$function->doPages($offset, 'ciudad.php', '', $total_records, $keyword);?>
	</h4>
	</div>
	<div class="separator"> </div>
          
          </div><!-- /.row (main row) -->

        </section><!-- /.content -->