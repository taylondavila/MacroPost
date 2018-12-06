<?php
class index extends controller {
	
	public function init(){
		
	}
	
	public function inicial(){
		
		//definições basicas (OBS: tudo que estiver na array dados é enviado como variavel para a view)
		$layout = new model_layout();
		$dados['_base'] = $layout->carrega();
		$dados['objeto'] = DOMINIO.$this->_controller.'/';
		$dados['controller'] = $this->_controller;
		
		
		//lista scripts
		$scripts = new model_scripts();
		$scripts->perpage = 5000;
		//define variaveis
		
		//gets caso for preenchido define a configuraçao
		if($this->get('busca')){ $scripts->busca = $this->get('busca'); }
		if($this->get('categoria')){ $scripts->categoria = $this->get('categoria'); }
		
	 	//retorno
		$array = $scripts->lista();
	 	$dados['lista'] = $array['lista'];
		
	 	//lista categorias para lateral
		$dados['categorias'] = $scripts->grupos();
		if($this->get('categoria')){
 			$dados['categoria_codigo'] = $this->get('categoria');
 		} else {
 			$dados['categoria_codigo'] = "";
 		}

 		//carrega view e envia dados para a tela
		$this->view('loja', $dados);
	}

	
}