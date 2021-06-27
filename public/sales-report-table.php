<script>
    $(document).ready(function () {
        var date = new Date();
        var currentMonth = date.getMonth() - 10;
        var currentDate = date.getDate();
        var currentYear = date.getFullYear() - 10;

        $('#from').datepicker({
            minDate: new Date(currentYear, currentMonth, currentDate),
            dateFormat: 'yy-mm-dd',

        });
    });
</script>
<script>
    $(document).ready(function () {
        var date = new Date();
        var currentMonth = date.getMonth() - 10;
        var currentDate = date.getDate();
        var currentYear = date.getFullYear() - 10;

        $('#to').datepicker({
            minDate: new Date(currentYear, currentMonth, currentDate),
            dateFormat: 'yy-mm-dd',

        });
    });
</script>
<script language="javascript">
    function printpage()
    {
        window.print();
    }
</script>
<!-- Main row -->

<div class="row">
    <!-- Left col -->
    <div class="col-xs-12">
        <?php if($permissions['reports']['read']==1){?>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Reporte de ventas</h3>
                <div class="box-tools">
                    <form method="post" action="reportes-venta.php" name="form1">
                        <div class="input-group" style="width: 360px;">
                            <input type="text" id="from" name="start_date" placeholder="AAAA/MM/DD" required/>
                            A
                            <input type="text" id="to" name="end_date" placeholder="AAAA/MM/DD" required/>

                            <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>


                        </div>
                    </form>
                </div>
            </div>
            <?php } else { ?>
        <div class="alert alert-danger topmargin-sm" style="margin-top: 20px;">No tienes permiso para ver informes</div>
            <?php } ?>
        </div>
    </div>
</div>

