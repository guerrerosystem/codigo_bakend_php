<?php 

    include_once('includes/crud.php');
    $db = new Database();
    $db->connect();
    $db->sql("SET NAMES 'utf8'");
    
    include('includes/variables.php');
    include_once('includes/custom-functions.php');
    
    $fn = new custom_functions;
    $config = $fn->get_configurations();
    ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>
<section class="content-header">
    <h1>Transacciones de saldo/<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
    
</section>

<section class="content">
    
    <div class="row">
       
        <div class="col-xs-12">
            <div class="box">
                <?php if($permissions['transactions']['read']==1){?>
                <div class="box-header">
                    <h3 class="box-title">Transacciones de saldo</h3>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-hover" data-toggle="table" id="delivery-boys"
                        data-url="api-firebase/get-bootstrap-table-data.php?table=wallet-transactions"
                        data-page-list="[5, 10, 20, 50, 100, 200]"
                        data-show-refresh="true" data-show-columns="true"
                        data-side-pagination="server" data-pagination="true"
                        data-search="true" data-trim-on-search="false"
                        data-sort-name="id" data-sort-order="desc">
                        <thead>
                        <tr>
                            <th data-field="id" data-sortable="true">ID</th>
                            <th data-field="user_id" data-sortable="true">Usuario ID</th>
                            <th data-field="name" data-sortable="true">Nombre de usuario</th>
                            <th data-field="type" data-sortable="true">Tipo</th>
                            <th data-field="amount" data-sortable="true">Cantidad</th>
                            <th data-field="message" data-sortable="true">Mensaje</th>
                            <th data-field="status"  data-sortable="true">Estado</th>
                            <th data-field="date_created" data-sortable="true">Fecha de Transacción</th>
                            <th data-field="last_updated" data-sortable="true" data-visible="false">Última actualización</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <?php } else { ?>
                <div class="alert alert-danger">No tiene permiso para ver las transacciones de billetera.</div>
            <?php } ?>
        </div>
        <div class="separator"> </div>
    </div>
</section>
 




