<section class="content-header">
    <h1>Lista de clientes</h1>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
    <hr/>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <?php if($permissions['customers']['read']==1){?>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Clientes</h3>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-hover" data-toggle="table" 
						data-url="api-firebase/get-bootstrap-table-data.php?table=users"
						data-page-list="[5, 10, 20, 50, 100, 200]"
						data-show-refresh="true" data-show-columns="true"
						data-side-pagination="server" data-pagination="true"
						data-search="true" data-trim-on-search="false"
						data-filter-control="true" data-filter-show-clear="true"
						data-sort-name="id" data-sort-order="desc">
					<thead>
                        <tr>
                            <th data-field="id" data-sortable="true">ID</th>
                            <th data-field="name" data-sortable="true">Nombre</th>
                            <th data-field="email" data-sortable="true">Email</th>
                            <th data-field="mobile" data-sortable="true">M.No</th>
                            <th data-field="balance" data-sortable="true">Saldo</th>
                            <th data-field="referral_code" data-sortable="true" data-visible="false">Código de referencia</th>
                            <th data-field="friends_code" data-sortable="true">Código de amigos</th>
                            <th data-field="street" data-sortable="true">Direccion exacta</th>
                            <th data-field="area" data-sortable="true" >Disitrito</th>
                            <th data-field="city" data-sortable="true" data-filter-control="select">Ciudad</th>
                            <th data-field="status" data-sortable="true">Estado</th>
                            <th data-field="created_at" data-sortable="true">Fecha y hora</th>
                        </tr>
					</thead>
                    </table>
                </div>
              
            </div>
            <?php } else { ?>
            <div class="alert alert-danger">No tienes permiso para ver clientes</div>
        <?php } ?>
            <!-- /.box -->
        </div>
    </div>
    <!-- /.row (main row) -->
</section>
<!-- /.content -->