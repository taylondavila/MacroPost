<?php require_once('_system/bloqueia_view.php'); ?>

<div id="topo" >

	<div id="menu_responsivo_botao" >
		<i class="fa fa-bars" aria-hidden="true"></i> <a href="#"></a>
	</div>
	
	<div class="container">
		<div class="row">
				
				<div class="col-xs-12 col-sm-4 col-md-4">
					<div id="logo"><a href='<?=DOMINIO?>' title="Logo ComprePronto" ><img src="<?=$_base['imagem']['151213498772211']?>"></a></div>
				</div>
				
				<div class="col-xs-12 col-sm-8 col-md-8">
					<div id="menu" >
						
						<ul> 
							<li><a href='<?=DOMINIO?>' <?php if($controller == 'index'){ echo " class='ativo' "; } ?> >Produtos</a></li> 
							<li><a href='<?=DOMINIO?>contato' <?php if($controller == 'contato'){ echo " class='ativo' "; } ?> >Fale Conosco</a></li>
						</ul>
						
					</div>
				</div>
				
		</div>
	</div>	 

</div>

<div id="menu_responsivo" >
	<ul> 
		<li><a href='<?=DOMINIO?>' >Produtos</a></li> 
		<li><a href='<?=DOMINIO?>contato' >Fale Conosco</a></li>
	</ul>
</div>