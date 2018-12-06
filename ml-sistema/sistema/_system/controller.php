<?php

class controller extends system {
	
	public $_cod_usuario = '';
	public $_dados_usuario = '';
	
	public function init(){ //inicialização
	}
	
    public function autenticacao(){
    	
    	if( isset($_SESSION['sessao']) AND isset($_SESSION['autenticacao']) AND isset($_SESSION['cod_usuario']) ){
    		
    		$this->_sessao = $_SESSION['sessao'];
    		$this->_autenticado = $_SESSION['autenticacao'];
    		$this->_cod_usuario = $_SESSION['cod_usuario'];
    		$usuario = $_SESSION['usuario'];
    		
    		$db = new mysql();
			$exec = $db->executar("SELECT * FROM adm_usuario WHERE codigo='$this->_cod_usuario' AND usuario='$usuario' ");
			
			if($exec->num_rows != 1){
				session_destroy();
 				$this->irpara( DOMINIO );
			} else {
				$this->_dados_usuario = $exec->fetch_object();
			}
    		
    	} else {
    		
    		$_SESSION['pagina_acionada'] = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    		
			$this->irpara(DOMINIO.'autenticacao');
			
    	}
    }
    
    public function nivel_acesso($setor, $msg = true){

    	if($this->_cod_usuario == 1){
    		return true;
    	} else {
    		if($setor == 'adm'){
    			if($msg){
	    			$this->msg('Permissão negada!');
					$this->irpara( DOMINIO );
					exit;
    			}
    			return false;
    		} else {
		    	$db = new mysql();
				$exec = $db->executar("SELECT id FROM adm_setores_usuario WHERE usuario='$this->_cod_usuario' AND setor='$setor' ");		
				if($exec->num_rows == 0){
					if($msg){
						$this->msg('Permissão negada!');
						$this->irpara( DOMINIO );
						exit;
					}
					return false;
				} else {
					return true;
				}
			}
		}

    }

    protected function lista_menu(){
    	
    	$lista = array();
    	
    	$db = new mysql();
		$exec = $db->executar("SELECT * FROM adm_setores_ordem where usuario='$this->_cod_usuario' order by id desc limit 1");
		$data_ordem = $exec->fetch_object();
		
		if(isset($data_ordem->data)){
			//a ordem existe
			$order = explode(',', $data_ordem->data);
			$i = 0;	
			foreach($order as $key => $value){
				
				$db = new mysql();
				$exec = $db->Executar("SELECT * FROM adm_setores WHERE id='$value'");
				$data_menu = $exec->fetch_object();
				
				if(isset($data_menu->id)){
					
					if( $this->nivel_acesso($data_menu->id,false) ){
						
						$lista[$i]['id'] = $data_menu->id;
						$lista[$i]['titulo'] = $data_menu->titulo;
						$lista[$i]['icone'] = $data_menu->ico;
						$lista[$i]['endereco'] = $data_menu->endereco;
						
						if($this->_controller == $data_menu->endereco){
							$lista[$i]['ativo'] = true;
						} else {
							$lista[$i]['ativo'] = false;
						}
						
						$i++;
					}
				}
			}
		} else {
			//carrega lista por ordem alfabetica
			$db = new mysql();
			$exec = $db->executar("SELECT * FROM adm_setores where id_pai='0' AND menu='0' order by titulo asc");
			$i = 0;
			while($data_menu = $exec->fetch_object()){
				
				if( $this->nivel_acesso($data_menu->id,false) ){

						$lista[$i]['id'] = $data_menu->id;
						$lista[$i]['titulo'] = $data_menu->titulo;
						$lista[$i]['icone'] = $data_menu->ico;
						$lista[$i]['endereco'] = $data_menu->endereco;

						if($this->_controller == $data_menu->endereco){
							$lista[$i]['ativo'] = true;
						} else {
							$lista[$i]['ativo'] = false;
						}
						
						$i++;

				}

			}
		}

        return $lista;
    }
    
    protected function base_layout(){
    	
    	$dados = array();
    	
    	$logo = new model_logo();
		$dados['logo'] = $logo->endereco;
		$dados['logo_b'] = $logo->endereco2;
		
		//carrega titulo da página
		$dados['titulo_pagina'] = $this->_modulo_nome.' - '.TITULO_VIEW;
		
		//carrega menu
		$dados['menu_lateral'] = $this->lista_menu();

		//carrega imagem do usuario
		if($this->_dados_usuario->imagem){
			$dados['conta_imagem'] = PASTA_CLIENTE."img_usuarios/".$this->_dados_usuario->imagem;
		} else {
			$dados['conta_imagem'] = LAYOUT."img/usuario.png";
		}

		//nome do usuário
		$dados['conta_nome'] = $this->_dados_usuario->nome;

		//codigo do usuario
		$dados['conta_codigo'] = $this->_dados_usuario->codigo;

		//email
		$dados['conta_email'] = $this->_dados_usuario->email_recuperacao;

		//tipo
		if($this->_dados_usuario->codigo == 1){
			$dados['conta_tipo'] = "administrador";
		} else {
			$dados['conta_tipo'] = "usuário";
		}

		//acesso aos usuarios
		if($this->nivel_acesso(1,false)){
			$dados['acesso_user'] = 1;
		} else {
			$dados['acesso_user'] = 0;
		}

		//acesso as configuracoes
		if($this->nivel_acesso(2,false)){
			$dados['acesso_config'] = 1;
		} else {
			$dados['acesso_config'] = 0;
		}
		
		//menu aberto ou fechado
		$dados['menu_fechado'] = $this->_dados_usuario->abre_fecha_menu;
		
		//objeto
		$dados['objeto'] = DOMINIO.$this->_controller.'/';
		
		//VERSAO DO SISTEMA
		$dados['versao'] = $this->versao();
		
		$navegador = new model_navegador();
		$dados['navegador'] = $navegador->nome();
		
		return $dados;
	}
    
    protected function versao(){
    	
		//Regra de Versão
		// Primeira Casa: Versão da Aplicação / Layout
		// Segunda Casa: Alterações dos Controllers/Módulos
		// Segunda Casa: Alterações Simples
    	$db = new mysql();
		$modulos = $db->executar("SELECT id FROM adm_setores where id_pai='0' order by titulo asc");
				
		return "10.".$modulos->num_rows;
		
    }
    
	protected function view( $arquivo, $vars = null ){
		
		if( is_array($vars) && count($vars) > 0){
			//transforma array em variavel
			//com prefixo
			//extract($vars, EXTR_PREFIX_ALL, 'htm_');
			//se ouver variaveis iguais adiciona prefixo
			extract($vars, EXTR_PREFIX_SAME, 'htm_');
		}

		$url_view = VIEWS."htm_".$arquivo.".php";
		
		return require_once($url_view);
	}

	public function gera_codigo(){
		return substr(time().rand(10000,99999),-15);
	}

	public function valida($var, $msg = null){
        if(!$var){
            if($msg){
                $this->msg($msg);
                $this->volta(1);
            } else {
                $this->msg('Preencha todos os campos e tente novamente!');
                $this->volta(1);
            }
        }
    }
	
	
}