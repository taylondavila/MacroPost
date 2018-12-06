<?php

Class model_logo extends model{
    
    public $endereco = "";
    public $endereco2 = "";
    
    public function __construct(){
    	$this->imagem();
    }
    
    public function imagem(){
 		
		$db = new mysql();
		$exec = $db->executar("SELECT logo FROM adm_config where id='1' ");
		$data = $exec->fetch_object();		 
		
		if($data->logo){
			$this->endereco = PASTA_CLIENTE.'img_logo/'.$data->logo;
			$this->endereco2 = PASTA_CLIENTE.'img_logo/'.$data->logo;
		} else {
			$this->endereco = LAYOUT."img/logo.png";
			$this->endereco2 = LAYOUT."img/logo2.png";
		}
        
    }
	
}