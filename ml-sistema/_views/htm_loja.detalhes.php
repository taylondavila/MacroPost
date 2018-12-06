<?php require_once('_system/bloqueia_view.php'); ?>
<!DOCTYPE html>
<html>
<head>

<meta http-equiv="Content-Type" charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?=$data->titulo?> - <?=$_base['titulo_pagina']?></title>
<link rel="shortcut icon" href="<?=$_base['imagem']['151213502131634']?>">

<meta name="description" content="<?=$data->previa?>" />
<meta property="og:description" content="<?=$data->previa?>">
<meta name="author" content="ComprePronto.com.br">
<meta name="classification" content="Website" />
<meta name="robots" content="index, follow">
<meta name="Indentifier-URL" content="<?=DOMINIO?>" />

<!--start Facebook Open Graph Protocol-->
<meta property="og:site_name" content="<?=$_base['titulo_pagina']?>" />
<meta property="og:title" content="<?=$data->titulo?> - <?=$_base['titulo_pagina']?>" />
<meta property="og:image" content="<?=$imagem_principal?>"/>
<meta property="og:url" content="<?=$endereco?>"/>
<!--end Facebook Open Graph Protocol-->

<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">

<link rel="stylesheet" href="<?=LAYOUT?>api/bootstrap/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?=LAYOUT?>api/font-awesome-4.6.2/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?=LAYOUT?>api/hover-master/css/hover-min.css" rel="stylesheet">
<link href="<?=LAYOUT?>css/animate.css" rel="stylesheet">
<link rel="stylesheet" href="<?=LAYOUT?>css/css.css" rel="stylesheet"> 

</head>
<body>

<?php // carrega o arquivo txt com o codigo do analytcs
echo analytics; ?>

<?php //carrega o topo selecionado no arquivo de configuração (config.php)
include_once('htm_topo.php');

//daqui em diante o conteudo da pagina
?>

<div id="corpo" >
<div style="position:relative; width:100%; background-color:#fff; padding-top:20px; margin-top:10px;">


	<div class="container">
		<div class="row">
			

			<div class="col-xs-12 col-sm-4 col-md-4" >
				<div class="loja_divisao_lateral" >
					<?php include_once('htm_loja.lateral.php'); ?>

					<div style="border-top: 1px solid #ddd; margin-top:40px; ">
					
					<div style="text-align:left; margin-top:40px; color: #990000; font-size:20px; font-weight:500">
						Valor: R$ <?=$valor?>
					</div>
					
					<?php if($endereco_ml){ ?>
					
					<div style="text-align:left; margin-top:30px;">
						<div onClick="window.open('<?=$endereco_ml?>', '_blank');" class='botao_comprar hvr-float-shadow' >Comprar pelo MercadoLivre</div>
					</div>
					
					<div style="text-align:left; margin-top:30px;">
						Após confirmação do pagamento em no máximo 10 minutos você receberá automaticamente o link para download dos arquivos.
					</div>
					
					<?php } else { ?> 
					
					<div style="text-align:left; margin-top:30px;">
						Venda indisponivel no momento
					</div>
					
					<?php } ?> 
					
					<div style="text-align: left; margin-top:30px; padding-bottom: 100px;" >
						<div onClick="history.go(-1)" class='botao_padrao hvr-float-shadow' >Voltar</div>
					</div>

				</div>

				</div>
			</div>

			<div class="col-xs-12 col-sm-8 col-md-8" >
				
				<h1 class='titulo_interno' ><?=$data->titulo?></h1>

				<div class='loja_lista_meta' >			
					<i class='fa fa-folder-open' ></i> <?=$categoria?>
				</div>

				<div class='blog_divisao_interna' ></div>

				<div style="font-size:15px; color:#000;" >
					<?=$data->descricao?>
				</div>

				<?php
				//lista imagens da postagem

					foreach ($imagens as $key => $value) {
						echo "<div class='loja_imagem_interna' ><img src='".$value['imagem_g']."' title='".$data->titulo."' ></div>";
					}

				?>
				
				<div class='blog_divisao_interna2' ></div>
				
				<div>
					
					<div style="text-align:center; margin-top:40px; color: #990000; font-size:20px; font-weight:500">
						Valor: R$ <?=$valor?>
					</div>
					
					<?php if($endereco_ml){ ?>
					<div style="text-align:center; margin-top:30px;">
					Parcele em até 12x no cartão com o<br><img src='<?=LAYOUT?>img/Mercado-Livre-logo.png' title='Mercado Livre'>
					</div>					
					
					<div style="text-align:center; margin-top:30px;">
						<div onClick="window.open('<?=$endereco_ml?>', '_blank');" class='botao_comprar hvr-float-shadow' >Comprar pelo MercadoLivre</div>
					</div>
					
					<div style="text-align:center; margin-top:30px;">
						Após confirmação do pagamento em no máximo 10 minutos você receberá automaticamente o link para download dos arquivos.
					</div>
					
					<?php } else { ?> 
					
					<div style="text-align:center; margin-top:30px;">
						Venda indisponivel no momento
					</div>
					
					<?php } ?> 
					
				</div>
								 
				<div style="text-align: center; margin-top:30px; padding-bottom: 100px;" >
					<div onClick="history.go(-1)" class='botao_padrao hvr-float-shadow' >Voltar</div>
				</div>

			</div>
					

		</div>		
	</div>

</div>
</div>

<?php //finaliza o conteudo da pagina carregando o rodapé, tambem setado no config.php

include_once('htm_rodape.php'); ?>

<script src="<?=LAYOUT?>api/jquery/jquery-1.12.3.min.js"></script>
<script src="<?=LAYOUT?>api/bootstrap/bootstrap.min.js"></script>
<script src="<?=LAYOUT?>api/jquery.scrollUp/jquery.scrollUp.min.js"></script>
<script src="<?=LAYOUT?>js/geral.js"></script>
<script>function dominio(){ return '<?=DOMINIO?>'; }</script>

</body>
</html>