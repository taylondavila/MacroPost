<?php require_once('_system/bloqueia_view.php'); ?>
<!DOCTYPE html>
<html>
<head>

<meta http-equiv="Content-Type" charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Erro! - <?=$_base['titulo_pagina']?></title>
<link rel="shortcut icon" href="<?=LAYOUT?>img/ico.png">

<meta name="description" content="<?=$_base['descricao']?>" />
<meta property="og:description" content="<?=$_base['descricao']?>">
<meta name="author" content="ComprePronto.com.br">
<meta name="classification" content="Website" />
<meta name="robots" content="index, follow">
<meta name="Indentifier-URL" content="<?=DOMINIO?>" />

<link rel="stylesheet" href="<?=LAYOUT?>api/bootstrap/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?=LAYOUT?>api/font-awesome-4.6.2/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?=LAYOUT?>api/hover-master/css/hover-min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Boogaloo|Open+Sans:300,400,600,600i,700,800|Roboto:300,300i,400,400i,500,700,900" rel="stylesheet">
<link href="<?=LAYOUT?>css/animate.css" rel="stylesheet">
<link rel="stylesheet" href="<?=LAYOUT?>css/css.css" rel="stylesheet"> 

<?php // css alteravel pelo painel
include_once('_css.php'); ?>

</head>
<body>

<?php // carrega o arquivo txt com o codigo do analytcs
echo analytics; ?>

<?php //carrega o topo selecionado no arquivo de configuração (config.php)
include_once('htm_topo.php');

//daqui em diante o conteudo da pagina
?>

<div id="corpo" style="background-color: #fff;" >	 

	<div class="container">

		<div class="row">

			<div class="col-xs-12 col-sm-12 col-md-12" >
				<div style="padding-top:250px; padding-bottom:270px; text-align:center; font-size:20px; color:#666;">Página não encontrada!</div>
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