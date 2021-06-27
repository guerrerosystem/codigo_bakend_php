<?php
    if (isset($_GET['id'])) {
        $ID = $_GET['id'];
    } else {
        $ID = "";
    }
    // create array variable to handle error
    $error = array();
    if (isset($_POST['update_order_status'])) {
        $process = $_POST['status'];
    }
    $sql="SELECT oi.*,u.*,p.*,v.*,o.*,u.name as uname,o.status as order_status,p.name as pname,(SELECT short_code FROM unit un where un.id=v.measurement_unit_id)as mesurement_unit_name FROM `order_items` oi JOIN users u ON u.id=oi.user_id JOIN product_variant v ON oi.product_variant_id=v.id JOIN products p ON p.id=v.product_id JOIN orders o ON o.id=oi.order_id WHERE o.id=".$ID;
    $db->sql($sql);
    $res=$db->getResult();
    $items=[];
    foreach($res as $row){
        $data=array($row['product_id'],$row['product_variant_id'],$row['pname'],$row['measurement'],$row['mesurement_unit_name'],$row['quantity'],$row['discounted_price'],$row['discounted_price']*$row['quantity']);
        array_push($items, $data);
    }
       
?>
<section class="content-header">
    <h1>detalle de pedido</h1>
    <?php echo isset($error['update_data']) ? $error['update_data'] : ''; ?>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">detalle de pedido</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
