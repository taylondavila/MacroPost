<?php

class modulos extends controller {
	
	protected $_modulo_nome = "Módulos";

	public function init(){
		$this->autenticacao();
		$this->nivel_acesso(25);
	}

	public function inicial(){
		
		$dados['_base'] = $this->base_layout();
		$dados['_titulo'] = $this->_modulo_nome;
		$dados['_subtitulo'] = "";
		
		$lista = array();

		$db = new mysql();
		$exec = $db->executar("SELECT * FROM adm_setores ");
		$i = 0;
		while($data = $exec->fetch_object()) {
			
			$lista[$i]['id'] = $data->id;
			$lista[$i]['id_pai'] = $data->id_pai;
			$lista[$i]['titulo'] = $data->titulo;
			$lista[$i]['titulo_tecnico'] = $data->titulo_tecnico;
			
		$i++;
		}
		$dados['lista'] = $lista;
		
		$this->view('modulos', $dados);
	}
	
	public function novo(){
		
		$dados['_base'] = $this->base_layout();
		$dados['_titulo'] = $this->_modulo_nome;
 		$dados['_subtitulo'] = "Novo";

 		

		$this->view('modulos.novo', $dados);
	}

	public function novo_grv(){
		
		$titulo = $this->post('titulo');
		$titulo_tecnico = $this->post('titulo_tecnico');
		$ico = $this->post('ico');
		$id_pai = $this->post('id_pai');
		$endereco = $this->post('endereco');

		$this->valida($titulo);
		$this->valida($titulo_tecnico);

		$db = new mysql();
		$db->inserir("adm_setores", array(
			"id_pai"=>"$id_pai",
			"titulo"=>"$titulo",
			"titulo_tecnico"=>"$titulo_tecnico",
			"endereco"=>"$endereco",
			"ico"=>"$ico"
		));
	 	
		$this->irpara(DOMINIO.$this->_controller);
		
	}
	
	public function alterar(){
		
		$dados['_base'] = $this->base_layout();
		$dados['_titulo'] = $this->_modulo_nome;
		$dados['_subtitulo'] = "Alterar";
 		
 		$id = $this->get('codigo');

 		$db = new mysql();
		$exec = $db->executar("SELECT * FROM adm_setores WHERE id='$id' ");
		$dados['data'] = $exec->fetch_object();

		if(!isset($dados['data']) ) {
			$this->irpara(DOMINIO.$this->_controller);
		}

		$this->view('modulos.alterar', $dados);

	}

	public function alterar_grv(){
		
		$codigo = $this->post('codigo');

		$titulo = $this->post('titulo');
		$titulo_tecnico = $this->post('titulo_tecnico');
		$ico = $this->post('ico');
		$id_pai = $this->post('id_pai');
		$endereco = $this->post('endereco');
		
		$this->valida($codigo);
		$this->valida($titulo);
		$this->valida($titulo_tecnico);
		
		$db = new mysql();
		$db->alterar("adm_setores", array(
			"id_pai"=>"$id_pai",
			"titulo"=>"$titulo",
			"titulo_tecnico"=>"$titulo_tecnico",
			"endereco"=>"$endereco",
			"ico"=>"$ico"
		), " id='$codigo' ");
	 	
		$this->irpara(DOMINIO.$this->_controller);
		
	}

	public function apagar_varios(){
		
		$db = new mysql();
		$exec = $db->Executar("SELECT * FROM adm_setores ");
		while($data = $exec->fetch_object()){
			
			if($this->post('apagar_'.$data->id) == 1){
				
				$conexao = new mysql();
				$conexao->apagar("adm_setores", " id='$data->id' ");
					
			}
		}

		$this->irpara(DOMINIO.$this->_controller);
		
	}

	public function bloqueios(){
		
		$dados['_base'] = $this->base_layout();
		$dados['_titulo'] = $this->_modulo_nome;
		$dados['_subtitulo'] = "Bloqueios";

		$lista = array();

		$db = new mysql();
		$exec = $db->executar("SELECT * FROM adm_setores order by titulo asc");
		$i = 0;
		while($data = $exec->fetch_object()) {			 	

			$lista[$i]['id'] = $data->id;
			$lista[$i]['id_pai'] = $data->id_pai;
			$lista[$i]['titulo'] = $data->titulo_tecnico;
			
			$db = new mysql();
			$exec_confere = $db->Executar("SELECT * FROM adm_setores_perfil where setor='$data->id' ");
			if($exec_confere->num_rows != 0){ $lista[$i]['check'] = " checked='' "; } else { $lista[$i]['check'] = ""; }

		$i++;
		}

		$lista_org = new model_ordena_permissoes();
		$lista_org->monta(0, $lista);
		$dados['lista'] = $lista_org->_lista_certa;

		$this->view('modulos.bloqueios', $dados);

	}

	public function bloqueios_grv(){
		
		$db = new mysql();
		$exec = $db->executar("SELECT * FROM adm_setores order by titulo asc");
		while($data = $exec->fetch_object()) {

			$db = new mysql();
			$confere = $db->executar("SELECT * FROM adm_setores_perfil where setor='$data->id' ");
			
			if( $this->post('setor_'.$data->id) ){
				
				if($confere->num_rows == 0){ 

					$db = new mysql();
					$db->inserir("adm_setores_perfil", array(
						"setor"=>"$data->id"
					));
						
				}
					
			} else {
				
				if($confere->num_rows != 0){ 

					$db = new mysql();
					$db->apagar("adm_setores_perfil", " setor='$data->id' ");
					
				}

			}
	
		}

		$this->irpara(DOMINIO.$this->_controller.'/bloqueios');
		
	}

}