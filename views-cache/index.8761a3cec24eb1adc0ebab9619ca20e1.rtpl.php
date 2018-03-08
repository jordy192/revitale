<?php if(!class_exists('Rain\Tpl')){exit;}?><!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Bem vindo !
    <small>Alguns relatórios</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="/login"><i class="fa fa-dashboard"></i> Site</a></li>
    <li class="active">Relatórios</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">

  <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <p>Produto mais Vendido</p>
              <?php $counter1=-1;  if( isset($report) && ( is_array($report) || $report instanceof Traversable ) && sizeof($report) ) foreach( $report as $key1 => $value1 ){ $counter1++; ?>
              <h3><sup style="font-size: 20px"><?php echo htmlspecialchars( $value1["desproduct"], ENT_COMPAT, 'UTF-8', FALSE ); ?></sup></h3>
              <?php } ?>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="/admin/pie-report" class="small-box-footer">Mais Informação <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <p>Maiores Compradores</p>
              <?php $counter1=-1;  if( isset($buyers) && ( is_array($buyers) || $buyers instanceof Traversable ) && sizeof($buyers) ) foreach( $buyers as $key1 => $value1 ){ $counter1++; ?>
              <h3><sup style="font-size: 20px"><?php echo htmlspecialchars( $value1["desperson"], ENT_COMPAT, 'UTF-8', FALSE ); ?></sup></h3>
              <?php } ?>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="/admin/buyers-report" class="small-box-footer">Mais Informação <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <p>Clientes Cadastrados</p>
              <?php $counter1=-1;  if( isset($count) && ( is_array($count) || $count instanceof Traversable ) && sizeof($count) ) foreach( $count as $key1 => $value1 ){ $counter1++; ?>
              <h3><sup style="font-size: 40px"><?php echo htmlspecialchars( $value1["nrqtd"], ENT_COMPAT, 'UTF-8', FALSE ); ?></sup></h3>
              <?php } ?>

            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="/admin/clients-report" class="small-box-footer">Mais Informação <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <p>Venda por Categoria</p>
              <?php $counter1=-1;  if( isset($category) && ( is_array($category) || $category instanceof Traversable ) && sizeof($category) ) foreach( $category as $key1 => $value1 ){ $counter1++; ?>
              <h3><sup style="font-size: 20px"><?php echo htmlspecialchars( $value1["descategory"], ENT_COMPAT, 'UTF-8', FALSE ); ?></sup></h3>
              <?php } ?>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="/admin/category-report" class="small-box-footer">Mais Informação <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>

</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->