<?php
class produto extends controller {
	
	public function init(){
	}
	
	public function inicial(){

		//definições basicas (OBS: tudo que estiver na array dados é enviado como variavel para a view)
		$layout = new model_layout();
		$dados['_base'] = $layout->carrega();
		$dados['objeto'] = DOMINIO.$this->_controller.'/';
		$dados['controller'] = $this->_controller;	
		
		$valores = new model_valores();
		$scripts = new model_scripts();

		$id = $_SESSION['id_produto'];
		
		//Pega dados 
		$db = new mysql();
		$exec = $db->executar("select * from lojascript WHERE id='$id' ");
		$dados['data'] = $exec->fetch_object();
		
		if(!isset($dados['data']->codigo)){
			$this->irpara(DOMINIO);
			exit;
		}
		
		//codigo da noticia
		$codigo = $dados['data']->codigo;
		
		//categoria codigo
		$dados['categoria_codigo'] = $dados['data']->grupo;
		$dados['valor'] = $valores->trata_valor($dados['data']->valor);
		
		$porcentagem_em_cima = 100;
		$resultado = (($porcentagem_em_cima / 100) * $dados['data']->valor) + $dados['data']->valor;
		$dados['valor_de'] = $valores->trata_valor($resultado); 
		
		//endereco
		$dados['endereco'] = DOMINIO."produto/".$dados['data']->id;
		
		//carrega anuncios para encontrar endereço
		$dados['endereco_ml'] = $scripts->endereco($dados['data']->codigo);		
		
		//pega imagens
		$imagens = array();
		$conexao = new mysql();
		$coisas_ordem = $conexao->Executar("SELECT * FROM lojascript_imagem_ordem WHERE codigo='$codigo' ORDER BY id desc limit 1");
		$data_ordem = $coisas_ordem->fetch_object();
		
		if(isset($data_ordem->data)){
			
			$order = explode(',', $data_ordem->data);
			
			$ii = 0;
			foreach($order as $key => $value){
				
				$conexao = new mysql();
				$coisas_img = $conexao->Executar("SELECT id, imagem FROM lojascript_imagem WHERE id='$value'");
				$data_img = $coisas_img->fetch_object();

				if(isset($data_img->imagem)){
					
					//carrega legenda se tiver
					$conexao = new mysql();
					$coisas_leg = $conexao->Executar("SELECT legenda FROM lojascript_imagem_legenda WHERE id_img='$data_img->id'");
					$data_leg = $coisas_leg->fetch_object();
					if(isset($data_leg->legenda)){
						$imagens[$ii]['legenda'] = $data_leg->legenda;
					} else {
						$imagens[$ii]['legenda'] = '';
					}
					
					if($ii == 0){
						$dados['imagem_principal'] = PASTA_CLIENTE."img_lojascript_g/".$codigo."/".$data_img->imagem;
					}
					
					$imagens[$ii]['id'] = $data_img->id;
					$imagens[$ii]['imagem_g'] = PASTA_CLIENTE."img_lojascript_g/".$codigo."/".$data_img->imagem;
					$imagens[$ii]['imagem_p'] = PASTA_CLIENTE."img_lojascript_p/".$codigo."/".$data_img->imagem;
					
				$ii++;
				}

			}
		}
		$dados['imagens'] = $imagens;
		
		//lista categorias para lateral
		$dados['categorias'] = $scripts->grupos();
 		$dados['categoria_codigo'] = $dados['data']->grupo;
 		$dados['categoria'] = $scripts->titulo_grupo($dados['data']->grupo);
 		
 		//carrega view e envia dados para a tela
		$this->view('loja.detalhes', $dados);
	}

	
}