<?php

class enviodigitais extends controller {
	
	protected $_modulo_nome = "Entrega de Produtos Digitais";

	public function init(){
		$this->autenticacao();
		$this->nivel_acesso(59);
	}


	public function inicial(){
		
		$dados['_base'] = $this->base_layout();
		$dados['_titulo'] = $this->_modulo_nome;
		$dados['_subtitulo'] = "";


		$produtos = new model_lojascript();
		$dados['lista'] = $produtos->lista();
		
		$this->view('lojascript.listadigital', $dados);
	}
	

	public function detalhes(){
		
		$dados['_base'] = $this->base_layout();
		$dados['_titulo'] = $this->_modulo_nome;
 		$dados['_subtitulo'] = "Alterar";

 		$codigo = $this->get('codigo'); 		
 		
 		$produtos = new model_lojascript();
		$config = new model_mercadolivre();
 		$data_config = $config->config();

 		$data = $produtos->seleciona($codigo);
 		
 		$texto = $data_config->entrega_automatica;
 		$dados['conteudo'] = str_replace("%descricao%", $data->arquivo_link, $texto);
 		

		$this->view('lojascript.envio', $dados);
	}
	
	
//termina classe
}