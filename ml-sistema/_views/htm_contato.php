<?php require_once('_system/bloqueia_view.php'); ?>
<!DOCTYPE html>
<html>
<head>

<meta http-equiv="Content-Type" charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Fale Conosco - <?=$_base['titulo_pagina']?></title>
<link rel="shortcut icon" href="<?=LAYOUT?>img/ico.png">

<meta name="description" content="Dúvidas, suporte ou sugestões? Entre em contato com nossa equipe!" />
<meta property="og:description" content="Dúvidas, suporte ou sugestões? Entre em contato com nossa equipe!">
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

<?php include_once('htm_modal.php'); ?>

<?php //carrega o topo selecionado no arquivo de configuração (config.php)
include_once('htm_topo.php');

//daqui em diante o conteudo da pagina
?> 

<div id="corpo" >	 


<div class="div_titulo" >
	<div class="container" >
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12" >
				<h1 class="titulo_principal" >Fale Conosco</h1>
			</div>
		</div>
	</div>	
</div>


<div style="position:relative; width:100%; background-color:#f2f2f2; padding-top:60px; padding-bottom:50px; margin-top:10px; ">

	<div class="container">

		<div class="row">

			<div class="col-xs-12 col-sm-3 col-md-3" ></div>

			<div class="col-xs-12 col-sm-6 col-md-6" >

				<div>
				<form id="formcontato" name="formcontato" >
						
						<div class="form-group">
							<label for="name">Nome</label>
							<input type="text" class="form-control form_personal" id="nome" name="nome" placeholder="Digite seu nome" required="required" />
	                    </div>
	                    
	                    <div class="form-group ">
							<label for="email">E-mail</label>
							<div class="input-group ">
								<span class="input-group-addon form_personal"><span class="glyphicon glyphicon-envelope"></span></span>
								<input type="email" class="form-control form_personal" id="email" name="email" placeholder="Digite seu e-mail" required="required" />
							</div>
						</div>
						
						<div class="form-group form_personal">
	                    	<label for="fone">Telefone</label>
	                        <div class="input-group">
	                        	<span class="input-group-addon form_personal"><span class="glyphicon glyphicon-phone"></span></span>
	                            <input type="text" class="form-control form_personal" id="fone" name="fone" placeholder="Digite seu Telefone" required="required" />
	                        </div>
	                    </div>
	                    
	                    <div class="form-group">
	                    	<label for="name">Mensagem</label>
	                        <textarea name="msg" id="msg" class="form-control form_personal" cols="25" required placeholder="Escreva sua Mensagem..." style="height:108px;"></textarea>
	                    </div>
	                	
	                	<div class="row" >
		                	<div class="form-group col-md-4">
		                		<div style='padding-top:5px;'><img src="<?=DOMINIO?>contato/captcha" alt="captcha" /></div>
		                	</div>
		                	
		                	<div class="form-group col-md-4">
		                		<div>
		                			<label for="captcha">Digite o texto</label>
	                                <input type="text" name="captcha" id="captcha" class="form-control form_personal" style="height: 33px;" />
	                            </div>
	                        </div>

	                        <div class="form-group col-md-4">
	                            <div class="botao_padrao  hvr-float-shadow" style="width:100%;  margin-top:20px; padding-top:10px;" onClick="envia_contato();" ><span style="font-size: 13px;"><i class="fa fa-check" aria-hidden="true"></i></span> ENVIAR</div>
	                        </div>
	                    </div>

				</form>
				</div>
 
			</div>

			<div class="col-xs-12 col-sm-3 col-md-3" ></div>

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

<script>
function envia_contato(){

    var nome = $('#nome').val();
    var email = $('#email').val();
    var fone = $('#fone').val();
    var msg = $('#msg').val();
    var captcha = $('#captcha').val();
    
    $('#modal_conteudo_m').html("<div style='text-align:center;'><img src='"+dominio()+"_views/img/loading.gif' style='width:25px;'></div>");
    $('#janela_modal_medio').modal('show');
    
    $.post('<?=DOMINIO?>contato/enviar', { nome:nome, email:email, fone:fone, msg:msg, captcha:captcha},function(data){
        if(data){
          $('#modal_conteudo_m').html(data);
        }
    });
    
}
</script>

</body>
</html>