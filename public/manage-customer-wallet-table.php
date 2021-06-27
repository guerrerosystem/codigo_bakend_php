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
    <h1>Administrar saldo de clientes /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
   
</section>
<?php if($permissions['customers']['read']==1){?>

<section class="content">
  
    <div class="row">
        <div class="col-md-6">
             
              <div class="box box-primary">
               
                <form  method="post" id="wallet_form" action="public/db-operation.php">
                    <input type="hidden" id="user_id" name="user_id" aria-required="true">
                    <input type="hidden" id="manage_customer_wallet" name="manage_customer_wallet" value="1" aria-required="true">
                  <div class="box-body">
                    <div class="form-group">
                      <label for="">Customer</label>
                      <input type="text" id="details" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                      <label for="">Seleccione tipo</label>
                      <select name="type" id="type" class="form-control">
                      <option value = ""> Seleccionar </option>
                         <option value = "credit"> Credito </option>
                         <option value = "débito"> Débito </option>
                          
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="">Cantidad</label>
                      <input type="text" class="form-control"  name="amount">
                    </div>
                    <label for="">Mensaje</label>
                    <div class="form-group">
                      
                      <textarea name="message" id="message" style=" min-width:500px; max-width:100%;min-height:100px;height:100%;width:100%;"></textarea>
                    </div>
                  </div><!-- /.box-body -->

                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary" id="submit_btn" name="btnAdd">Agregar</button>
                    <input type="reset" class="btn-warning btn" value="Limpiar"/>
                
                  </div>
                  <div class="form-group">
                      
                      <div id="result" style="display: none;"></div>
                    </div>
                </form>
              </div><!-- /.box -->
             </div>
        <!-- Left col -->
        <div class="col-xs-6">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Clientes</h3>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-hover" data-toggle="table" id="users"
                        data-toggle="table"
                        data-url="api-firebase/get-bootstrap-table-data.php?table=users"
                        data-click-to-select="true"
                        data-side-pagination="server"
                        data-pagination="true"
                        data-page-list="[5, 10, 20, 50, 100, 200]"
                        data-search="true" data-trim-on-search="false"
                        data-show-refresh="true" data-show-columns="true"
                        data-sort-name="id" data-sort-order="asc"
                        data-mobile-responsive="true"
                        data-toolbar="#toolbar" data-show-export="true"
                        data-maintain-selected="true"
                        data-export-types='["txt","excel"]'
                        data-export-options='{
                            "fileName": "users-list-<?=date('d-m-y')?>",
                            "ignoreColumn": ["state"]   
                        }'
                        >
                        <thead>
                        <tr>
                            <th data-field="state" data-radio="true"></th>
                            <th data-field="id" data-sortable="true">ID</th>
                            <th data-field="name" data-sortable="true">Nombre</th>
                            <th data-field="balance" data-sortable="true">saldo</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="separator"> </div>
    </div>
</section>
<?php } else { ?>
            <div class="alert alert-danger" style="margin-top: 20px;">No tiene permiso para administrar la saldo del cliente</div>
        <?php } ?>
<script>
    $('#wallet_form').validate({
    rules:{
    amount:"required",
    type:"required",
    }
    });
</script>
<script>
  $('#wallet_form').on('submit',function(e){
    e.preventDefault();
    var formData = new FormData(this);
  if( $("#wallet_form").validate().form() ){
    if($('#details').val() != ''){
        if(confirm('Estas seguro')){
            
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
            $('#wallet_form')[0].reset();
            $('#users').bootstrapTable('refresh');
        }
        });
        }

    }else{
        alert('Por favor seleccione al menos un usuario.');
        
    }
    }
    }); 
</script>
<script>
    $('#users').on('check.bs.table', function (e, row) {
    $('#details').val(row.id + " | " + row.name + " | " + row.email);
    $('#user_id').val(row.id);
});
</script>

