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
    <h1>Repartidor /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
   
</section>

<section class="content">
  
    <div class="row">
        <div class="col-md-6">
            <?php if($permissions['delivery_boys']['create']==0){?>
                <div class="alert alert-danger">No tienes permiso para crear repartidor</div>
            <?php } ?>
            
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Añadir repartidor</h3>

                </div>
              
                <form  method="post" id="add_form" action="public/db-operation.php">
                    <input type="hidden" id="add_delivery_boy" name="add_delivery_boy" required="" value="1" aria-required="true">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="">Nombre</label>
                      <input type="text" class="form-control"  name="name">
                    </div>
                              <div class="form-group">
                      <label for="">Telefono</label>
                      <input type="text" class="form-control"  name="mobile">
                    </div>
                    <div class="form-group">
                      <label for="">Contraseña</label>
                      <input type="password" class="form-control"  name="password" id="password">
                    </div>
                    <div class="form-group">
                      <label for="">Confirmar contraseña</label>
                      <input type="password" class="form-control"  name="confirm_password">
                    </div>
                      <label for="">Direccion</label>
                    <div class="form-group">
                      
                      <textarea name="address" id="address" style=" min-width:500px; max-width:100%;min-height:100px;height:100%;width:100%;"></textarea>
                    </div>
                    <div class="form-group">
                      <label for="">Bonos (%)</label>
                      <input type="number" class="form-control"  name="bonus" id="bonus" value="<?=$config['delivery-boy-bonus-percentage']?>">
                    </div>
                    
                    
                  </div>
                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary" id="submit_btn" name="btnAdd">Agregar</button>
                    <input type="reset" class="btn-warning btn" value="Limpiar"/>
                
                  </div>
                  <div class="form-group">
                      
                      <div id="result" style="display: none;"></div>
                    </div>
                </form>
              </div>
             </div>
        <!-- Left col -->
        <div class="col-xs-6">
            <?php if($permissions['delivery_boys']['read']==1){?>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Repartidores</h3>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-hover" data-toggle="table" id="delivery-boys"
                        data-url="api-firebase/get-bootstrap-table-data.php?table=delivery-boys"
                        data-page-list="[5, 10, 20, 50, 100, 200]"
                        data-show-refresh="true" data-show-columns="true"
                        data-side-pagination="server" data-pagination="true"
                        data-search="true" data-trim-on-search="false"
                        data-sort-name="id" data-sort-order="desc">
                        <thead>
                        <tr>
                            <th data-field="id" data-sortable="true">ID</th>
                            <th data-field="name" data-sortable="true">Nombre</th>
                            <th data-field="mobile" data-sortable="true">Telefono</th>
                            <th data-field="address" data-sortable="true">Direccion</th>
                            <th data-field="bonus" data-sortable="true">Bonos(%)</th>
                            <th data-field="balance" data-sortable="true">Saldo</th>
                            <th data-field="status">Estado</th>
                            <th data-field="operate" data-events="actionEvents">Accion</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <?php } else { ?>
                <div class="alert alert-danger">No tienes permiso para ver repartidores</div>
            <?php }?>
        </div>
        <div class="separator"> </div>
    </div>
    <div class="modal fade" id='editDeliveryBoyModal' tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Editar repartidor</h4>
                        </div>
                        
                        <div class="modal-body">
                            <?php if($permissions['delivery_boys']['update']==0){?>
                                <div class="alert alert-danger">No tienes permiso para actualizar repartidor</div>
                            <?php } ?>
                            <div class="box-body">
                            <form id="update_form"  method="POST" action ="public/db-operation.php" data-parsley-validate class="form-horizontal form-label-left">
                                <input type='hidden' name="delivery_boy_id" id="delivery_boy_id" value=''/>
                                <input type='hidden' name="update_delivery_boy" id="update_delivery_boy" value='1'/>
                                <!-- <input type='hidden' name="image_url" id="image_url" value=''/> -->
                                    
                                    
                                                <div class="form-group">
                                                    <label class="" for="">Nombre</label>
                                                    <input type="text" id="update_name" name="update_name" class="form-control col-md-7 col-xs-12">
                                                </div>
                                                <div class="form-group">
                                                    <label class="" for="">Telefono</label>
                                                    <input type="text" id="update_mobile" name="update_mobile" class="form-control col-md-7 col-xs-12" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label class="" for="">Contraseña</label><small>( Déjalo en blanco sin cambios )</small>
                                                    <input type="password" id="update_password" name="update_password" class="form-control col-md-7 col-xs-12">
                                                </div>
                                                <div class="form-group">
                                                    <label class="" for="">Confirmar creacion</label>
                                                    <input type="password" id="confirm_password" name="confirm_password" class="form-control col-md-7 col-xs-12">
                                                </div>
                                                <div class="form-group">
                                                    <label class="" for="">Direccion</label>
                                                    <textarea name="update_address" id="update_address" style=" min-width:500px; max-width:100%;min-height:100px;height:100%;width:100%;"></textarea>
                                                </div>
                                                <div class="form-group">
                                                   <label for="">Bonus (%)</label>
                                                   <input type="number" class="form-control"  name="update_bonus" id="update_bonus">
                                                </div>
                                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Estado</label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div id="status" class="btn-group" >
                                            <label class="btn btn-default" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                            <input type="radio" name="status" value="0">  Desactive 
                                            </label>
                                            <label class="btn btn-primary" data-toggle-class="btn-primary" data-toggle-passive-class="btn-default">
                                            <input type="radio" name="status" value="1"> Activo
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="id" name="id">
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                        <button type="submit" id="update_btn" class="btn btn-success">Actualizar</button>
                                    </div>
                                </div>
                                <div class="form-group">
                      
                                    <div class="row"><div  class="col-md-offset-3 col-md-8" style ="display:none;" id="update_result"></div></div>
                                </div>
                            </form>
                        </div>
                            
                        </div>
                    </div>
                </div>
            </div>
                        <div class="modal fade" id='fundTransferModal' tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Fondo de transferencia</h4>
                        </div>
                        <div class="modal-body">
                            <?php if($permissions['delivery_boys']['update']==0){?>
                                <div class="alert alert-danger">No tienes permiso para actualizar repartidor</div>
                            <?php } ?>
                            <form id="transfer_form"  method="POST" action ="public/db-operation.php" data-parsley-validate class="form-horizontal form-label-left">
                                <input type='hidden' name="boy_id" id="boy_id" value=''/>
                                <input type='hidden' name="transfer_fund" id="transfer_fund" value='1'/>
                                        <div class="form-group">
                                    <!-- <label class="control-label col-md-1 col-sm-3 col-xs-12" for="name">Name</label> -->
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <label>Nombre</label><input type="text" name="delivery_boy_name" id="delivery_boy_name" class="form-control" readonly>
                                    </div>
                                    <!-- <label class="control-label col-md-1 col-sm-3 col-xs-12" for="email">Email</label> -->
                                        <!-- <label class="control-label col-md-1 col-sm-3 col-xs-12" for="mobile">Mobile</label> -->
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <label>Telefono</label><input type="text" name="delivery_boy_mobile" id="delivery_boy_mobile" class="form-control" readonly>
                                    </div>
                                    
                                    
                                </div>
                                  <!-- <div class="form-group">
                                                    <label class="" for="">Address</label>
                                                    <textarea name="delivery_boy_address" id="delivery_boy_address" style=" min-width:10px; max-width:100%;min-height:100px;height:100%;width:100%;"></textarea>
                                                </div> -->
                                <div class="form-group">
                                    <!-- <label class="control-label col-md-1 col-sm-3 col-xs-12" for="account holder">A/C Holder</label> -->
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <label>Saldo</label><input type="text" name="delivery_boy_balance" id="delivery_boy_balance" class="form-control" readonly>
                                    </div>
                                    <!-- <label class="control-label col-md-1 col-sm-3 col-xs-12" for="account number">A/C Number</label> -->
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <label>Monto de la transferencia</label><input type="text" name="amount" id="amount" class="form-control" onkeyup="validate_amount(this.value);">
                                    </div>
                                    <div class="col-md-12 col-sm-6 col-xs-12">
                                        <label>Mensaje</label><input type="text" name="message" id="message" class="form-control">
                                    </div>
                                        <!-- <label class="control-label col-md-1 col-sm-3 col-xs-12" for="ifsc code">IFSC code</label> -->
                                       
                                    
                                    
                                </div>
                                                

                                <div class="ln_solid"></div>
                                <div class="form-group">
                                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                        <button type="submit" id="submit_button" class="btn btn-success">Enviar</button>
                                    </div>
                                </div>
                            </form>
                            <div class="row"><div  class="col-md-offset-3 col-md-8" style ="display:none;" id="transfer_result"></div></div>
                        </div>
                    </div>
                </div>
            </div>
