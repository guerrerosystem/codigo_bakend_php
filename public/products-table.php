<section class="content-header">
    <h1>
        Productos /
        <small><a href="home.php"><i class="fa fa-home"></i> Home</a></small>
    </h1>
    <ol class="breadcrumb">
        <a class="btn btn-block btn-default" href="agregar-productos.php"><i class="fa fa-plus-square"></i> Agregar nuevo producto</a>
    </ol>
</section>
<?php
    if($permissions['products']['read']==1) { 
?>

<section class="content">
    
    <div class="row">
    
        <div class="col-xs-12">
            <div class="box">
               
                <div class="box-header">
                    <div class="col-md-3">
                       <h4 class="box-title">Filtrar por categoría de productos</h4>
                        <form method="post">
                            <select id="category_id" name="category_id" placeholder="selecciona una categoría" required class="form-control col-xs-3" style="width: 300px;">
                                <?php
                                    $Query="select name, id from category";
                                    $db->sql($Query);
                                    $result=$db->getResult();
                                    if($result)
                                    {
                                    ?>
                                <option value="">Todos los productos</option>
                                <?php foreach($result as $row){
                                    if($permissions['categories']['read']==1){
                                ?>
                                <option value='<?=$row['id']?>'><?=$row['name']?></option>
                                <?php }}}   
                                ?>
                            </select>
                        </form> 
                    </div>
                    

                </div>
                <?php //if($_SESSION['role']=='super admin') { ?>
                    <div class="box-header">
                    

                </div>
                
            <?php //} ?>
                <!-- </div> -->


                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id='products_table' class="table table-hover" data-toggle="table" 
                        data-url="api-firebase/get-bootstrap-table-data.php?table=products"
                        data-page-list="[5, 10, 20, 50, 100, 200]"
                        data-show-refresh="true" data-show-columns="true"
                        data-side-pagination="server" data-pagination="true"
                        data-search="true" data-trim-on-search="false"
                        data-filter-control="true" data-query-params="queryParams"
                        data-sort-name="id" data-sort-order="desc"
                        data-show-export="true"
                        data-export-types='["txt","excel"]'
                        data-export-options='{
                            "fileName": "products-list-<?=date('d-m-Y')?>",
                            "ignoreColumn": ["operate"] 
                        }'>
                        <thead>
                        <tr>
                            <th data-field="id" data-sortable="true">ID</th>
                            <th data-field="name" data-sortable="true">Nombre</th>
                            <th data-field="image">Imagen</th>
                            <th data-field="price" data-sortable="true">Precio</th> 
                            <th data-field="measurement" data-sortable="true">Medicion(Kg, gm, Ltr)</th> 
                            <th data-field="stock" data-sortable="true" >Stock</th>
                            <th data-field="serve_for" data-sortable="true">Disponibilidad</th>
                            <!-- <th data-field="discounted_price" data-sortable="true" >Discounted Price</th> -->
                            <!--<th data-field="category_id" data-sortable="true">Category ID</th>-->
                            <th data-field="operate">Accion</th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <div class="separator"> </div>
    </div>
    <!-- /.row (main row) -->
</section>
<?php } else { ?>
<div class="alert alert-danger topmargin-sm" style="margin-top: 20px;">No tienes permiso para ver productos.</div>
<?php } ?>

<script>
function queryParams(p){
    return {
        "category_id": $('#category_id').val(),
        limit:p.limit,
        sort:p.sort,
        order:p.order,
        offset:p.offset,
        search:p.search
    };
}
</script>
<script>
$('#category_id').on('change',function(){
    id = $('#category_id').val();
    $('#products_table').bootstrapTable('refresh');
});
</script>