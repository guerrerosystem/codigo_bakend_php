<?php session_start();
    
    include_once ('../includes/custom-functions.php');
    include_once ('../includes/functions.php');
    $function = new custom_functions();
    
    // set time for session timeout
    $currentTime = time() + 25200;
    $expired = 3600;
    // if session not set go to login page
    if(!isset($_SESSION['delivery_boy_id']) && !isset($_SESSION['name'])){
        header("location:index.php");
    }else{
        $id = $_SESSION['delivery_boy_id'];
    }

    if ($currentTime > $_SESSION['timeout']) {
        session_destroy();
        header("location:index.php");
    }
    // destroy previous session timeout and create new one
    unset($_SESSION['timeout']);
    $_SESSION['timeout'] = $currentTime + $expired;
    
    include "header.php";?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title><?=$settings['app_name']?> - Dashboard</title>
	</head>
    <body>
       
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>Home</h1>
                <ol class="breadcrumb">
                    <li>
                        <a href="home.php"> <i class="fa fa-home"></i> Home</a>
                    </li>
                </ol>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-lg-4 col-xs-6">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3><?=$function->rows_count('orders','id','delivery_boy_id='.$id);?></h3>
                                <p>Pedidos</p>
                            </div>
                            <div class="icon"><i class="fa fa-shopping-cart"></i></div>
                            <a href="pedidos.php" class="small-box-footer">Más información<i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-xs-6">
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <h3><?= $function->get_balance($id);?></h3>
                                <p>saldo</p></p>
                            </div>
                            <div class="icon"><i class="fa fa-money"></i></div>
                            <a href="fund-transfers.php" class="small-box-footer">Mas información <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-xs-6">
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <h3><?= $function->get_bonus($id);?></h3>
                                <p>Bonos%</p></p>
                            </div>
                            <div class="icon"><i class="fa fa-cubes"></i></div>
                            <a href="fund-transfers.php" class="small-box-footer">Mas información <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    
                    
                    
                    
                    

                </div>
				<div class="row">
					<div class="col-md-12">
						<div class="box box-info">
							<div class="box-header with-border">
								<h3 class="box-title">Ultimos pedidos</h3>
								<div class="box-tools pull-right">
									<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
									<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
								</div>
				<form method="POST" id="filter_form" name="filter_form">
    
                <div class="form-group pull-right">
                    <!--<h3 class="box-title">Filter by status</h4>-->
                        <select id="filter_order" name="filter_order" placeholder="Seleccionar estado" required class="form-control" style="width: 300px;">
                        <option value = ""> Todos los pedidos </option>
                             <option value = 'recibido'> Recibido </option>
                             <option value = 'procesado'> Procesado </option>
                             <option value = 'enviado'> Enviado </option>
                             <option value = 'entregado'> Entregado </option>
                             <option value = 'cancelado'> Cancelado </option>
                         </select>
                        
                    <!-- <input type="submit" name="filter_btn" id="filter_btn" value="Filter" class="btn btn-primary btn-md"> -->
                </div>
                </form>
							</div>
							<div class="box-body">
								<div id="toolbar">
									<form method="post">
										<select class='form-control' id="category_id" name="category_id" placeholder="Seleccvionar categoria" required style="display: none;">
											<?php
												$Query="select name, id from category";
												$db->sql($Query);
                                                $result=$db->getResult();
												if($result)
												{
												?>
											<option value="">Todos los productos</option>
                                            <?php foreach($result as $row){?>
                                                 <option value='<?=$row['id']?>'><?=$row['name']?></option>
                                                <?php }} 
                                                    ?>
											
										</select>
									</form>
								</div>
								<div class="table-responsive">
									<table class="table no-margin" id='orders_table' data-toggle="table" 
										data-url="get-bootstrap-table-data.php?table=orders"
										data-page-list="[5, 10, 20, 50, 100, 200]"
										data-show-refresh="true" data-show-columns="true"
										data-side-pagination="server" data-pagination="true"
										data-search="true" data-trim-on-search="false"
										data-sort-name="id" data-sort-order="desc"
										data-toolbar="#toolbar" data-query-params="queryParams"
										>
										<thead>
											<tr>
												<th data-field="id" data-sortable='true'>O.ID</th>
												<th data-field="user_id" data-sortable='true' data-visible="false">Usuario ID</th>
												 <th data-field="qty" data-sortable='true' data-visible="false">Cantidad</th>
                                                <th data-field="name" data-sortable='true'>U.nombre</th>
												<th data-field="mobile" data-sortable='true' data-visible="true">telefono.</th>
												<th data-field="items" data-sortable='true' data-visible="false">Items</th>
												<th data-field="total" data-sortable='true' data-visible="true">Total(<?=$settings['currency']?>)</th>
												<th data-field="delivery_charge" data-sortable='true'>D.comicion</th>
												<th data-field="tax" data-sortable='false'>impuesto <?=$settings['currency']?>(%)</th>
												<th data-field="discount" data-sortable='true' data-visible="true">DSTO.<?=$settings['currency']?>(%)</th>
												<th data-field="promo_code" data-sortable='true' data-visible="false">cod promocional</th>
												<th data-field="promo_discount" data-sortable='true' data-visible="true">DSTO promocional.(<?=$settings['currency']?>)</th>
												<th data-field="wallet_balance" data-sortable='true' data-visible="true">saldo utilisado(<?=$settings['currency']?>)</th>
												<th data-field="final_total" data-sortable='true'>F.Total(<?=$settings['currency']?>)</th>
												<th data-field="deliver_by" data-sortable='true' data-visible='false'>Entregado por</th>
												<th data-field="payment_method" data-sortable='true' data-visible="true">metodo p.</th>
												<th data-field="address" data-sortable='true' data-visible="false">direccion</th>
												<th data-field="delivery_time" data-sortable='true' data-visible='false'>D.hora</th>
												<th data-field="status" data-sortable='true' data-visible='false'>Estado</th>
												<th data-field="active_status" data-sortable='true' data-visible='true'>A.Estado</th>
												<th data-field="date_added" data-sortable='true' data-visible="false">O.Fecha</th>
												<th data-field="operate">Acción</th>
                                               
												
											</tr>
										</thead>
									</table>
								</div>
							</div>
							<div class="box-footer clearfix">
								<a href="pedidos.php" class="btn btn-sm btn-default btn-flat pull-right">Ver todos los pedidos</a>
							</div>
						</div>
					</div>
				</div>
			</section>
        </div>
<script>
	$('#filter_order').on('change',function(){
    $('#orders_table').bootstrapTable('refresh');
    });
</script>
<script>
function queryParams(p){
	return {
		"filter_order": $('#filter_order').val(),
		limit:p.limit,
		sort:p.sort,
		order:p.order,
		offset:p.offset,
		search:p.search
	};
}
</script>
<?php include "footer.php";?>
  </body>
</html>