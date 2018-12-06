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
  <link rel="stylesheet" href="<?=LAYOUT?>plugins/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="<?=LAYOUT?>plugins/datepicker/datepicker3.css">
  <link rel="stylesheet" href="<?=LAYOUT?>plugins/iCheck/all.css">
  <link rel="stylesheet" href="<?=LAYOUT?>plugins/colorpicker/bootstrap-colorpicker.min.css">
  <link rel="stylesheet" href="<?=LAYOUT?>plugins/timepicker/bootstrap-timepicker.min.css">
  <link rel="stylesheet" href="<?=LAYOUT?>plugins/select2/select2.min.css">
  <link rel="stylesheet" href="<?=LAYOUT?>dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="<?=LAYOUT?>dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="<?=LAYOUT?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  <link rel="stylesheet" href="<?=LAYOUT?>css/css.css">

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
        	



        	<div class="nav-tabs-custom">

  				<ul class="nav nav-tabs">
  					
  					<li <?php if($aba_selecionada == "dados"){ echo "class='active'"; } ?> >
  						<a href="#dados" data-toggle="tab">Dados</a>
  					</li>

  				</ul>

  				<div class="tab-content" >

  					<div id="dados" class="tab-pane <?php if($aba_selecionada == "dados"){ echo "active"; } ?>" >
  					<form action="<?=$_base['objeto']?>novo_grv" class="form-horizontal" method="post">

  						<fieldset>
  							
                <div class="form-group">
  								<label class="col-md-12" >Titulo</label>
  								<div class="col-md-8">
  									<input name="titulo" type="text" class="form-control" >
  								</div>
  							</div>

                <div class="form-group">
                  <label class="col-md-12">Vizibilidade</label>
                  <div class="col-md-6">
                    <select name="esconder" class="form-control select2" style="width: 100%;" >
                      <option value='0' selected >Mostrar do Site</option>
                      <option value='1' >Esconder do Site</option>
                    </select>
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="col-md-12">Grupo/Categoria</label>
                  <div class="col-md-6">
                    <select name="grupo" class="form-control select2" style="width: 100%;" >
                    <option value='' selected >Selecione</option>
                    <?php
                      foreach ($grupos as $key => $value) {
                        
                        echo "<option value='".$value['codigo']."' >".$value['titulo']."</option>";
                        
                      }
                    ?>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-12">Prévia</label>
                  <div class="col-md-6">
                    <textarea class="form-control" name="previa" rows="4" ></textarea>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-12" >Valor R$</label>
                  <div class="col-md-3">
                    <input name="valor" id="valor_custo" type="text" class="form-control" value="0" onkeypress="Mascara(this,MaskMonetario)" onKeyDown="Mascara(this,MaskMonetario)" >
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="col-md-12">Texto para Envio (se este campo estiver vazio o produto não sera entregue automaticamente)</label>
                  <div class="col-md-6">
                    <textarea class="form-control" name="arquivo_link" rows="5" ></textarea>
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="col-md-12">Descrição</label>
                  <div class="col-md-12">
                    <textarea class="ckeditor" id="conteudo" name="descricao" rows="10" ></textarea>
                  </div>
                </div>

  						</fieldset>

              <div>
                <button type="submit" class="btn btn-primary">Salvar</button>
                <button type="button" class="btn btn-default" onClick="window.location='<?=$_base['objeto']?>';" >Voltar</button>
  						</div>
  						
  				  </form>
  				  </div>

        </div>

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

<script src="<?=LAYOUT?>plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="<?=LAYOUT?>bootstrap/js/bootstrap.min.js"></script>
<script src="<?=LAYOUT?>plugins/select2/select2.full.min.js"></script>
<script src="<?=LAYOUT?>plugins/input-mask/jquery.inputmask.js"></script>
<script src="<?=LAYOUT?>plugins/input-mask/jquery.inputmask.extensions.js"></script>
<script src="<?=LAYOUT?>plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="<?=LAYOUT?>plugins/iCheck/icheck.min.js"></script>
<script src="<?=LAYOUT?>plugins/fastclick/fastclick.js"></script>
<script src="<?=LAYOUT?>ckeditor412/ckeditor.js"></script>
<script src="<?=LAYOUT?>dist/js/app.min.js"></script>
<script src="<?=LAYOUT?>dist/js/demo.js"></script>

<script>function dominio(){ return '<?=DOMINIO?>'; }</script>
<script src="<?=LAYOUT?>js/funcoes.js"></script>

<script>

$(function(){
  $(".select2").select2();  
});
 

</script>

</body>
</html>