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
  <link rel="stylesheet" href="<?=LAYOUT?>api/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />
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
  <link rel="stylesheet" href="<?=LAYOUT?>api/bootstrap-fileupload/bootstrap-fileupload.min.css"/>
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
            <li <?php if($aba_selecionada == "imagem"){ echo "class='active'"; } ?> onClick="carrega_envio_imagens();" >
              <a href="#imagem" data-toggle="tab">Imagens</a>
            </li>
            <li <?php if($aba_selecionada == "anuncios"){ echo "class='active'"; } ?> onClick="carrega_envio_imagens();" >
              <a href="#anuncios" data-toggle="tab">Anuncios</a>
            </li>

  				</ul>

  				<div class="tab-content" >

  					<div id="dados" class="tab-pane <?php if($aba_selecionada == "dados"){ echo "active"; } ?>" >
  					<form action="<?=$_base['objeto']?>alterar_grv" class="form-horizontal" method="post">  						

  						<fieldset>

                <div class="form-group">
                  <label class="col-md-12" >Ref: <?=$data->id?></label>
                </div>
                
                <div class="form-group">
                  <label class="col-md-12" >Titulo</label>
                  <div class="col-md-6">
                    <input name="titulo" type="text" class="form-control" value="<?=$data->titulo?>" onkeypress="gera_url(this.value)" onKeyDown="gera_url(this.value)" >
                  </div>
                </div>                
                
                <div class="form-group">
                  <label class="col-md-12">Vizibilidade</label>
                  <div class="col-md-6">
                    <select name="esconder" class="form-control select2" style="width: 100%;" >
                      <option value='0' <?php if($data->esconder == 0){ echo "selected"; } ?> >Mostrar do Site</option>
                      <option value='1' <?php if($data->esconder == 1){ echo "selected"; } ?> >Esconder do Site</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-12">Grupo/Categoria</label>
                  <div class="col-md-6">
                    <select name="grupo" class="form-control select2" style="width: 100%;" >
                    <?php
                      foreach ($grupos as $key => $value) {
                        
                        if($value['selected']){
                          $selected = "selected";
                        } else {
                          $selected = "";
                        }

                        echo "<option value='".$value['codigo']."' $selected >".$value['titulo']."</option>";
                        
                      }
                    ?>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-12">Prévia</label>
                  <div class="col-md-6">
                    <textarea class="form-control" name="previa" rows="4" ><?=$data->previa?></textarea>
                  </div>
                </div> 

                <div class="form-group">
                  <label class="col-md-12" >Valor R$</label>
                  <div class="col-md-3">
                    <input name="valor" id="valor_custo" type="text" class="form-control" value="<?=$valor?>" onkeypress="Mascara(this,MaskMonetario)" onKeyDown="Mascara(this,MaskMonetario)" >
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-12">Texto para Envio (se este campo estiver vazio o produto não sera entregue automaticamente)</label>
                  <div class="col-md-6">
                    <textarea class="form-control" name="arquivo_link" rows="5" ><?=$data->arquivo_link?></textarea>
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="col-md-12" >Vídeo YouTube</label>
                  <div class="col-md-6">
                    <input name="youtube" type="text" class="form-control" value="<?=$data->youtube?>" >
                  </div>
                </div>
                
                <div class="form-group">  
                  <label class="col-md-12">Descrição</label>
                  <div class="col-md-12">
                    <textarea class="ckeditor" id="conteudo" name="descricao" rows="10" ><?=$data->descricao?></textarea>
                  </div>
                </div>
                
              </fieldset>
              
              <div>
                <button type="submit" class="btn btn-primary">Salvar</button>
                <input type="hidden" name="codigo" value="<?=$data->codigo?>" >
                <button type="button" class="btn btn-default" onClick="window.location='<?=$_base['objeto']?>inicial/';" >Voltar</button>
  						</div>
  						
  				  </form>
  				  </div>
            
            <div id="imagem" class="tab-pane <?php if($aba_selecionada == "imagem"){ echo "active"; } ?>" >
              
              <button type="button" class="btn btn-primary" onClick="modal('<?=$_base['objeto']?>upload/codigo/<?=$data->codigo?>', 'Enviar Imagens');" >Carregar Imagens</button>

              <hr>

              <div style="text-align:center;">
                <ul id="sortable_imagem" >
                <?php

                  $n = 0;
                  foreach ($imagens as $key => $value) {
                    
                    echo "
                    <li id='item_".$value['id']."' >
                      
                      <div class='quadro_img' style='background-image:url(".$value['imagem_p']."); '></div>
                      <div style='padding-top:5px; text-align:center;'>
                        <button class='btn btn-default fa fa-times-circle' onClick=\"confirma_apagar('".$_base['objeto']."apagar_imagem/codigo/$data->codigo/id/".$value['id']."');\" title='Remover imagem' ></button>
                        <button class='btn btn-default fa fa-external-link' onClick=\"window.open('".$value['imagem_g']."', '_blank');\" title='Abrir' ></button>
                      </div>
                      
                    </li>
                    ";

                  $n++;
                  }

                ?>
                </ul>
              </div>

              <?php if($n == 0){ ?>
                
                <div style="text-align:center; padding-top:100px; padding-bottom:100px;">Nenhuma imagem adicionada!</div>
                
              <?php } ?>
              
              <div>
                <button type="button" class="btn btn-default" onClick="window.location='<?=$_base['objeto']?>inicial';" >Voltar</button>
              </div>
              
            </div>
            
            
            <div id="anuncios" class="tab-pane <?php if($aba_selecionada == "anuncios"){ echo "active"; } ?>" >
              
              <button type="button" class="btn btn-primary" onClick="modal('<?=$_base['objeto']?>novo_anuncio/codigo/<?=$data->codigo?>', 'Novo Anuncio');" >Novo Anuncio</button>

              <button type="button" class="btn btn-default" onClick="window.open('<?=$_base['objeto']?>template_ml/codigo/<?=$data->codigo?>', '_blank');" >Visualizar na Template ML</button>
                
              <hr>

              <?php
              
                foreach ($anuncios as $key => $value) {
                  

                  if($value['atualizado'] == 1){
                    $botao_sincronizar = "";
                  } else {

                    if($value['id_ml']){

                      $botao_sincronizar = "<button type='button' class='btn btn-primary' onClick=\"window.location='".DOMINIO."mercadolivre/alterar_anuncio/codigo/".$value['codigo']."';\" >Atualizar Anuncio do MercadoLivre</button>";

                    } else {
                      
                      $botao_sincronizar = "<button type='button' class='btn btn-primary' onClick=\"window.location='".DOMINIO."mercadolivre/criar_anuncio/codigo/".$value['codigo']."';\" >Criar Anuncio no MercadoLivre</button>";
                      
                    }

                  }

                  echo "
                  <div>
                  <form action='".$_base['objeto']."alterar_anuncio_ml/codigo/".$value['codigo']."/produto/".$data->codigo."' method='post' >

                  <fieldset>
                    
                    <div class='form-group'>
                      <label class='col-md-12' >Titulo / Categoria</label>                     
                      <div class='col-md-6'>
                        <input name='titulo' type='text' class='form-control' size=60 maxlength=60 value='".$value['titulo']."'>
                      </div>
                      <div class='col-md-6'>
                        <input name='categoria' type='text' class='form-control' value='".$value['categoria']." - ".$value['categoria_titulo']."' disabled >
                      </div>
                    </div>
                    
                    <div style='clear:both'></div>

                    <div class='form-group' style='padding-top:10px;' >
                      <label class='col-md-12' >ID / Endereço do Anuncio</label>
                      <div class='col-md-2'>
                        <input name='idanuncio_naoalteravel' type='text' class='form-control' size=60 maxlength=60 value='".$value['id_ml']."' >
                      </div>
                      <div class='col-md-10'>
                        <input name='endereco_naoalteravel' type='text' class='form-control' value='".$value['endereco']."' >
                      </div>
                    </div>
                    
                    <div style='clear:both'></div>

                    <div class='form-group' style='padding-top:10px;' >
                      <label class='col-md-12' >Imagens</label>
                      <div class='col-md-12'>

                        <div id='ordem_".$value['codigo']."' class='ordem_img_anuncios' >
                    ";

                    foreach ($value['imagens'] as $key2 => $value_img) {
                      
                      echo "
                      <li id='item_".$value_img['id']."' >
                        <div class='quadro_img' style='background-image:url(".$value_img['imagem']."); '></div>                         
                      </li>
                      ";

                    }


                    echo "
                        </div>

                      </div>
                    </div>

                    <div class='form-group'>
                      <div class='col-md-6'>
                        <div style='text-align:left; padding-top:15px;'>
                          <button type='submit' class='btn btn-primary'>Salvar</button>
                        </div>
                      </div>
                      <div class='col-md-6'>
                        <div style='text-align:right; padding-top:15px;'>
                          $botao_sincronizar
                          <button type='button' class='btn btn-default' onClick=\"confirma_apagar('".$_base['objeto']."remover_anuncio/codigo/".$value['codigo']."/produto/".$data->codigo."');\" >Remover Anuncio</button>
                        </div>
                      </div>
                    </div>

                  </fieldset>

                  </form>
                  </div>
                  <hr>
                  ";

                }

              ?>

              <div>
                <button type="button" class="btn btn-default" onClick="window.location='<?=$_base['objeto']?>inicial';" >Voltar</button>
              </div>

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
<script src="<?=LAYOUT?>plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="<?=LAYOUT?>plugins/input-mask/jquery.inputmask.extensions.js"></script>
<script src="<?=LAYOUT?>plugins/timepicker/bootstrap-timepicker.min.js"></script>
<script src="<?=LAYOUT?>plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="<?=LAYOUT?>plugins/iCheck/icheck.min.js"></script>
<script src="<?=LAYOUT?>plugins/fastclick/fastclick.js"></script>
<script src="<?=LAYOUT?>ckeditor412/ckeditor.js"></script>
<script src="<?=LAYOUT?>api/jquery-ui/js/jquery-ui-1.10.4.custom.js"></script>
<script src="<?=LAYOUT?>api/bootstrap-fileupload/bootstrap-fileupload.min.js"></script>
<script src="<?=LAYOUT?>dist/js/app.min.js"></script>
<script src="<?=LAYOUT?>dist/js/demo.js"></script> 

