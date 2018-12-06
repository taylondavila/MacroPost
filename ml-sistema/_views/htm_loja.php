<?php require_once('_system/bloqueia_view.php'); ?>
<!DOCTYPE html>
<html>
<head>

<meta http-equiv="Content-Type" charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?=$_base['titulo_pagina']?></title>
<link rel="shortcut icon" href="<?=$_base['imagem']['151213502131634']?>">

<meta name="description" content="<?=$_base['descricao']?>" />
<meta property="og:description" content="<?=$_base['descricao']?>">
<meta name="author" content="ComprePronto.com.br">
<meta name="classification" content="Website" />
<meta name="robots" content="index, follow">
<meta name="Indentifier-URL" content="<?=DOMINIO?>" />

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

<div style="position:relative; width:100%; background-color:#f2f2f2; padding-top:30px; padding-bottom: 30px; margin-top:10px;">
	<div class="container">
		<div class="row">
			
			<div class="col-xs-12 col-sm-3 col-md-3" >
				<div class="loja_divisao_lateral" >
					<?php include_once('htm_loja.lateral.php'); ?>
				</div>
			</div>
			
			<div class="col-xs-12 col-sm-9 col-md-9" >
				
				<div>
				<?php

					$n = 1;
					$i = 0;
					foreach ($lista as $key => $value) {
						if($value['imagem']){

							if($n == 1){
								echo "<div class='row'>";
							}

							$endereco = "onClick=\"window.location='".$value['endereco']."';\"";

							echo "
							<div class='col-xs-12 col-sm-4 col-md-4' >
								<div class='loja_lista_div hvr-bob'>
									<div class='loja_lista_imagem' $endereco style='background-image:url(".$value['imagem'].")' ></div>
									<div class='loja_lista_titulo' $endereco >".$value['titulo']."</div>
									<div class='loja_lista_previa'>".$value['previa']."</div>
									<div class='loja_lista_valor'>R$ ".$value['valor']."</div>
									<div class='loja_lista_botao' ><div $endereco class='botao_padrao hvr-float-shadow' >Mais Detalhes</div></div>
								</div>
							</div>
							";
							
							if($n == 3){
								echo "</div>";
								$n = 1;
							} else {
								$n++;
							}

						$i++;
						}
					}

					if($n != 1){
						echo "</div>";
					}

					if($i == 0){
						echo "
						<div class='row'>
							<div class='col-xs-12 col-sm-12 col-md-12' >

								<div style='margin-top:100px; margin-bottom:50px; text-align:center;'>Nenhum resultado encontrado!</div>
								
							</div>
						</div>
						";				 
					}
					
				?>
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