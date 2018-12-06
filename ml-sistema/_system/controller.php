<?php

class controller extends system {
	
	public $_cod_usuario = '';
	public $_dados_usuario = '';
	
	public function init(){ //inicialização
	}
	
	//carrega o html 
	protected function view( $arquivo, $vars = null ){
		
		if( is_array($vars) && count($vars) > 0){
			//transforma array em variavel
			//com prefixo
			//extract($vars, EXTR_PREFIX_ALL, 'htm_');
			//se ouver variaveis iguais adiciona prefixo
			extract($vars, EXTR_PREFIX_SAME, 'htm_');
		}

		$url_view = VIEWS."htm_".$arquivo.".php";
		
		if(!file_exists($url_view)){
            $this->erro();
        } else {
			return require_once($url_view);
		}
		
	}
	
	//gera codigo que nunca se repete
	public function gera_codigo(){
		return substr(time().rand(10000,99999),-15);
	}
	
	//confere se foi preenchido um campo post ou get
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