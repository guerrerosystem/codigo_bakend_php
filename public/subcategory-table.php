<?php
    include_once('includes/functions.php'); 
    ?>
<section class="content-header">
    <h1>Subcategorías /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
    <ol class="breadcrumb">
        <a class="btn btn-block btn-default" href="agregar-subcategoria.php"><i class="fa fa-plus-square"></i> Agregar nueva subcategoría</a>
    </ol>
</section>
<?php
    if($permissions['subcategories']['read']==1) { 
?>

<section class="content">
    
    <div class="row">
        <!-- Left col -->
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                        <h3 class="box-title">Subcategorías</h3>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-hover" data-toggle="table" 
						data-url="api-firebase/get-bootstrap-table-data.php?table=subcategory"
						data-page-list="[5, 10, 20, 50, 100, 200]"
						data-show-refresh="true" data-show-columns="true"
						data-side-pagination="server" data-pagination="true"
						data-search="true" data-trim-on-search="false"
						data-sort-name="id" data-sort-order="desc">
                        <thead>
                        <tr>
                            <th data-field="id" data-sortable="true">ID</th>
                            <th data-field="name" data-sortable="true">Nombre</th>
                            <th data-field="category_name" data-sortable="true">categoria principal</th>
                            <th data-field="subtitle" data-sortable="true">Subtitulo</th>
                            <th data-field="image">Imagen</th>
                            <th data-field="operate">Accion</th>
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
<div class="alert alert-danger topmargin-sm" style="margin-top: 20px;">No tiene permiso para ver subcategorías.</div>
<?php } ?>
