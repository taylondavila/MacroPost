<?php

class imagens extends controller {
	
	protected $_modulo_nome = "Imagens";

	public function init(){
		$this->autenticacao();
		$this->nivel_acesso(65);
	}

	public function inicial(){
		
		$dados['_base'] = $this->base_layout();
		$dados['_titulo'] = $this->_modulo_nome;
		$dados['_subtitulo'] = "";

		$dados['permissao'] = false;
		
		$lista = array();

		$conexao = new mysql();
		$coisas = $conexao->executar("SELECT * FROM imagem ORDER BY titulo asc");
		$n = 0;
		while($data = $coisas->fetch_object()){

			$lista[$n]['id'] = $data->id;
			$lista[$n]['codigo'] = $data->codigo;
			$lista[$n]['titulo'] = $data->titulo;

		$n++;
		}
		$dados['lista'] = $lista;

		$this->view('imagens', $dados);
	}
	
	public function nova(){
		
		$dados['_base'] = $this->base_layout();
		$dados['_titulo'] = $this->_modulo_nome;
 		$dados['_subtitulo'] = "Nova";

 		$dados['aba_selecionada'] = "dados";

		$this->view('imagens.nova', $dados);
	}

	public function nova_grv(){
		
		$titulo = $this->post('titulo');

		$this->valida($titulo);

		$codigo = $this->gera_codigo();

		$db = new mysql();
		$db->inserir("imagem", array(
			"codigo"=>"$codigo",
			"titulo"=>"$titulo"
		));
	 	
		$this->irpara(DOMINIO.$this->_controller.'/alterar/aba/imagem/codigo/'.$codigo);
	}
	

	public function alterar(){
		
		$dados['_base'] = $this->base_layout();
		$dados['_titulo'] = $this->_modulo_nome;
 		$dados['_subtitulo'] = "Alterar";
 		
 		$codigo = $this->get('codigo');

		$dados['acesso_alterar_titulo'] = true;
 		 
 		$aba = $this->get('aba');
 		if($aba){
 			$dados['aba_selecionada'] = $aba;
 		} else {

 			if($dados['acesso_alterar_titulo']){
	 			$dados['aba_selecionada'] = 'dados';
	 		} else {
	 			$dados['aba_selecionada'] = 'imagem';
	 		}

 		}


 		$db = new mysql();
 		$exec = $db->Executar("SELECT * FROM imagem where codigo='$codigo' ");
		$dados['data'] = $exec->fetch_object();

		$this->view('imagens.alterar', $dados);
	}


	public function alterar_grv(){

		$codigo = $this->post('codigo');

		$titulo = $this->post('titulo'); 

		$this->valida($titulo);
		
		$db = new mysql();
		$db->alterar("imagem", array(
			"titulo"=>"$titulo"
		), " codigo='$codigo' ");
	 	
		$this->irpara(DOMINIO.$this->_controller.'/alterar/codigo/'.$codigo);		
	}

	public function imagem(){

		$arquivo = $_FILES['arquivo'];
		$tmp_name = $_FILES['arquivo']['tmp_name'];

		//pega a exteção
		$ext1 = $_FILES['arquivo']['name'];
		$explode = explode(".", $ext1);
		$ext3 = end($explode);
		$ext2 = strtolower($ext3);

		$codigo = $this->get('codigo');

		$diretorio = "arquivos/imagens/";

		if($tmp_name) {
	 		
			$icode = $this->gera_codigo();
			$nome_foto  = md5(uniqid($icode)).".$ext2";
			
			if(copy($tmp_name, $diretorio.$nome_foto)){

				$db = new mysql();
				$db->alterar("imagem", array(
					"imagem"=>"$nome_foto"
				), " codigo='$codigo' ");
				
				$this->irpara(DOMINIO.$this->_controller.'/alterar/codigo/'.$codigo.'/aba/imagem');
				
			} else {
				
				$this->msg('Erro ao gravar imagem!');
				$this->irpara(DOMINIO.$this->_controller."/alterar/codigo/".$codigo."/aba/imagem");
				
			}

		}
		
	}

	public function apagar_imagem(){
		
		$codigo = $this->get('codigo');

		if($codigo){

			$db = new mysql();
			$exec = $db->executar("SELECT * FROM imagem where codigo='$codigo' ");
			$data = $exec->fetch_object();

			if($data->imagem){
				unlink('arquivos/imagens/'.$data->imagem);
			}
			
			$db = new mysql();
			$db->alterar("imagem", array(
				"imagem"=>""
			), " codigo='$codigo' ");
		}

		$this->irpara(DOMINIO.$this->_controller.'/alterar/codigo/'.$codigo.'/aba/imagem');
	}
	
	public function apagar_varios(){
		
		$db = new mysql();
		$exec = $db->Executar("SELECT * FROM imagem ");
		while($data = $exec->fetch_object()){
			
			if($this->post('apagar_'.$data->id) == 1){
				
				if($data->imagem){
					unlink('arquivos/imagens/'.$data->imagem);
				}

				$conexao = new mysql();
				$conexao->apagar("imagem", " id='$data->id' ");
					
			}
		}

		$this->irpara(DOMINIO.$this->_controller);
		
	}


}