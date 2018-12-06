<?php

Class model_lojascript extends model{
	
    public function lista(){
    	
    	$valores = new model_valores();

    	$lista = array();
    	
    	$db = new mysql();
		$exec = $db->executar("SELECT id, codigo, titulo, grupo, valor FROM lojascript ORDER BY id desc ");
		$n = 0;
		while($data = $exec->fetch_object()) {
			
			$lista[$n]['id'] = $data->id;
			$lista[$n]['codigo'] = $data->codigo;
			$lista[$n]['ref'] = $data->id;
			$lista[$n]['titulo'] = $data->titulo;			 
			$lista[$n]['grupo_cod'] = $data->grupo;
			
			$db = new mysql();
			$exec_gr = $db->executar("SELECT titulo FROM lojascript_grupo WHERE codigo='$data->grupo' ");
			$data_gr = $exec_gr->fetch_object();

			if(isset($data_gr->titulo)){
				$lista[$n]['grupo'] = $data_gr->titulo;
			} else {
				$lista[$n]['grupo'] = 'Não definido.';
			}
						 
			$lista[$n]['valor'] = $valores->trata_valor($data->valor);
			
			$vendidos = 0;
			
			$anuncios = '';
			$anuncios_n = 0;
			$db = new mysql();
			$exec_anu = $db->executar("SELECT id_ml, vendidos FROM lojascript_anuncios WHERE produto='$data->codigo' ORDER BY id desc ");
			while($data_anu = $exec_anu->fetch_object()) {
				if($data_anu->id_ml){
					$anuncios .= $data_anu->id_ml.", ";
					$anuncios_n++;
					$vendidos = $vendidos + $data_anu->vendidos;
				}
			}
			
			$lista[$n]['vendidos'] = $vendidos;
			$lista[$n]['anuncios'] = $anuncios;
			$lista[$n]['anuncios_n'] = $anuncios_n;
			
		$n++;
		}
	  	
		return $lista;
	}

	public function anuncios($produto = null){
    	
    	$mercadolivre = new model_mercadolivre();
    	$valores = new model_valores();

    	$lista = array();
    	
    	if($produto){
    		
    		$db = new mysql();
    		$exec = $db->executar("SELECT 
    			lojascript_anuncios.id AS id,
    			lojascript_anuncios.codigo AS codigo,
    			lojascript_anuncios.categoria AS categoria,
    			lojascript_anuncios.id_ml AS id_ml,
    			lojascript_anuncios.titulo AS titulo,
    			lojascript_anuncios.endereco AS endereco,
    			lojascript_anuncios.atualizado AS atualizado,
    			lojascript_anuncios.vendidos AS vendidos,
    			lojascript_anuncios.produto AS produto,
    			lojascript_anuncios.valor AS valor,
    			lojascript.titulo AS produto_titulo 
    			FROM lojascript_anuncios JOIN lojascript ON lojascript_anuncios.produto = lojascript.codigo WHERE lojascript_anuncios.produto='$produto' AND lojascript_anuncios.ativo='0' ");
 				
 				$n = 0;
				while($data = $exec->fetch_object()) {
					
					$lista[$n]['id'] = $data->id;
					$lista[$n]['codigo'] = $data->codigo;
					$lista[$n]['categoria'] = $data->categoria;
					$lista[$n]['categoria_titulo'] = $mercadolivre->nome_categoria($data->categoria);
					$lista[$n]['id_ml'] = $data->id_ml;
					$lista[$n]['titulo'] = $data->titulo;
					$lista[$n]['endereco'] = $data->endereco;
					$lista[$n]['atualizado'] = $data->atualizado;
					$lista[$n]['vendas'] = $data->vendidos;
					$lista[$n]['valor'] = $valores->trata_valor($data->valor);
					$lista[$n]['produto'] = $data->produto_titulo;
					$lista[$n]['imagens'] = $this->imagens_anuncios($data->codigo, $data->produto);
					
				$n++;
				}
				
		} else {
			
			$db = new mysql();
			$exec = $db->executar("SELECT 
    			lojascript_anuncios.id AS id,
    			lojascript_anuncios.codigo AS codigo,
    			lojascript_anuncios.categoria AS categoria,
    			lojascript_anuncios.id_ml AS id_ml,
    			lojascript_anuncios.titulo AS titulo,
    			lojascript_anuncios.endereco AS endereco,
    			lojascript_anuncios.atualizado AS atualizado,
    			lojascript_anuncios.vendidos AS vendidos,
    			lojascript_anuncios.produto AS produto,
    			lojascript_anuncios.valor AS valor,
    			lojascript.titulo AS produto_titulo 
    			FROM lojascript_anuncios JOIN lojascript ON lojascript_anuncios.produto=lojascript.codigo WHERE lojascript_anuncios.ativo='0' ");
				
			$n = 0;
			while($data = $exec->fetch_object()) {
				
				$lista[$n]['id'] = $data->id;
				$lista[$n]['codigo'] = $data->codigo;
				$lista[$n]['categoria'] = $data->categoria;
				$lista[$n]['categoria_titulo'] = $mercadolivre->nome_categoria($data->categoria);
				$lista[$n]['id_ml'] = $data->id_ml;
				$lista[$n]['titulo'] = $data->titulo;
				$lista[$n]['endereco'] = $data->endereco;
				$lista[$n]['atualizado'] = $data->atualizado;
				$lista[$n]['vendas'] = $data->vendidos;
				$lista[$n]['valor'] = $valores->trata_valor($data->valor);
				$lista[$n]['produto'] = $data->produto_titulo;
				$lista[$n]['imagens'] = $this->imagens_anuncios($data->codigo, $data->produto);
				
			$n++;
			}

			$db = new mysql();
			$exec = $db->executar("SELECT * FROM lojascript_anuncios WHERE ativo='0' ");
			while($data = $exec->fetch_object()) {
				
				if(!$data->produto){

					$lista[$n]['id'] = $data->id;
					$lista[$n]['codigo'] = $data->codigo;
					$lista[$n]['categoria'] = $data->categoria;
					$lista[$n]['categoria_titulo'] = $mercadolivre->nome_categoria($data->categoria);
					$lista[$n]['id_ml'] = $data->id_ml;
					$lista[$n]['titulo'] = $data->titulo;
					$lista[$n]['endereco'] = $data->endereco;
					$lista[$n]['atualizado'] = $data->atualizado;
					$lista[$n]['vendas'] = $data->vendidos;
					$lista[$n]['valor'] = $valores->trata_valor($data->valor);
					$lista[$n]['produto'] = "";
					$lista[$n]['imagens'] = array();
					
				$n++;
				}
			}
		}
		
		return $lista;
	}
	
	public function seleciona_anuncio($codigo){
		
		if(!$codigo){ return false; } else {
			
			$db = new mysql();
			$exec = $db->executar("SELECT * FROM lojascript_anuncios WHERE id_ml='$codigo' ");
			if($exec){
				return $exec->fetch_object();
			} else {
				return false;
			}
		}
	}

	public function seleciona($codigo){

		if(!$codigo){ return false; } else {

			$db = new mysql();
			$exec = $db->executar("SELECT * FROM lojascript WHERE codigo='$codigo' ");
			if($exec){
				return $exec->fetch_object();
			} else {
				return false;
			}
		}
	}

	public function imagens($codigo){

		//imagens
 		$conexao = new mysql();
        $coisas_ordem = $conexao->Executar("SELECT * FROM lojascript_imagem_ordem WHERE codigo='$codigo' ORDER BY id desc limit 1");
        $data_ordem = $coisas_ordem->fetch_object();

        $n = 0;
        $imagens = array();
        if(isset($data_ordem->data)){

        	$order = explode(',', $data_ordem->data); 

        	foreach($order as $key => $value){
                
                $conexao = new mysql();
                $coisas_img = $conexao->Executar("SELECT * FROM lojascript_imagem WHERE id='$value'");
                $data_img = $coisas_img->fetch_object();                                

                if(isset($data_img->imagem)){

                	$conexao = new mysql();
	                $coisas_leg = $conexao->Executar("SELECT * FROM lojascript_imagem_legenda WHERE id_img='$value' ");
	                $data_leg = $coisas_leg->fetch_object();
	                
	                if(isset($data_leg->legenda)){
	                	$imagens[$n]['legenda'] = $data_leg->legenda;
	                } else {
	                	$imagens[$n]['legenda'] = "";
	                }

                	$imagens[$n]['id'] = $data_img->id;
               		$imagens[$n]['imagem_p'] = PASTA_CLIENTE.'img_lojascript_p/'.$codigo.'/'.$data_img->imagem;
               		$imagens[$n]['imagem_g'] = PASTA_CLIENTE.'img_lojascript_g/'.$codigo.'/'.$data_img->imagem;

                $n++;
                }
            }
        }

        return $imagens;
	}
	
	public function imagens_anuncios($codigo, $produto){

		//imagens
 		$conexao = new mysql();
        $coisas_ordem = $conexao->Executar("SELECT * FROM lojascript_imagem_ordem WHERE codigo='$codigo' ORDER BY id desc limit 1");
        $data_ordem = $coisas_ordem->fetch_object();
        
        $n = 0;
        $imagens = array();
        if(isset($data_ordem->data)){

        	$order = explode(',', $data_ordem->data); 

        	foreach($order as $key => $value){
                
                $conexao = new mysql();
                $coisas_img = $conexao->Executar("SELECT * FROM lojascript_imagem WHERE id='$value'");
                $data_img = $coisas_img->fetch_object();
                
                if(isset($data_img->imagem)){
                	
                	$imagens[$n]['id'] = $data_img->id;
               		$imagens[$n]['imagem'] = PASTA_CLIENTE.'img_lojascript_p/'.$produto.'/'.$data_img->imagem;
               		$imagens[$n]['imagem_g'] = PASTA_CLIENTE.'img_lojascript_g/'.$produto.'/'.$data_img->imagem; 
               		
                $n++;
                }
            }
            
        } else {
        	
        	$conexao = new mysql();
	        $coisas_ordem = $conexao->Executar("SELECT * FROM lojascript_imagem_ordem WHERE codigo='$produto' ORDER BY id desc limit 1");
	        $data_ordem = $coisas_ordem->fetch_object();
	        
	        if(isset($data_ordem->data)){
	        	
	        	$order = explode(',', $data_ordem->data); 
	        	
	        	foreach($order as $key => $value){
	                
	                $conexao = new mysql();
	                $coisas_img = $conexao->Executar("SELECT * FROM lojascript_imagem WHERE id='$value'");
	                $data_img = $coisas_img->fetch_object();

	                if(isset($data_img->imagem)){

	                	$imagens[$n]['id'] = $data_img->id;
	               		$imagens[$n]['imagem'] = PASTA_CLIENTE.'img_lojascript_p/'.$produto.'/'.$data_img->imagem; 
	               		$imagens[$n]['imagem_g'] = PASTA_CLIENTE.'img_lojascript_g/'.$produto.'/'.$data_img->imagem; 

	                $n++;
	                }
	            }

	        }

        }

        return $imagens;

	}

	public function grupos($codigo = null){

		//categorias
 		$lista = array();

 		$db = new mysql();
 		$exec = $db->Executar("SELECT * FROM lojascript_grupo order by titulo asc");
 		$n = 0;
 		while($data = $exec->fetch_object()){

 			$lista[$n]['id'] = $data->id;
 			$lista[$n]['codigo'] = $data->codigo;
 			$lista[$n]['titulo'] = $data->titulo;
 			
 			if($codigo == $data->codigo){
 				$lista[$n]['selected'] = true;
 			} else {
 				$lista[$n]['selected'] = false;
 			}
 			
 		$n++;
 		}
 		
 		return $lista;
	}

	public function seleciona_grupo($codigo){

		if(!$codigo){ return false; } else {

			$db = new mysql();
			$exec = $db->executar("SELECT * FROM lojascript_grupo WHERE codigo='$codigo' ");
			if($exec){
				return $exec->fetch_object();
			} else {
				return false;
			}
		}
	}
	
	
}