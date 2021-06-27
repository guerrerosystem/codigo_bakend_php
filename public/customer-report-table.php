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
<?php
include_once('includes/functions.php');
?>
<!-- Main row -->

<div class="row">
    <!-- Left col -->
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Informe del cliente</h3>
                <div class="box-tools">
                    <form method="post" action="informe-cliente.php" name="form1">
                        <div class="input-group" style="width: 360px;">
                            <input type="text" id="from" name="start_date" placeholder="YYYY/MM/DD" required/>
                            A
                            <input type="text" id="to" name="end_date" placeholder="YYYY/MM/DD" required/>

                            <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>


                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if (isset($_POST) && isset($_POST['start_date']) && isset($_POST['end_date'])) {
    $function = new functions;
    $month = $_POST['start_date'];
    $daysago = $_POST['end_date'];
    // create array variable to store data from database
    $data = array();

    if (isset($_GET['keyword'])) {
        // check value of keyword variable
        $keyword = $_GET['keyword'];
    } else {
        $keyword = "";
    }

    // get all data from pemesanan table
    if (empty($keyword)) {
        $sql_query = "SELECT id,name,email, mobile,city,area, street ,created_at
				FROM users WHERE created_at < '" . $daysago . "' and created_at >'" . $month . "'
				ORDER BY id DESC";
    } else {
        $sql_query = "SELECT id,name,email, mobile,city,area, street ,created_at
				FROM users WHERE created_at < '" . $daysago . "' and created_at >'" . $month . "'
				AND name LIKE '%".$keyword."%' 
				ORDER BY id DESC";
    }
        // Bind your variables to replace the ?s
        
        // Execute query
        $db->sql($sql_query);
        // store result 
        $res=$db->getResult();

        // get total records
        $total_records = $db->numRows($res);
    

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
    // get all data from pemesanan table
    if (empty($keyword)) {
        $sql_query = "SELECT id,name,email, mobile,city,area, street ,created_at
				FROM users WHERE created_at < '" . $daysago . "' and created_at >'" . $month . "'
				ORDER BY id DESC 
				LIMIT ".$from.", ".$offset."";
    } else {
        $sql_query = "SELECT id,name,email, mobile,city,area, street ,created_at
				FROM users WHERE created_at < '" . $daysago . "' and created_at >'" . $month . "'
				AND  name LIKE '%".$keyword."%' 
				ORDER BY id DESC 
				LIMIT ".$from.", ".$offset."";
    }
   
        // Bind your variables to replace the ?s
       
        // Execute query
        $db->sql($sql_query);
        // store result 
        $res=$db->getResult();
        // for paging purpose
        $total_records_paging = $db->numRows($res);
    

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

                            <div class="box-tools">
                                <form  method="get">
                                    <div class="input-group" style="width: 150px;">

                                        <input type="text" name="keyword" class="form-control input-sm pull-right" placeholder="Search">
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
                                   

                                    <th> No </th>
                                     <th> Nombre </th>
                                     <th> Correo </th>
                                     <th> Telefono </th>
                                     <th> Ciudad </th>
                                     <th> Distrito </th>
                                     <th> Direccion exacta </th>
                                     <th> Fecha </th>


                                </tr>
                                <?php
                                // get all data using while loop
                                $count = 1;
                                foreach ($res as $row) {
                                    ?>
                                    <tr>
                                        <td><?php echo $count; ?> </td>
                                        <td><?php echo $row['name']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td><?php echo $row['mobile']; ?></td>
                                        <td><?php echo $row['city']; ?></td>
                                        <td><?php echo $row['area']; ?></td>


                                        <td><?php echo $row['street']; ?></td>
                                        <td><?php echo $row['created_at']; ?></td>

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
                    $function->doPages($offset, 'informe-cliente.php', '', $total_records, $keyword);
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
    $function = new functions;
    // create array variable to store data from database
    $data = array();

    if (isset($_GET['keyword'])) {
        // check value of keyword variable
        $keyword = $_GET['keyword'];
    } else {
        $keyword = "";
    }

    // get all data from pemesanan table
    if (empty($keyword)) {
        $sql_query = "SELECT id,name,email, mobile,city,area, street ,created_at
				FROM users WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 MONTH) 
				ORDER BY id DESC";
    } else {
        $sql_query = "SELECT id,name,email, mobile,city,area, street ,created_at
				FROM users WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 MONTH) 
				AND name LIKE '%".$keyword."%' 
				ORDER BY id DESC";
    }
        // Execute query
        $db->sql($sql);
        // store result 
        $res=$db->getResult();

        // get total records
        $total_records = $db->numRows($res);
    

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
    // get all data from pemesanan table
    if (empty($keyword)) {
        $sql_query = "SELECT id,name,email, mobile,city,area, street ,created_at
				FROM users WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 MONTH)
				ORDER BY id DESC 
				LIMIT ".$from.", ".$offset."";
    } else {
        $sql_query = "SELECT id,name,email, mobile,city,area, street ,created_at
				FROM users WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 MONTH)
				AND  name LIKE %'".$keyword."%' 
				ORDER BY id DESC 
				LIMIT ".$from.", ".$offset."";
    }
        // Execute query
        $db->sql($sql_query);
        // store result 
        $res=$db->getResult();


        // for paging purpose
        $total_records_paging = $db->numRows($res);
    }

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


                            <div class="box-tools">
                                <form  method="get">
                                    <div class="input-group" style="width: 150px;">

                                        <input type="text" name="keyword" class="form-control input-sm pull-right" placeholder="Search">
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
                                      <th>No</th>
                                     <th> Nombre </th>
                                     <th> email </th>
                                     <th> Telefono </th>
                                     <th> Ciudad </th>
                                     <th> Distrito </th>
                                     <th> Direccion exacta </th>
                                     <th> Fecha </th>

                                </tr>
                                <?php
                                // get all data using while loop
                                $count = 1;
                                foreach ($res as $row) {
                                    ?>
                                    <tr>
                                        <td><?php echo $count; ?> </td>
                                        <td><?php echo $row['name']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td><?php echo $row['mobile']; ?></td>
                                        <td><?php echo $row['city']; ?></td>
                                        <td><?php echo $row['area']; ?></td>


                                        <td><?php echo $row['street']; ?></td>
                                        <td><?php echo $row['created_at']; ?></td>
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
                    $function->doPages($offset, 'informe-cliente.php', '', $total_records, $keyword);
                    ?>
                </h4>
            </div>
            <div class="separator"> </div>
            <!-- right col (We are only adding the ID to make the widgets sortable)-->
        </div><!-- /.row (main row) -->

    </section><!-- /.content --> 
    <?php
    $db->disconnect();

?>
