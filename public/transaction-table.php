<?php
include_once('includes/functions.php');
?>
<?php

$function = new functions;


$data = array();
if (isset($_GET['keyword'])) {
    
    $keyword = $function->sanitize($_GET["keyword"]);
} else {
    $keyword = "";
}
if (empty($keyword)) {
    $sql_query = "SELECT count(*)
                    as total_records FROM transactions
                    ORDER BY id ASC";
} else {
    $sql_query = "SELECT count(*)
                    as total_records FROM transactions WHERE id LIKE '%".$keyword."%'
                    ORDER BY id ASC";
}
$db->sql($sql_query);
    $res = $db->getResult();
    foreach($res as $row){
        $total_records = $row['total_records'];
    }

if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

// number of data that will be display per page     
$offset = 10;

//lets calculate the LIMIT for SQL, and save it $from
if ($page) {
    $from = ($page * $offset) - $offset;
} else {
    //if nothing was given in page request, lets load the first page
    $from = 0;
}

// get all data from reservation table

if (empty($keyword)) {
    $sql_query = "SELECT t.id,u.name,user_id,order_id,type,payu_txn_id, amount, t.status, message, transaction_date
                    FROM transactions t INNER JOIN users u ON t.user_id = u.id ORDER BY t.id ASC LIMIT ".$from.",".$offset."";
                    
} else {
    $sql_query = "SELECT t.id,u.name,user_id,order_id,type,payu_txn_id, amount, t.status, message, transaction_date
                    FROM transactions t INNER JOIN users u ON t.user_id = u.id where t.id LIKE '%".$keyword."%'
                    ORDER BY id ASC LIMIT ".$from.",".$offset."";
}

    // Execute query
    $db->sql($sql_query);
    // store result 
    $res = $db->getResult();

    // for paging purpose
    $total_records_paging = $total_records;

    if($permissions['transactions']['read']==1){
if ($total_records_paging == 0) {
    ?>
    <section class="content-header">
        <h1>
        Transacción no disponible /
            <small><a href="home.php"><i class="fa fa-home"></i> Home</a></small>

        </h1>
        <hr />
        <?php
        // otherwise, show data
    } else {
        $row_number = $from + 1;
        ?>
        <section class="content-header">
            <h1>
                Transacion /
                <small><a href="home.php"><i class="fa fa-home"></i> Home</a></small>
            </h1>


        </section>

        <!-- Main content -->

        <section class="content">
            <!-- Main row -->

            <div class="row">
                <!-- Left col -->
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">

                            <div class="box-tools">

                            </div>
                        </div><!-- /.box-header -->

                        <div class="box-body table-responsive">
                            <table class="table table-hover">
                                <tr>
                                    
                                    <th> ID de transacción </th>
                                     <th> Nombre de usuario </th>
                                     <th> ID de pedido </th>
                                     <th> Tipo </th>
                                     <th> ID TXN </th>
                                     <th> Cantidad </th>
                                     <th> Estado </th>
                                     <th> Mensaje </th>
                                     <th> Fecha de transacción </th>
                                </tr>

                                <?php
                                // get all data using foreach loop
                            
                                $count = 1;
                                foreach($res as $row){?>
                                       
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo $row['name']; ?></td>
                                        <td><?php echo $row['order_id']; ?></td>
                                        <td><?php echo $row['type']; ?></td>
                                        <td><?php echo $row['payu_txn_id']; ?></td>
                                        <td><?php echo $row['amount']; ?></td>
                                        <td><?php echo $row['status']; ?></td>
                                        <td><?php echo $row['message']; ?></td>

                                        <td><?php echo $row['transaction_date']; ?></td>
                                    </tr>
                                    <?php
                                    $count++;
                                }?>
                               

                            </table>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div>
                <div class="col-sx-12">
                    <h4>
                        <?php
                        // for pagination purpose
                        $function->doPages($offset, 'transacciones.php', '', $total_records, $keyword);
                        ?>
                    </h4>
                </div>
                <div class="separator"> </div>
                <!-- right col (We are only adding the ID to make the widgets sortable)-->
            </div><!-- /.row (main row) -->

        </section><!-- /.content -->    <?php } } else{ ?>

            <div class="alert alert-danger topmargin-sm" style="margin-top: 20px;">Yno tienes permiso para ver transacciones</div>
        <?php } ?>
    <?php
   $db->disconnect();
    ?>
                    