<!--                    <form  id="update_status_form">-->
                         <table class="table table-bordered">
                            <tr>
                                <input type="hidden" name="hidden" id="order_id" value="<?php echo $res[0]['id']; ?>">
                                <th style="width: 10px">ID</th>
                                <td><?php echo $res[0]['id']; ?></td>
                            </tr>
                            <tr>
                                <th style="width: 10px">nombre</th>
                                <td><?php echo $res[0]['uname']; ?></td>
                            </tr>
                            <tr>
                                <th style="width: 10px">Email</th>
                                <td><?php echo $res[0]['email']; ?></td>
                            </tr>
                            <tr>
                                <th style="width: 10px">Contacto</th>
                                <td><?php echo $res[0]['mobile']; ?></td>
                            </tr>
                             <tr>
                                <th style="width: 10px">productos</th>
                                <td><?php $total = 0;
                                
                                    foreach ($items as $item) {
                                        // echo $item[8];
                                        if($item[8]=='recibido'){
                                            $active_status = '<label class="label label-primary">'.$item[8].'</label>';
                                        }
                                        if($item[8]=='procesado'){
                                            $active_status = '<label class="label label-info">'.$item[8].'</label>';
                                        }
                                        if($item[8]=='enviado'){
                                            $active_status = '<label class="label label-warning">'.$item[8].'</label>';
                                        }
                                        if($item[8]=='entregado'){
                                            $active_status = '<label class="label label-success">'.$item[8].'</label>';
                                        }
                                        if($item[8]=='devuelto' || $item[8]=='cancelado'){
                                            $active_status = '<label class="label label-danger">'.$item[8].'</label>';
                                        }
                                        $total += $subtotal = ($item[6] != 0 && $item[6] < $item[7])?($item[6]*$item[5]) : ($item[7]*$item[5]);
                                        echo "<b>Producto Id : </b>" . $item[0];
                                        echo "<b> Variante producto Id : </b>" . $item[1];
                                        echo " <b>Nombre : </b>" . $item[2];
                                        echo " <b>Unidad : </b>" . $item[3]." ".$item[4];
                                        echo " <b>Cantidad : </b>" . $item[5];
                                        echo " <b>Precio : </b>" . $item[7];
                                        echo " <b>Precio descontado : </b>" . $item[6];
                                        echo " <b>Subtotal : </b>" . $subtotal;
                                        echo " <b>Estado activo : </b>" . $active_status."<br>
                                        -----------------------------------<br>";
                                        
                                    }?>

                                </td>
                            </tr>
                            <tr>
                                <th style="width: 10px">Total (<?=$settings['currency']?>)</th>
                                <td ><?php echo $res[0]['order_total']; ?></td>
                            </tr>
                            <tr>
                                <th style="width: 10px">D.comicio(<?=$settings['currency']?>)</th>
                                <td ><?php echo $res[0]['delivery_charge']; ?></td>

                            </tr>
                            <tr>
                                <th style="width: 10px">Impuesto <?=$settings['currency']?>(%)</th>
                                <td ><?php echo $res[0]['tax_amount'].'('.$res[0]['tax_percentage'].'%)'; ?></td>
                            </tr>
                            <?php
                                $discounted_amount = $res[0]['total'] * $res[0]['discount'] / 100; /*  */
                        	    $final_total = $res[0]['total'] - $discounted_amount;
                                $discount_in_rupees = $res[0]['total']-$final_total;
                            ?>
                            <tr>
                                <th style="width: 10px">Descto. <?=$settings['currency']?>(%)</th>
                                <td ><?php echo  $discount_in_rupees.'('.$res[0]['discount'].'%)'; ?></td>
                            </tr>
                             
                            <tr>
                                <th style="width: 10px">Descto promocional. (<?=$settings['currency']?>)</th>
                                <td ><?php echo $res[0]['promo_discount']; ?></td>
                            </tr>
                            <tr>
                                <th style="width: 10px">saldo utilizada</th>
                                <td ><?php echo $res[0]['wallet_balance']; ?></td>
                            </tr>
                            
                            
                            <input type="hidden" name="total_amount" id="total_amount" value="<?php echo $res[0]['order_total']+$res[0]['delivery_charge']?>">
                            <input type="hidden" name="final_amount" id="final_amount" value="<?php echo $res[0]['final_total'];?>">
                            
                             <tr>
                                <th style="width: 10px">Descto %</th>
                                <td ><input type="text" class="form-control"  value="<?php echo $res[0]['discount']; ?>" disabled min=0></td>
                                
                            </tr>
                            
                            
                            <tr>
                                <th style="width: 10px">Total a pagar(<?=$settings['currency']?>)</th>
                                <td ><input type="text" class="form-control" id="final_total" name="final_total" value="<?=ceil($res[0]['final_total']);?>" disabled ></td>
                            </tr>
                            <tr>
                                <th >Entregado por</th>
                               
                                
                                <td>
                                    <p>tu.</p>

                                </td>
                                    
                             
                            </tr>
                            <tr>
                                <th style="width: 10px">Metodo p.</th>
                                <td ><?php echo $res[0]['payment_method']; ?></td>
                            </tr>
                            <tr>
                                <th style="width: 10px">Cod promocional</th>
                                <td ><?=(!empty($res[0]['promo_code']) || $res[0]['promo_code'] != null)?$res[0]['promo_code']:""; ?></td>
                            </tr>
                            <tr>
                                <th style="width: 10px">direccion</th>
                                <td ><?php echo $res[0]['address']; ?></td>
                            </tr>
                            <tr>
                                <th style="width: 10px">Fecha de pedido</th>
                                <td ><?php echo date('d-m-Y',strtotime($row['date_added'])); ?></td>
                            </tr>
                            <tr>
                                <th >Estado</th>
                                <td>
                                <?php
                                    $status = json_decode($res[0]['order_status']);
                                    $i = count($status);
                                    $currentStatus = $status[$i - 1][0];
                                    ?>

                                    <select name="status" id="status" class="form-control">
                                    <option value = "recibido"> Recibido </option>
                                         <option value = "procesado"> Procesado </option>
                                         <option value = "enviado"> Enviado </option>
                                         <option value = "entregado"> Entregado </option>
                                         <option value = "cancelado"> Cancelar </option>
                                         <option value = "return"> Devuelto </option>
                                    </select>
                                </td>
                            </tr>
                            
                        </table>

                        <!-- /.box-body -->
                        <div class="alert alert-danger" id="result_fail" style="display:none"></div>
                        <div class="alert alert-success" id="result_success" style="display:none"></div>
                        <div class="box-footer clearfix">
                            <a href="#" title='Actualizar' class="btn btn-primary update_order_status" id="submit_btn" data-id='<?=$res[0]['id'];?>'>actualizar</a>
                            <a class="btn btn-primary" data-fancybox="" data-options="{&quot;iframe&quot; : {&quot;css&quot; : {&quot;width&quot; : &quot;80%&quot;, &quot;height&quot; : &quot;80%&quot;}}}" href="https://www.google.com/maps/search/?api=1&amp;query=<?=$res[0]['latitude'];?>,<?=$res[0]['longitude'];?>&hl=es;z=14&amp;output=embed">ubicacion</a>
                        </div>

                        
