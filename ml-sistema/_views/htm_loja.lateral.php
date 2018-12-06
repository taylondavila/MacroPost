<?php require_once('_system/bloqueia_view.php'); ?>

<div>
	<h2 class='h3' >
		<a class='blog_lista_titulo2' >Buscar</a>
	</h2>

	<form id="form_busca" name="form_busca" action="<?=DOMINIO?>busca_loja" method="post" >
	
		<div style="padding-top:20px;">
			<input name="busca" type="text" class="form-control" placeholder="O que procura?" style="width:100%; border-radius:0px;" >

			<div class='botao_padrao hvr-float-shadow' style="width:100%; margin-top:10px" onclick="document.form_busca.submit()" ><i class="fa fa-search"></i> BUSCAR</div>
		</div>

	</form>
</div>

<div style="margin-top:30px">

	<h2 class='h3' >
		<a class='blog_lista_titulo2' >Categorias</a>
	</h2>	

	<div style="padding-top:5px;">
	<?php

		foreach ($categorias as $key => $value) {
			
			$endereco_cat = DOMINIO."index/inicial/categoria/".$value['codigo'];

			if($categoria_codigo == $value['codigo']){
				$ativo = " categorias_ativa";
				$abrepasta = "-open";
			} else {
				$ativo = "";
				$abrepasta = "";
			}
			
			echo "
			<div class='categorias".$ativo."' >
				<a href='$endereco_cat' ><i class='fa fa-folder".$abrepasta."-o'></i> ".$value['titulo']."</a>
			</div>
			";
			
		}

	?>
	</div>


</div>