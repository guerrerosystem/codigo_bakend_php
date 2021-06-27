<?php

session_start();

$currentTime = time() + 25200;
$expired = 3600;

if(!isset($_SESSION['delivery_boy_id']) && !isset($_SESSION['name'])){
    header("location:index.php");             
}

// if current time is more than session timeout back to login page
if ($currentTime > $_SESSION['timeout']) {
    session_destroy();
    header("location:index.php");
}
// destroy previous session timeout and create new one
unset($_SESSION['timeout']);
$_SESSION['timeout'] = $currentTime + $expired;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<?php include "header.php";?>
<html>
    <head>
        <title>Detalles del pedido | <?=$settings['app_name']?> - Dashboard</title>
    </head>
    <body>
        <?php
            if (isset($_GET['id'])) {
                $ID = $_GET['id'];
            
                $sql = "SELECT delivery_boy_id FROM orders WHERE id=".$ID;
                $db->sql($sql);
                $res=$db->getResult();
                if($res[0]['delivery_boy_id'] != $_SESSION['delivery_boy_id']){
                    echo "<script>alert('No tiene permiso para ver este pedido..');top.location='pedidos.php';</script>";
                    return false;
                }
                $sql_query = "SELECT status FROM delivery_boys WHERE id=".$_SESSION['delivery_boy_id'];
                $db->sql($sql_query);
                $result=$db->getResult();
                if($result[0]['status']==0){
                    echo "<script>alert('Parece que su cuenta no está activa, ¡póngase en contacto con el administrador para obtener más información!.');top.location='pedidos.php';</script>";
                    return false;
                }
            }
        ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <?php include ('public/orders-data.php');?>
    </div>
    <!-- /.content-wrapper -->
    </body>
</html>
<?php include "footer.php";?>