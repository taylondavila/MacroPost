<?php

class arquivos extends controller {
	
	protected $_modulo_nome = "Arquivos";

	public function init(){
		$this->autenticacao();
		$this->nivel_acesso(61);
	}

	public function inicial(){
		
		$dados['_base'] = $this->base_layout();
		$dados['_titulo'] = $this->_modulo_nome;
		$dados['_subtitulo'] = "";
		
		$usuarios = new model_usuarios();
		
		$lista = array();

		$db = new mysql();
		$exec = $db->executar("SELECT * FROM hospedararquivo order by id desc");
		$i = 0;
		while($data = $exec->fetch_object()) {
			
			$lista[$i]['id'] = $data->id;
			$lista[$i]['codigo'] = $data->codigo;
			$lista[$i]['titulo'] = $data->titulo;
			$lista[$i]['arquivo'] = $data->arquivo;
			$lista[$i]['data'] = date("d/m/Y H:i", $data->data);
			$lista[$i]['usuario'] = $usuarios->nome($data->usuario);
			
		$i++;
		}
		$dados['lista'] = $lista;
		
		$this->view('arquivos', $dados);
	}
	
	public function novo(){
		
		$dados['_base'] = $this->base_layout();
		$dados['_titulo'] = $this->_modulo_nome;
 		$dados['_subtitulo'] = "Novo"; 		

		$this->view('arquivos.novo', $dados);
	}

	public function novo_grv(){
		
		$titulo = $this->post('titulo');
		$arquivo_original = $_FILES['arquivo'];
		$tmp_name = $_FILES['arquivo']['tmp_name'];

		$this->valida($titulo);
		
		//carrega model de gestao de imagens
		$arquivo = new model_arquivos_imagens();

		//// Definicao de Diretorios / 
		$diretorio = "arquivos/arquivos/";
		
		if(!$arquivo->filtro($arquivo_original)){ $this->msg('Arquivo com formato inválido ou inexistente!'); $this->volta(1); } else {
				
				$nome_original = $_FILES['arquivo']['name'];
				$nome_arquivo  = $arquivo->trata_nome($nome_original);
				
				$destino = $diretorio.$nome_arquivo;
				
				if(copy($tmp_name, $destino)){
					
					$codigo = $this->gera_codigo();
					$time = time();
					
					$db = new mysql();
					$db->inserir("hospedararquivo", array(						
						"codigo"=>"$codigo",
						"usuario"=>"$this->_cod_usuario",
						"data"=>"$time",
						"titulo"=>"$titulo",
						"arquivo"=>"$nome_arquivo"
					));
					
					$this->irpara(DOMINIO.$this->_controller);
					
				} else {
					
					$this->msg('Não foi possível copiar o arquivo!');
					$this->volta(1);

				}
				
		}
		
	}
	

	public function apagar_varios(){
		
		$db = new mysql();
		$exec = $db->Executar("SELECT * FROM hospedararquivo ");
		while($data = $exec->fetch_object()){
			
			if($this->post('apagar_'.$data->id) == 1){

				unlink("arquivos/arquivos/$data->arquivo");
				
				$conexao = new mysql();
				$conexao->apagar("hospedararquivo", " id='$data->id' ");

			}
		}

		$this->irpara(DOMINIO.$this->_controller);		
	}


}