<!--                    </form>-->
                </div>
                
               <?php if ($currentStatus == "recibido") { ?>
                    <button class="btn btn-primary pull-right" onclick="myfunction()"  style="margin-right: 5px; margin-top: -45px;"><i class="fa fa-download"></i>Generar factura</button>
                <?php } elseif ($currentStatus == "procesado") { ?>
                    <button class="btn btn-primary pull-right" onclick="myfunction()" style="margin-right: 5px; margin-top: -45px;"><i class="fa fa-download"></i> Generar factura</button>
                <?php } elseif ($currentStatus == "enviado") { ?>
                    <button class="btn btn-primary pull-right" onclick="myfunction()" style="margin-right: 5px; margin-top: -45px;"><i class="fa fa-download"></i>Generar factura</button>
                <?php } elseif ($currentStatus == "entregado") { ?>
                    <button class="btn btn-primary pull-right" onclick="myfunction()" style="margin-right: 5px; margin-top: -45px;"><i class="fa fa-download"></i> Generar factura</button>
                <?php } else { ?>
                    <button class="btn btn-primary disabled pull-right" style="margin-right: 5px; margin-top: -45px;"><i class="fa fa-download"></i> Generar factura</button>
                <?php } ?>
            </div>
            <!-- /.box -->
        </div>
               <div class="col-md-6">
            <ul class="timeline">
            <?php foreach($status as $s){ ?>
                <!-- timeline time label -->
                <li class="time-label">
                    <span class="bg-blue">
                        <?=$s[0];?>
                    </span>
                </li>
                <!-- /.timeline-label -->
                <!-- timeline item -->
                <li>
                    <!-- timeline icon -->
                    <i class="fa fa-circle bg-blue"></i>
                    <div class="timeline-item">
                        <!--<span class="time"><i class="fa fa-clock-o"></i> 12:05</span>-->
                        <h3 class="timeline-header"><?=$s[1];?></h3>
                        <div class="timeline-body">
                        </div>
                    </div>
                </li>
                <!-- timeline time label -->
            <!-- /.timeline-label -->
            <!-- timeline item -->
            <!-- END timeline item -->
        <?php } ?>
        </ul>
        </div>
    </div>
</section>
<!-- <script>
    var total_amount=$('#total_amount').val();
    $("#final_total").val(total_amount);
</script> -->

<script>
    
$(document).on('click','.update_order_status',function(e){
	e.preventDefault();
        var status = $('#status').val();
		var id = $('#order_id').val();
        var deliver_by = $('#deliver_by').val();
		var dataString ='update_order_status=true&id='+id+'&status='+status+'&delivery_boy_id='+<?=$_SESSION['delivery_boy_id'];?>+'&ajaxCall=1';
	$.ajax({        
        url: "../api-firebase/order-process.php",
        type: "POST",
        data: dataString,
        beforeSend:function(){$('#submit_btn').html('Por favor espera..');$('#submit_btn').attr('disabled',true);},
        dataType: "json",
        success: function (data) {
            var result = $.map(data, function(value, index) {
                return [value];
			});
			if(result[1][0]=='C'){
			    $('#result_fail').html(result[1]);
			    $('#result_fail').show().delay(3000).fadeOut();
			}else{
			    $('#result_success').html(result[1]);
			    $('#result_success').show().delay(3000).fadeOut();
			}
			
			
			$('#submit_btn').attr('disabled',false);
			$('#submit_btn').html('Actualizar');
			
			 //alert(result[1]);
// 			if(!result[0]){
// 				location.reload();
//             }
        }

    });
});
</script>
<script type="text/javascript">
/* function sendMail(){
    var process = $('#status').val();
    window.location.href = './public/send-message.php?process='+process+'&id=<?php //echo $data['id']; ?>';
} */
</script>

<script>
    $(document).ready(function () {
        $("#status").val("<?= $GLOBALS['currentStatus'] ?>");
    });
</script>
<script>
    function myfunction() {
        window.location.href = 'factura.php?id=<?php echo $res[0]['id']; ?>';
    }
</script>

<?php $db->disconnect(); ?>