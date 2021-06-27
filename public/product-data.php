<?php 
if(isset($_GET['id'])){
	$ID = $_GET['id'];
	}else{
	$ID = "";
	}

	$data = array();
	$sql_query="SELECT *,p.id as  product_id,(SELECT name FROM subcategory s WHERE s.id=p.subcategory_id) as subcategory_name,(SELECT short_code FROM unit u where u.id=v.measurement_unit_id) as measurement_unit_name  FROM products p join product_variant v on p.id=v.product_id where v.id=".$ID;
	$db->sql($sql_query);
	$res = $db->getResult();
	foreach($res as $row)
		$data = $row;
	?>
  <?php
    if($permissions['products']['read']==1){
  ?>
<section class="content-header">
	<h1>Productos <small><?php echo $data['name']; ?></small></h1>
	<ol class="breadcrumb">
		<li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
	</ol>
</section>
<section class="content">
          <div class="row">
            <div class="col-md-6">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Detalle del producto</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table class="table table-bordered">
                    <tr>
                      <th style="width: 10px">ID</th>
                      <td><?php echo $data['product_id']; ?></td>
                    </tr>
					<tr>
                      <th style="width: 10px">Nombre</th>
                      <td><?php echo $data['name']; ?></td>
                    </tr>
					<tr>
                      <th style="width: 10px">Estado</th>
                      <td><?php echo $data['serve_for']; ?></td>
                    </tr>
					<tr>
                      <th style="width: 10px">Medicion (kg, ltr, gm)</th>
                      <td><?php echo $data['measurement']." ".$data['measurement_unit_name']; ?></td>
                    </tr>
					<tr>
                      <th style="width: 10px">Precio(<?=$settings['currency']?>)</th>
                      <td><?php echo $data['price']; ?></td>
                    </tr>
					<tr>
                      <th style="width: 10px">Precio descontado(<?=$settings['currency']?>)</th>
                      <td><?php echo $data['discounted_price']; ?></td>
                    </tr>
					<tr>
                      <th style="width: 10px">Subcategoría</th>
                      <td><?php echo $data['subcategory_name']; ?></td>
                    </tr>
					<tr>
                      <th style="width: 10px">Imagen principal</th>
                      <td><img src="<?php echo $data['image']; ?>" width="200" height="150"/></td>
                    </tr><tr>
						<th style="width: 10px">Otras imagenes</th>
						<td><?php $other_images = json_decode($data['other_images']);
						if(!empty($other_images)){
						foreach($other_images as $image){?>
							<img src="<?=$image;?>" height="150"/>
						<?php }}else{
							echo "<h4>No se encontraron otras imágenes</h4>";
						}?>
						</td>
                    </tr>
					<tr>
                      <th style="width: 10px">descripción</th>
                      <td><?php echo $data['description']; ?></td>
                    </tr>
                  </table>
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                  <a href="editar-producto.php?id=<?php echo $data['product_id']; ?>"><button class="btn btn-primary">Editar</button></a>
			<a href="delete-product.php?id=<?php echo $data['product_id']; ?>"><button class="btn btn-danger">Eliminar</button></a>
                </div>
              </div><!-- /.box -->
			  </div>
			  </div>
	</section>
  <?php } else { ?>
<section class="content-header">
  <h1>Productos <small><a  href='home.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Home</a></small></h1>
  <ol class="breadcrumb">
    <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
  </ol>
</section>
  <div class="alert alert-danger topmargin-sm">No tiene permiso para ver el producto..</div>

<?php }?>			
<?php $db->disconnect(); ?>