</section>
        <script>
            $('#transfer_form').validate({
                rules:{
                    amount:"required",
                }
            });
        </script>
                <script>
            $('#transfer_form').on('submit',function(e){
                e.preventDefault();
                var formData = new FormData(this);
                if($("#transfer_form").validate().form()){
                    $.ajax({
                    type:'POST',
                    url: $(this).attr('action'),
                    data:formData,
                    beforeSend:function(){$('#submit_button').html('Por favor espera..');},
                    cache:false,
                    contentType: false,
                    processData: false,
                    success:function(result){

                        $('#transfer_result').html(result);
                        $('#transfer_result').show().delay(3000).fadeOut();
                        $('#submit_button').html('Enviar');
                        $('#amount').val('');
                        $('#delivery-boys').bootstrapTable('refresh');
                        setTimeout(function() {$('#fundTransferModal').modal('hide');}, 3000);
                    }
                    });
                }
            }); 
        </script>
        <script>
        $(document).on('click','.transfer-fund',function(){
                    id = $(this).data("id");
                    name = $(this).data("name");
                    mobile = $(this).data("mobile");
                    address = $(this).data("address");
                    balance = $(this).data("balance");

                    $('#boy_id').val(id);
                    $('#delivery_boy_name').val(name);
                    // alert(row.city_id);
                    $('#delivery_boy_mobile').val(mobile);
                    $('#delivery_boy_address').val(address);    
                    $('#delivery_boy_balance').val(balance);    

        });
        </script>
                <script>
           function validate_amount(){
            var balance=$('#delivery_boy_balance').val();
            var amount=$('#amount').val();
            if(parseInt(balance)>0){
                if(parseInt(amount) > parseInt(balance)){   
                    alert('No puede ingresar una cantidad mayor que el saldo.');
                    $('#amount').val('');

                }
            }else{
                alert('El saldo debe ser mayor que cero.');
                    $('#amount').val('');
            }
            if(parseInt(amount)<=0){
                alert('La cantidad debe ser mayor que cero.');
                $('#amount').val('');
            }
            
           }
        </script>
  <script>
      $('#add_form').validate({
        rules:{
        name:"required",
        mobile:"required",
        password:"required",
        address:"required",
        confirm_password : {
                    required:true,
                    equalTo : "#password"
                }
        }
      });
  </script>
    <script>
      $('#update_form').validate({
        rules:{
        update_name:"required",
        update_mobile:"required",
        update_address:"required",
        confirm_password : {
                    equalTo : "#update_password"
                }
        }
      });
  </script>
    <script>
      $('#add_form').on('submit',function(e){
        e.preventDefault();
        var formData = new FormData(this);
      if( $("#add_form").validate().form() ){
            if(confirm('¿Estás seguro? ¿Quieres agregar repartidor?')){
            $.ajax({
            type:'POST',
            url: $(this).attr('action'),
            data:formData,
            beforeSend:function(){$('#submit_btn').html('Por favor espera..');},
            cache:false,
            contentType: false,
            processData: false,
            success:function(result){
                $('#result').html(result);
                $('#result').show().delay(6000).fadeOut();
                $('#submit_btn').html('Enviar');
                $('#add_form')[0].reset();
                $('#delivery-boys').bootstrapTable('refresh');
                // $('#area_tp_form').find(':input').each(function(){
                //      $('#area_tp').val('');
                // });
                // $('#area_tp_list').bootstrapTable('refresh');
            }
            });
            }
              }
        }); 
  </script>
  <script>
      $('#update_form').on('submit',function(e){
        e.preventDefault();
        var formData = new FormData(this);
      if( $("#update_form").validate().form() ){
            //if(confirm('Are you sure?Want to Update Delivery Boy')){
            $.ajax({
            type:'POST',
            url: $(this).attr('action'),
            data:formData,
            beforeSend:function(){$('#update_btn').html('Por favor espera...');},
            cache:false,
            contentType: false,
            processData: false,
            success:function(result){
                $('#update_result').html(result);
                $('#update_result').show().delay(6000).fadeOut();
                $('#update_btn').html('Actualizar');
                $('#update_form')[0].reset();
                $('#delivery-boys').bootstrapTable('refresh');
                setTimeout(function() {$('#editDeliveryBoyModal').modal('hide');}, 3000);
                // $('#area_tp_form').find(':input').each(function(){
                //      $('#area_tp').val('');
                // });
                // $('#area_tp_list').bootstrapTable('refresh');
            }
            });
            //}
              }
        }); 
  </script>
  <script>
    window.actionEvents = {
        'click .edit-delivery-boy': function (e, value, row, index) {
            //alert('You click remove icon, row: ' + JSON.stringify(row));
            $("input[name=status][value=1]").prop('checked', true);
            if($(row.status).text() == 'Deactive')
                $("input[name=status][value=0]").prop('checked', true);
            $('#delivery_boy_id').val(row.id);
            $('#update_name').val(row.name);
            // alert(row.city_id);
            $('#update_mobile').val(row.mobile);
            $('#update_address').val(row.address);
            $('#update_bonus').val(row.bonus);
        }
    }
</script>
 <script>
     $(document).on('click','.delete-delivery-boy',function(){
            if(confirm('¿Estás seguro? Quiere eliminar repartidor.')){
                
                id = $(this).data("id");
            
                // image = $(this).data("image");
                $.ajax({
                    url : 'public/db-operation.php',
                    type: "get",
                    data: 'id='+id+'&delete_delivery_boy=1',
                    success: function(result){
                        if(result==0){
                            $('#delivery-boys').bootstrapTable('refresh');
                        }
                        if(result==2){
                            alert('No tienes permiso para eliminar repartidor');
                        }
                        if(result==1){
                            alert('¡Error! El repartidor no se pudo eliminar.');
                        }
                        if(result==3){
                            alert('No puedes eliminar a este repartidor.');
                        }
                        
                    }
                });
            }
        });
  </script>

