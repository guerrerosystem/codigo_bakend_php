
<section class="content-header">
    <h1>Configuraciones de la tienda</h1>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
    <hr />
</section>
<section class="content">

    <div class="row">
        <div class="col-md-6">
         
            <?php if($permissions['settings']['read']==1){
                    if($permissions['settings']['update']==0) { ?>
                    <div class="alert alert-danger">No tienes permiso para actualizar la configuración</div>
                    <?php } ?>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Actualizar configuración del sistema</h3>
                </div>
             
                <?php
                    $db->sql("SET NAMES 'utf8'");
                    $sql="SELECT * FROM settings WHERE  variable='system_timezone'";
                    $db->sql($sql);

                    $res_time = $db->getResult();
                    if(!empty($res_time)){
                            foreach ($res_time as $row){
                                $id = $row['id'];
                                // echo $id;
                                $data = json_decode($row['value'], true);
                            }
                            // print_r($data);
                        }
                  
                    $sql = "select value from `settings` where variable='Logo' OR variable='logo'";
                    $db->sql($sql);
                    $res_logo = $db->getResult();
                    $sql="SELECT * FROM settings WHERE variable='currency'";
                    $db->sql($sql);
                    $res_currency = $db->getResult();
                ?>
              
                <form id="system_configurations_form"  method="post" enctype="multipart/form-data">
                    <input type="hidden" id="system_configurations" name="system_configurations" required="" value="1" aria-required="true">
                        <input type="hidden" id="system_timezone_gmt" name="system_timezone_gmt" value="<?php if(!empty($data['system_timezone_gmt'])){ echo $data['system_timezone_gmt']; } ?>" aria-required="true">
                        <input type="hidden" id="system_configurations_id" name="system_configurations_id" value="<?php if(!empty($id)){ echo $id; } ?>" aria-required="true">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="app_name">Nombre de la aplicación:</label>
                            <input type="text" class="form-control" name="app_name" value="<?=(isset($data['app_name']))?$data['app_name']:'';?>" placeholder="Nombre de la aplicación: se usa en todo el sistema"/>
                        </div>
                        <div class="form-group">
                            <label for="">Número de soporte:</label>
                            <input type="text" class="form-control" name="support_number" value="<?=(isset($data['support_number']))?$data['support_number']:""?>" placeholder="Número de teléfono móvil de soporte al cliente: utilizado en todo el sistema"/>
                        </div>
                        <div class="form-group">
                            <label for="">Email de soporte:</label>
                            <input type="text" class="form-control" name="support_email" value="<?=(isset($data['support_email']))?$data['support_email']:""?>" placeholder="Correo electrónico de atención al cliente: utilizado en todo el sistema"/>
                        </div>
                        <div class="form-group">
                            <label for="app_name">Logo:</label>
                            <img src="<?=DOMAIN_URL.'dist/img/'.$res_logo[0]['value']?>" title='<?=$data['app_name']?> - Logo' alt='<?=(isset($data['app_name']))?$data['app_name']:"";?> - Logo' style="max-width:100%"/>
                            <input type='file' name='logo' id='logo' accept="image/*"/>
                        </div>
                        <h4>Configuraciones de versión</h4><hr>

                        <div class="form-group col-md-4">
                            <label for="">Versión actual de la aplicación:</label>
                            <input type="text" class="form-control" name="current_version" value="<?=isset($data['current_version'])?$data['current_version']:''?>" placeholder='Versión actual'/>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Versión mínima requerida: </label>
                            <input type="text" class="form-control" name="minimum_version_required" value="<?=isset($data['minimum_version_required'])?$data['minimum_version_required']:''?>" placeholder='Versión mínima requerida'/>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Versión Estado del sistema</label><br>
                            <input type="checkbox" id="version-system-button" class="js-switch" <?php if(!empty($data['is-version-system-on']) && $data['is-version-system-on'] == '1'){ echo 'checked'; }?>>
                            <input type="hidden" id="is-version-system-on" name="is-version-system-on" value="<?=(!empty($data['is-version-system-on']))?$data['is-version-system-on']:0;?>">
                        </div><hr>
                        
                        <div class="form-group">
                            <label for="currency">Moneda de la tienda (símbolo o código - S/. soles - Cualquiera):</label>
                            <input type="text" class="form-control" name="currency" value="<?=!empty($res_currency)?$res_currency[0]['value']:'';?>" placeholder="Símbolo o código: por ejemplo, S/. soles"/>
                        </div>
                        <div class="form-group">
                            <label for="tax">Impuesto (%):</label>
                            <input type="number" class="form-control" name="tax" value="<?=$data['tax']?>" placeholder="Ingrese solo el número" min="0"/>
                        </div>
                        <div class="form-group">
                            <label for="delivery_charge">comicion de repartidor (<?=$settings['currency']?>)</label>
                            <input type="number" class="form-control" name="delivery_charge" value="<?=$data['delivery_charge']?>" placeholder='Cargo de entrega en compras' min='0'/>
                        </div>
                        <div class="form-group">
                            <label for="delivery_charge"> Cantidad mínima para entrega gratuita(<?=$settings['currency']?>) <small>(Por debajo de este usuario se le cobrará en función de los gastos de envío)</small></label>
                            <input type="number" class="form-control" name="min_amount" value="<?=$data['min_amount']?>" placeholder='Cantidad mínima de pedido para entrega gratuita' min='0'/>
                        </div>
                        

                        <div class="form-group">
                            <label class="system_timezone" for="system_timezone">Zona horaria del sistema</label>
                            <select id="system_timezone" name="system_timezone" required class="form-control col-md-12">
                                <?php $options = getTimezoneOptions();
                                foreach($options as $option){?>     
                                <option value="<?=$option[2]?>" data-gmt="<?=$option['1'];?>" <?=(isset($data['system_timezone']) && $data['system_timezone'] == $option[2])?'selected':'';?>><?=$option[2]?> - GMT <?=$option[1]?> - <?=$option[0]?></option>  
                                <?php } ?>
                            </select>
                        </div>
                        <hr>
                        <?php
                            // print_r($data);
                        ?>
                        <h4>Referir y ganar sistema</h4><hr>
                        <div class="form-group">
                            <label for="refer-earn-system">Referir y ganar sistema</label><br>
                            <input type="checkbox" id="refer-earn-system-button" class="js-switch" <?php if(!empty($data['is-refer-earn-on']) && $data['is-refer-earn-on'] == '1'){ echo 'checked'; }?>>
                            <input type="hidden" id="is-refer-earn-on" name="is-refer-earn-on" value="<?=(!empty($data['is-refer-earn-on']))?$data['is-refer-earn-on']:0;?>">
                        </div>
                        <div class="form-group">
                            <label for="">Cantidad mínima de pedido de referencia y ganancia (<?=$settings['currency']?>)</label>
                            <input type="number" class="form-control" name="min-refer-earn-order-amount" value="<?=$data['min-refer-earn-order-amount']?>" placeholder='Cantidad mínima de pedido' />
                        </div>
                        <div class="form-group">
                            <label for="">Recomiende y gane bonos(<?=$settings['currency']?> O %)</label>
                            <input type="number" class="form-control" name="refer-earn-bonus" value="<?=$data['refer-earn-bonus']?>" placeholder='Bonos' />
                        </div>
                        <div class="form-group">
                            <label for="">Referir y ganar metodo</label>
                            <select name="refer-earn-method" class="form-control">
                                <option value="">Seleccione</option>
                                <option value="percentage" <?=(isset($data['refer-earn-method']) && $data['refer-earn-method']=='percentage')?"selected":""?> >Porcentaje</option>
                                <option value="rupees" <?=(isset($data['refer-earn-method']) && $data['refer-earn-method']=='rupees')?"selected":""?>>soles</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Cantidad máxima de referidos y ganancias (<?=$settings['currency']?>)</label>
                            <input type="number" class="form-control" name="max-refer-earn-amount" value="<?=$data['max-refer-earn-amount']?>" placeholder='Cantidad máxima por referir y ganar' />
                        </div>
                        <div class="form-group">
                            <label for="">Monto mínimo de retiro</label>
                            <input type="number" class="form-control" name="minimum-withdrawal-amount" value="<?=$data['minimum-withdrawal-amount']?>" placeholder='Monto mínimo de retiro' />
                        </div>
                        <div class="form-group">
                            <label for="">Días máximos para devolver el artículo</label>
                            <input type="number" class="form-control" name="max-product-return-days" value="<?=(isset($data['max-product-return-days']))?$data['max-product-return-days']:'';?>" placeholder='Días máximos para devolver el artículo' />
                        </div>
                        <div class="form-group">
                            <label for="">Bono repartidor (%)</label>
                            <input type="number" class="form-control" name="delivery-boy-bonus-percentage" value="<?=$data['delivery-boy-bonus-percentage']?>" placeholder='Bono repartidor' />
                        </div>
                        
                        <h4> Configuración de correo</h4><hr>
                        <div class="form-group ">
                            <label for="from_mail">Desde el e-mailID: <small>( Esta identificación de correo electrónico se usará en el sistema de correo )</small></label>
                            <input type="email" class="form-control" name="from_mail" value="<?=$data['from_mail']?>" placeholder='Desde ID de correo electrónico'/>
                        </div>
                        <div class="form-group">
                            <label for="reply_to">Responder a un correo electrónico ID: <small>( Esta identificación de correo electrónico se usará en el sistema de correo )</small></label>
                            <input type="email" class="form-control" name="reply_to" value="<?=$data['reply_to']?>" placeholder='Desde ID de correo electrónico'/>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div id="result"></div>
                    <div class="box-footer">
                        <input type="submit" id="btn_update" class="btn-primary btn" value="Actualizar" name="btn_update"/>
                        <!-- <input type="submit" class="btn-danger btn" value="Cancel" name="btn_cancel"/> -->
                    </div>
                </form>
                <?php } else { ?>
                <div class="alert alert-danger">No tienes permiso para ver la configuracións</div>
                <?php } ?>
            </div>
            <!-- /.box -->
        </div>
        
    </div>
