<?php
    if($permissions['orders']['read']==1) { 
?>
<section class="content-header">
    <h1>Lista de pedido</h1>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
    <hr/>
</section>
<style>
.uppercase {
  text-transform: uppercase;
}
</style>


<section class="content">
   
    
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
                <div class="form-group">
                            <label for="from" class="control-label col-md-3 col-sm-3 col-xs-12">Desde y hasta la fecha</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="date" name="date" autocomplete="off" />
                            </div>
                            <input type="hidden" id="start_date" name="start_date">
                            <input type="hidden" id="end_date" name="end_date">
                </div>
                <div class="form-group">
                    
                      <select id="filter_order" name="filter_order" placeholder="Select Status" required class="form-control" style="width: 300px;">
                         

                            <option value =""> Todos los pedidos </option>
                             <option value ='recibido'> Recibido </option>
                             <option value ='procesado'> Procesado </option>
                             <option value ='enviado'> Enviado </option>
                             <option value ='entregado'> Entregado </option>
                             <option value ='cancelado'> Cancelado </option>
                             <option value ='devuelto'> Devuelto </option>
                        </select>
                        
                
                </div>
                <input type="hidden" id="filter_order_status" name="filter_order_status">
                <div class="form-group">
                  
                </div>
                </form>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                    
                        <table class="table no-margin" data-toggle="table"  id="order_list"
                            data-url="api-firebase/get-bootstrap-table-data.php?table=orders"
                            data-page-list="[5, 10, 20, 50, 100, 200]"
                            data-show-refresh="true" data-show-columns="true"
                            data-side-pagination="server" data-pagination="true"
                            data-search="true" data-trim-on-search="false"
							data-sort-name="id" data-sort-order="desc"
                            data-query-params="queryParams_1"
                            data-show-footer="true"
                            data-footer-style="footerStyle"
                            >
                            <thead>
                                <tr>
    								<th data-field="id" data-sortable='true'>ID</th>
									<th data-field="user_id" data-sortable='true' data-visible="false">Usuario ID</th>
									 <th data-field="qty" data-sortable='true' data-visible="false">Cantidad</th>
                                    <th data-field="name" data-sortable='true'>Nombre</th>
									<th data-field="mobile" data-sortable='true' data-visible="true">Telefono.</th>
									<th data-field="items" data-sortable='true' data-visible="false">Productos</th>
									<th data-field="total" data-sortable='true' data-visible="true">Total(<?=$settings['currency']?>)</th>
									<th data-field="delivery_charge" data-sortable='true'>comision</th>
									<th data-field="tax" data-sortable='false'>IGV <?=$settings['currency']?></th>
									<th data-field="discount" data-sortable='true' data-visible="true">Dscto.<?=$settings['currency']?></th>
									<th data-field="promo_code" data-sortable='true' data-visible="false">Cód promocional</th>
									<th data-field="promo_discount" data-sortable='true' data-visible="true">Dst prom.(<?=$settings['currency']?>)</th>
									<th data-field="wallet_balance" data-sortable='true' data-visible="true">S.Utlz(<?=$settings['currency']?>)</th>
									<th data-field="final_total" data-sortable='true'>F.Total(<?=$settings['currency']?>)</th>
									<th data-field="deliver_by" data-sortable='true' data-visible='false'>Entregado por</th>
									<th data-field="payment_method" data-sortable='true' data-visible="true">Metodo P.</th>
									<th data-field="address" data-sortable='true' data-visible="false">Direccion</th>
									<th data-field="delivery_time" data-sortable='true' data-visible='false'>D.Hora</th>
									<th data-field="status" data-sortable='true' data-visible='false'>Estado</th>
									<th data-field="active_status" data-sortable='true' data-visible='true'>A.Estado</th>
									<th data-field="date_added" data-sortable='true' data-visible="false">O.Fecha</th>
									<th data-field="operate">Action</th>
								</tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
 <?php } else { ?>
    <div class="alert alert-danger topmargin-sm leftmargin-sm">No tienes permiso para ver pedidos.</div>
 <?php } ?>
<!-- /.content -->
<script>
    $('#filter_order').on('change', function() {
        status = $('#filter_order').val();
        $('#filter_order_status').val(status);
        
	});
</script>
<script>
  $(document).ready(function(){
    	$('#date').daterangepicker({
				"autoApply": true,
				"showDropdowns": true,
                "alwaysShowCalendars":true,
				"startDate":moment(),
				"endDate":moment(),
				"locale": {
					"format": "DD/MM/YYYY",
					"separator": " - "
				},
			});

            $('#date').on('apply.daterangepicker', function(ev, picker) {
				var drp = $('#date').data('daterangepicker');
				$('#start_date').val(drp.startDate.format('YYYY-MM-DD'));
				$('#end_date').val(drp.endDate.format('YYYY-MM-DD'));
			});
        	$('#date').on('apply.daterangepicker', function(ev, picker) {
				var drp = $('#date').data('daterangepicker');
				$('#start_date').val(drp.startDate.format('YYYY-MM-DD'));
				$('#end_date').val(drp.endDate.format('YYYY-MM-DD'));
                $('#order_list').bootstrapTable('refresh');
			});
			$('#filter_order').on('change',function(){
			 //   alert('change');
			    $('#order_list').bootstrapTable('refresh');
			});
  });
  function queryParams_1(p){
			return {
				"start_date": $('#start_date').val(),
				"end_date": $('#end_date').val(),
				"filter_order": $('#filter_order_status').val(),
				limit:p.limit,
				sort:p.sort,
				order:p.order,
				offset:p.offset,
				search:p.search
			};
		}

        function totalFormatter() {
    return '<span style="color:green;font-weight:bold;font-size:large;">TOTAL</span>'
  }

  function orderFormatter(data) {
    return '<span style="color:green;font-weight:bold;font-size:large;">'+ data.length + ' Order'
  }
  var total =0;
  function priceFormatter(data) {
    // return JSON.stringify(data);
    var field = this.field
    return '<span style="color:green;font-weight:bold;font-size:large;">'+ data.map(function (row) {
     return +row[field]
    })
    .reduce(function (sum, i) {
      return sum + i
    }, 0)+ ' Rs.<span>'
  }
  function delivery_chargeFormatter(data) {
    // return JSON.stringify(data);
    var field = this.field
    return '<span style="color:green;font-weight:bold;font-size:large;">'+ data.map(function (row) {
     return +row[field]
    })
    .reduce(function (sum, i) {
      return sum + i
    }, 0)+' Rs.</span>'
  }
  
  function final_totalFormatter(data) {
    // return JSON.stringify(data);
    var field = this.field
    return '<span style="color:green;font-weight:bold;font-size:large;">'+ data.map(function (row) {
     return +row[field]
    })
    .reduce(function (sum, i) {
      return sum + i
    }, 0)+ ' Rs.<span>'
  }
</script>
<?php 
$db->disconnect();
?>