<script>function dominio(){ return '<?=DOMINIO?>'; }</script>
<script src="<?=LAYOUT?>js/funcoes.js"></script>

<script>

$(document).ready(function() {
  
  $(".select2").select2();
  
  $( "#sortable_imagem" ).sortable({
        update: function(event, ui){
          var postData = $(this).sortable('serialize');
          console.log(postData);
          
          $.post('<?=$_base['objeto']?>ordenar_imagem', {list: postData, codigo: '<?=$data->codigo?>'}, function(o){
            console.log(o);
          }, 'json');
        }
  });


  <?php foreach ($anuncios as $key => $value) { ?>

    $( "#ordem_<?=$value['codigo']?>" ).sortable({
        update: function(event, ui){
            var postData = $(this).sortable('serialize');
            console.log(postData);
            
            $.post('<?=$_base['objeto']?>ordenar_imagem', {list: postData, codigo: '<?=$value['codigo']?>'}, function(o){
              console.log(o);
            }, 'json');
          }
    });

  <?php } ?>

});

function gera_url(string){
  string = removerAcento(string);
  string = '<?=$data->id?>-'+string.toLowerCase();
  $('#id_unico').val(string);
}

function removerAcento(palavra) {
  var palavraSemAcento = "";
  var caracterComAcento = "áàãâäéèêëíìîïóòõôöúùûüçÁÀÃÂÄÉÈÊËÍÌÎÏÓÒÕÖÔÚÙÛÜÇ ";
  var caracterSemAcento = "aaaaaeeeeiiiiooooouuuucAAAAAEEEEIIIIOOOOOUUUUC-";

  for (var i = 0; i < palavra.length; i++)
  {
  var char = palavra.substr(i, 1);
  var indexAcento = caracterComAcento.indexOf(char);
  if (indexAcento != -1) {
  palavraSemAcento += caracterSemAcento.substr(indexAcento, 1);
  } else {
  palavraSemAcento += char;
  }
  }

  return palavraSemAcento;
}

</script>

</body>
</html>