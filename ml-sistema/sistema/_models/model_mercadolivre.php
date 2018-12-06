<?php

Class model_mercadolivre extends model{
	
    public function categorias(){
    	
    	$lista = array();
    	
    	$db = new mysql();
		$exec = $db->executar("SELECT * FROM mercadolivre_categorias order by titulo asc");
		$i = 0;
		while($data = $exec->fetch_object()) {
			
			$lista[$i]['id'] = $data->id;
			$lista[$i]['codigo'] = $data->codigo;
			$lista[$i]['titulo'] = $data->titulo;
			
		$i++;
		}
	  	
		return $lista;
	}
	
	public function seleciona_categoria($codigo){

		$db = new mysql();
		$exec = $db->executar("SELECT * FROM mercadolivre_categorias where id='$codigo' ");

		if(isset($exec)){
			return $exec->fetch_object();
		} else {
			return 'Categoria não encontrada!';
		}

		exit;
	}
	
	public function nome_categoria($codigo){
		
		$db = new mysql();
		$exec = $db->executar("SELECT * FROM mercadolivre_categorias where codigo='$codigo' ");
		$data = $exec->fetch_object();
		
		if(isset($data->titulo)){
			return $data->titulo;
		} else {
			return 'Categoria não encontrada!';
		}
	}

	public function config(){

		$db = new mysql();		 
		$exec = $db->executar("SELECT * FROM mercadolivre_config WHERE id='1' ");
		if($exec){
			return $exec->fetch_object();
		} else {
			return false;
		}
	}
	

	//////////////////////////////////////////////////////////////////////////////////////////////
	// RECEBE DO MERCADO LIVRE
	
	
	
	
}