<?php
if (isset($_POST) && isset($_POST['start_date']) && isset($_POST['end_date'])) {
    $month = $_POST['start_date'];
    $daysago = $_POST['end_date'];
    if (isset($_GET['keyword'])) {
        // check value of keyword variable
        $keyword = $_GET['keyword'];
    } else {
        $keyword = "";
    }
    if (empty($keyword)) {
        $sql_query = "SELECT id, mobile, address,date_added,final_total
                FROM orders WHERE date_added < '" . $daysago . "' and date_added >'" . $month . "'
                ORDER BY id DESC";
    } else {
        $sql_query = "SELECT id, mobile,address,date_added,final_total
                FROM orders WHERE date_added < '" . $daysago . "' and date_added >'" . $month . "'
                AND mobile LIKE '%".$keyword."%'' 
                ORDER BY id DESC";
    }
        // Execute query
        $db->sql($sql_query);
        // store result 
        $res=$db->getResult();  
        // get total records
        $total_records = $db->numRows();
    // check page parameter
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }

    // number of data that will be display per page
    $offset = 20;

    //lets calculate the LIMIT for SQL, and save it $from
    if ($page) {
        $from = ($page * $offset) - $offset;
    } else {
        //if nothing was given in page request, lets load the first page
        $from = 0;
    }
    
    $month = $_POST['start_date'];
    $daysago = $_POST['end_date'];
    $sql_daily = "SELECT SUM(final_total) as num FROM orders  WHERE date_added< '" . $daysago . "' and date_added >'" . $month . "'";
    $db->sql($sql_daily);
    $total_daily = $db->getResult();
    $total_daily = $total_daily[0]['num'];
     
    
    // get all data from pemesanan table
    if (empty($keyword)) {
        $sql_query = "SELECT id, mobile,address,date_added,final_total
                FROM orders WHERE date_added < '" . $daysago . "' and date_added >'" . $month . "'
                ORDER BY id DESC 
                LIMIT ".$from.", ".$offset."";
    } else {
        $sql_query = "SELECT id, mobile, address,date_added,final_total
                FROM orders WHERE date_added < '" . $daysago . "' and date_added >'" . $month . "'
                AND  mobile LIKE '%".$keyword."%' 
                ORDER BY id DESC 
                LIMIT ".$from.", ".$offset."";
    }

        $db->sql($sql_query);
        $res=$db->getResult();

        // for paging purpose
        $total_records_paging = $total_records;

    // if no data on database show "Tidak Ada Pemesanan"
    if ($total_records_paging == 0) {
        ?>
        <section class="content-header">
            <h1>No hay registros para esta fecha</h1>
            <ol class="breadcrumb">
                <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
            </ol>
            <hr />
        </section>
        <?php
        // otherwise, show data
    } else {
        $row_number = $from + 1;
        ?>
        <section class="content-header">
            <h1>Lista de registros</h1>
            <ol class="breadcrumb">
                <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
            </ol>
            <hr/>
        </section>

        <!-- search form -->
        <section class="content">
            <!-- Main row -->

            <div class="row">
                <!-- Left col -->
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Registros</h3>
<!--                            <h3 class="box-title">Total_Sale : <?php // echo $total_daily; ?></h3>-->
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
                        </div><!-- /.box-header -->
                        <div class="box-body table-responsive">
                            <table class="table table-hover">
                                <tr>
                                   

                                    <th> Telefono </th>
                                     <th> Dirección </th>
                                     <th> Fecha de pedido </th>
                                     <th> Total final </th>
                                    

                                </tr>
                                <?php
                                $count = 1;
                                foreach($res as $row) {
                                
                                    ?>
                                    <tr>
                                        <td><?php echo $row['mobile']; ?></td>
                                        <td><?php echo $row['address']; ?></td>
                                        <td><?php echo $row['date_added']; ?></td>
                                   
                                        <td><?php echo $row['final_total']; ?></td>
                                       
                                    </tr>
                                    <?php
                                    $count++;
                                }
                                }
                                
    
                            
                            ?>
                            
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
            <div class="col-sx-12">
                <h4>
                    <?php
                    // for pagination purpose
                   $function->doPages($offset, 'reportes-venta.php', '', $total_records, $keyword);
                    ?>
                </h4>
            </div>
            <div class="separator"> </div>
            <!-- right col (We are only adding the ID to make the widgets sortable)-->
        </div><!-- /.row (main row) -->

    </section><!-- /.content --> 
    <?php
   $db->disconnect();
} else {

    if (isset($_GET['keyword'])) {
        // check value of keyword variable
        $keyword = $_GET['keyword'];
    } else {
        $keyword = "";
    }

    // get all data from pemesanan table
    if (empty($keyword)) {
        $sql_query = "SELECT id, mobile,address,date_added,final_total
                FROM orders WHERE date_added > DATE_SUB(NOW(), INTERVAL 1 MONTH) 
                ORDER BY id DESC";
    } else {
        $sql_query = "SELECT id, mobile,address,date_added,final_total
                FROM orders WHERE date_added > DATE_SUB(NOW(), INTERVAL 1 MONTH) 
                AND mobile LIKE '%".$keyword."%'
                ORDER BY id DESC";
    }
        // Execute query
        $db->sql($sql_query);
        // store result 
        $res=$db->getResult();
        
        // get total records
        $total_records = $db->numRows();
        // echo $total_records;
    

    // check page parameter
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }

    // number of data that will be display per page
    $offset = 20;

    //lets calculate the LIMIT for SQL, and save it $from
    if ($page) {
        $from = ($page * $offset) - $offset;
    } else {
        //if nothing was given in page request, lets load the first page
        $from = 0;
    }
                                          
    $sql_daily = "SELECT SUM(final_total) as num  FROM orders WHERE date_added > DATE_SUB(NOW(), INTERVAL 1 MONTH)";
    $db->sql($sql_daily);
    $total_daily = $db->getResult();
    $total_daily = $total_daily[0]['num'];
   
    // get all data from pemesanan table
    if (empty($keyword)) {
        $sql_query = "SELECT id, mobile, address,date_added,final_total
                FROM orders WHERE date_added > DATE_SUB(NOW(), INTERVAL 1 MONTH)
                ORDER BY id DESC 
                LIMIT ".$from.",".$offset."";
    } else {
        $sql_query = "SELECT id, mobile, address,date_added,final_total
                FROM orders WHERE date_added > DATE_SUB(NOW(), INTERVAL 1 MONTH)
                AND  mobile LIKE '%".$keyword."%' 
                ORDER BY id DESC 
                LIMIT ".$from.",".$offset."";
    }
    $db->sql($sql_query);
    $res=$db->getResult();
    $total_records_paging = 1;
        // for paging purpose
        $total_records_paging = $total_records;
        
    

    // if no data on database show "Tidak Ada Pemesanan"
    if ($total_records_paging == 0) {
        ?>
        <section class="content-header">
            <h1>No hay registros</h1>
            <ol class="breadcrumb">
                <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
            </ol>
            <hr />
        </section>
        <?php
        // otherwise, show data
    } else {
        $row_number = $from + 1;
        ?>
        <section class="content-header">
            <h1>Lista de registros</h1>
            <ol class="breadcrumb">
                <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
            </ol>
            <hr/>
        </section>

        <!-- search form -->
        <section class="content">
            <!-- Main row -->

            <div class="row">
                <!-- Left col -->
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Registros</h3>
<!--                            <h3 class="box-title">Total_Sale : <?php //  echo $total_daily; ?></h3>-->
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
                        </div><!-- /.box-header -->
                        <div class="box-body table-responsive">
                            <table class="table table-hover">
                                <tr>
                                <th> Telefono </th>
                                     <th> Dirección </th>

                                     <th> Fecha de pedido </th>
                                    
                                     <th> Total final </th>
                                    

                                </tr>
                                <?php
                                $count = 1;
                                    foreach($res as $row){
                                   
                                    ?>
                                    <tr>
                                        <td><?php echo $row['mobile']; ?></td>
                                        <td><?php echo $row['address']; ?></td>
                                        <td><?php echo $row['date_added']; ?></td>
                                        <td><?php echo $row['final_total']; ?></td>
                                        
                                    </tr>
                                    <?php
                                    $count++;
                                
                            }
 
                            
    }
                            ?>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
            <div class="col-sx-12">
                <h4>
                    <?php
                  
                    ?>
                </h4>
            </div>
            <div class="separator"> </div>
            <!-- right col (We are only adding the ID to make the widgets sortable)-->
        </div><!-- /.row (main row) -->

    </section><!-- /.content --> 
    <?php
     $db->disconnect();
}
?>