</section>
<div class="separator"> </div>
 <?php function getTimezoneOptions(){
                $list = DateTimeZone::listAbbreviations();
                $idents = DateTimeZone::listIdentifiers();
                
                    $data = $offset = $added = array();
                    foreach ($list as $abbr => $info) {
                        foreach ($info as $zone) {
                            if ( ! empty($zone['timezone_id'])
                                AND
                                ! in_array($zone['timezone_id'], $added)
                                AND 
                                  in_array($zone['timezone_id'], $idents)) {
                                $z = new DateTimeZone($zone['timezone_id']);
                                $c = new DateTime(null, $z);
                                $zone['time'] = $c->format('H:i a');
                                $offset[] = $zone['offset'] = $z->getOffset($c);
                                $data[] = $zone;
                                $added[] = $zone['timezone_id'];
                            }
                        }
                    }
                
                    array_multisort($offset, SORT_ASC, $data);
                    /*$options = array();
                    foreach ($data as $key => $row) {
                        $options[$row['timezone_id']] = $row['time'] . ' - '
                            . formatOffset($row['offset']). ' ' . $row['timezone_id'];
                    }*/
                    $i = 0;$temp = array();
                    foreach ($data as $key => $row) {
                        $temp[0] = $row['time'];
                        $temp[1] = formatOffset($row['offset']);
                        $temp[2] = $row['timezone_id'];
                        $options[$i++] = $temp;
                    }
                    
                    // echo "<pre>";
                    // print_r($options);
                    return $options;
            }
             function formatOffset($offset) {
                $hours = $offset / 3600;
                $remainder = $offset % 3600;
                $sign = $hours > 0 ? '+' : '-';
                $hour = (int) abs($hours);
                $minutes = (int) abs($remainder / 60);
            
                if ($hour == 0 AND $minutes == 0) {
                    $sign = ' ';
                }
                return $sign . str_pad($hour, 2, '0', STR_PAD_LEFT).':'. str_pad($minutes,2, '0');
            }
            ?>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>
            <script>
                 $('#system_timezone').on('change',function(e){
                gmt = $(this).find(':selected').data('gmt');
                $('#system_timezone_gmt').val(gmt);
                
            });
            
            $('#system_configurations_form').validate({
            	rules:{
				currency:"required",
				}
            });

            $('#system_configurations_form').on('submit',function(e){
                e.preventDefault();
                var formData = new FormData(this);
                if($("#system_configurations_form").validate().form()){
                    $.ajax({
                    type:'POST',
                    url:'public/db-operation.php',
                    data:formData,
                    beforeSend:function(){$('#btn_update').html('Por favor espera..');},
                    cache:false,
                    contentType: false,
                    processData: false,
                    success:function(result){
                        $('#result').html(result);
                        $('#result').show().delay(5000).fadeOut();
                        $('#btn_update').html('Guardar ajustes');
                        // $('#system_configurations_form')[0].reset();
                        // location.reload();
                    }
                    });
                }
            }); 
            </script>
            
            
           
            <script>
                var changeCheckbox = document.querySelector('#version-system-button');
                var init = new Switchery(changeCheckbox);
                changeCheckbox.onchange = function() {
                if ($(this).is(':checked')) {
                    $('#is-version-system-on').val(1);
                }else{
                		$('#is-version-system-on').val(0);
                	}
                };
                var changeCheckbox = document.querySelector('#refer-earn-system-button');
                var init = new Switchery(changeCheckbox);
                changeCheckbox.onchange = function() {
                    if ($(this).is(':checked')) {
                    $('#is-refer-earn-on').val(1);
                }else{
                		$('#is-refer-earn-on').val(0);
                	}
                };
    
            </script>