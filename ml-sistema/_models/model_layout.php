<?php

Class model_layout extends model{
    
    public function carrega(){
    	
    	$dados = array();
		
		//detecta navegador 
		$navegador = new model_navegador();
		$dados['navegador'] = $navegador->nome();
		
		//informações basicas de metan
    	$db = new mysql();
		$config = $db->executar("select * from meta where id='1' ")->fetch_object();
		$dados['titulo_pagina'] = $config->titulo_pagina;
		$dados['descricao'] = $config->descricao;

		//carrega imagens do setadas no painel de controle
		$db = new mysql();
		$exec = $db->executar("select codigo, imagem from imagem ");
		while($data = $exec->fetch_object()){
			if($data->imagem){
				$dados['imagem'][$data->codigo] = PASTA_CLIENTE.'imagens/'.$data->imagem;
			} else {
				$dados['imagem'][$data->codigo] = '';
			}
		}
		
		//retorna para a pagina a array com todos as informações
		return $dados;
	}

}