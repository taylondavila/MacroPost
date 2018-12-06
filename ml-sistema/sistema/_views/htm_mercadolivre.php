<?php require_once('_system/bloqueia_view.php'); ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" href="<?=FAVICON?>" type="image/x-icon" />
  <title><?=$_titulo?> - <?=TITULO_VIEW?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?=LAYOUT?>bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?=LAYOUT?>font-awesome-4.6.2/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?=LAYOUT?>plugins/datatables/dataTables.bootstrap.css">
  <link rel="stylesheet" href="<?=LAYOUT?>dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="<?=LAYOUT?>dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="<?=LAYOUT?>css/css.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

</head>
<body class="hold-transition skin-blue <?php if($_base['menu_fechado'] == 1){ echo "sidebar-collapse"; } ?> sidebar-mini">
<div class="wrapper">

  <?php require_once('htm_modal.php'); ?>

	<?php require_once('htm_topo.php'); ?>

	<?php require_once('htm_menu.php'); ?>

	<div class="content-wrapper">

	    <section class="content-header">
	      <h1>
	      <?=$_titulo?>
	      <small><?=$_subtitulo?></small>
	      </h1>
	    </section>

		<!-- Main content -->
    	<section class="content">
    	<div class="row">
        	
        	<div class="col-xs-12">
          <section class="panel">
          <form action="<?=$_base['objeto']?>config_grv" class="form-horizontal" method="post">

            <div class="panel-body">             

              <button type="button" class="btn btn-primary" onClick="modal('<?=DOMINIO?>mercadolivre/autorizacao.php', 'Autorização Mercado Livre', 'medio');">Autorização</button>
              
              <button type="button" class="btn btn-default" onClick="window.location='<?=$_base['objeto']?>categorias';">Gerenciar Categorias</button>
              
              <hr>

    					<fieldset>

                  <div class="form-group">
                    <label class="col-md-12" >App ID do Mercado Livre</label>
                    <div class="col-md-4">
                      <input name="app_id" type="text" class="form-control" value="<?=$data->app_id?>" >
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-md-12" >Secret Key do Mercado Livre</label>
                    <div class="col-md-4">
                      <input name="secret_key" type="text" class="form-control" value="<?=$data->secret_key?>" >
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-md-12" >Mensagem automática para vendas não qualificadas</label>
                    <div class="col-md-7">
                      <textarea name="nao_qualificados" class="form-control" style="height: 200px;" ><?=$data->nao_qualificados?></textarea>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-md-12" >Mensagem entrega automática de produtos (Adicione "%descricao%' para marcar a posição dos links do produto)</label>
                    <div class="col-md-7">
                      <textarea name="entrega_automatica" class="form-control" style="height: 200px;" ><?=$data->entrega_automatica?></textarea>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-md-12">Template para Mercado Livre (Adicione "%descricao%' para marcar a posição da descrição do produto na template)</label>
                    <div class="col-md-12">
                      <textarea class="ckeditor" id="template_ml" name="template" rows="10" ><?=$data->template?></textarea>
                    </div>
                  </div>

    					</fieldset>
    					
    					</div>
    					
    			    <div class="panel-footer">
    						<div class="row">
    							<div class="col-md-12">
    								<button type="submit" class="btn btn-primary">Salvar</button>
    							</div>
    						</div>
    					</div>

    				</form>
            
    			</section>
        	</div>

		</div>
		<!-- /.row -->
	</section>
    <!-- /.content -->

  </div>
  <!-- /.content-wrapper -->
  <?php require_once('htm_rodape.php'); ?>

</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="<?=LAYOUT?>plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="<?=LAYOUT?>bootstrap/js/bootstrap.min.js"></script>
<script src="<?=LAYOUT?>plugins/select2/select2.full.min.js"></script>
<script src="<?=LAYOUT?>plugins/input-mask/jquery.inputmask.js"></script>
<script src="<?=LAYOUT?>plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="<?=LAYOUT?>plugins/input-mask/jquery.inputmask.extensions.js"></script>
<script src="<?=LAYOUT?>plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="<?=LAYOUT?>plugins/fastclick/fastclick.js"></script>
<script src="<?=LAYOUT?>ckeditor412/ckeditor.js"></script>
<script src="<?=LAYOUT?>api/jquery-ui/js/jquery-ui-1.10.4.custom.js"></script>
<script src="<?=LAYOUT?>dist/js/app.min.js"></script>
<script src="<?=LAYOUT?>dist/js/demo.js"></script>
 
<script>function dominio(){ return '<?=DOMINIO?>'; }</script>
<script src="<?=LAYOUT?>js/funcoes.js"></script>

</body>
</html>