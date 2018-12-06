<?php

class mercadolivre extends controller {
	
	protected $_modulo_nome = "Mercado Livre";
	
	public function init(){
		$this->autenticacao();
		$this->nivel_acesso(63);
	}
	
	public function inicial(){
		
		$dados['_base'] = $this->base_layout();
		$dados['_titulo'] = $this->_modulo_nome;
 		$dados['_subtitulo'] = "Configurações";
 		
 		$produtos = new model_mercadolivre();
 		$dados['data'] = $produtos->config();
 		
		$this->view('mercadolivre', $dados);
	}
	
	public function config_grv(){
		
		$template = $_POST['template'];
		$app_id = $_POST['app_id'];
		$secret_key = $_POST['secret_key'];
		$entrega_automatica = $_POST['entrega_automatica'];
		$nao_qualificados = $_POST['nao_qualificados'];
		
		$db = new mysql();
		$db->alterar("mercadolivre_config", array(
			"template"=>"$template",
			"app_id"=>"$app_id",
			"secret_key"=>"$secret_key",
			"entrega_automatica"=>"$entrega_automatica",
			"nao_qualificados"=>"$nao_qualificados"
		), " id='1' ");
	 	
		$this->irpara(DOMINIO.$this->_controller);
	}

	public function categorias(){
		
		$dados['_base'] = $this->base_layout();
		$dados['_titulo'] = $this->_modulo_nome;
 		$dados['_subtitulo'] = "Categorias";

 		$produtos = new model_mercadolivre();
 		$dados['lista'] = $produtos->categorias();
 		
		$this->view('mercadolivre.categorias', $dados);
	}
 	
	public function nova_categoria(){

		$dados['_base'] = $this->base_layout();

		$this->view('mercadolivre.categorias.nova', $dados);
	}

	public function nova_categoria_grv(){

		$titulo = $this->post('titulo');
		$codigo = $this->post('codigo');

		$db = new mysql();
		$db->inserir("mercadolivre_categorias", array(
			"codigo"=>"$codigo",
			"titulo"=>"$titulo"
		));


		$this->irpara(DOMINIO.$this->_controller.'/categorias');
	}

	public function alterar_categoria(){
		
		$dados['_base'] = $this->base_layout();
		
		$id = $this->get('id');
		
		$produtos = new model_mercadolivre();
 		$dados['data'] = $produtos->seleciona_categoria($id);
 		
 		
		$this->view('mercadolivre.categorias.alterar', $dados);
	}
		
	public function alterar_categoria_grv(){
		
		$titulo = $this->post('titulo');
		$codigo = $this->post('codigo');
		$id = $this->post('id');
		
		$db = new mysql();
		$db->alterar("mercadolivre_categorias", array(
			"codigo"=>"$codigo",
			"titulo"=>"$titulo"
		), " id='$id' ");

	 	
		$this->irpara(DOMINIO.$this->_controller.'/categorias');
	}

	public function apagar_categorias(){
		
		$db = new mysql();
		$exec = $db->Executar("SELECT * FROM mercadolivre_categorias ");
		while($data = $exec->fetch_object()){
			
			if($this->post('apagar_'.$data->id) == 1){
				
				$conexao = new mysql();
				$conexao->apagar("mercadolivre_categorias", " id='$data->id' ");
				
			}
		}

		$this->irpara(DOMINIO.$this->_controller.'/categorias');		
	}

	////////////////////////////////////////////////////////////////////////////////
	// integração

	public function criar_anuncio(){

		$valores = new model_valores();

		$anuncio = $this->get('codigo');

		$db = new mysql();
		$exec_anuncio = $db->Executar("SELECT * FROM lojascript_anuncios WHERE codigo='$anuncio' ");
		$data_anuncio = $exec_anuncio->fetch_object();
 		
 		// configuracao mercado livre
		$mercadolivre = new model_mercadolivre();
		$config_ml = $mercadolivre->config();
		
		// pega dados do produto
		$produto = new model_lojascript();
		$data_produto = $produto->seleciona($data_anuncio->produto);
		
		// monta na template a descricao
 		$descricao = str_replace("%descricao%", $data_produto->descricao, $config_ml->template);
 		$valor = $valores->trata_valor($data_produto->valor);
 		$valor = str_replace(".", "", $valor); 
 		$valor = str_replace(",", ".", $valor); 

 		// imagens
 		$imagens_anuncio = array();
 		$n = 0;
 		foreach ($produto->imagens_anuncios($anuncio, $data_anuncio->produto) as $key => $value) {
			$imagens_anuncio[$n]['source'] = $value['imagem_g'];
			$n++;
 		}
 		
 		//echo '<pre>'; print_r($imagens_anuncio); echo '</pre>'; exit;

 		// video
 		if($data_produto->youtube){
 			$video = explode('v=', $data_produto->youtube);
 			$video_id = $video[1];
 		} else {
 			$video_id = null;
 		}


		require_once('mercadolivre/meli.php');

		if($_SESSION['access_token']){

			$meli = new Meli($_SESSION['access_token'], $_SESSION['refresh_token']);
 			
			$item = array(
				"title" => "$data_anuncio->titulo",
				"category_id" => "$data_anuncio->categoria",
				"price" => $valor,
				"currency_id" => "BRL",
				"available_quantity" => 9999,
				"buying_mode" => "buy_it_now",
				"listing_type_id" => "gold_pro",
				"condition" => "new",
				"description" => "$descricao",
				"warranty" => "Garantia de suporte após a compra",
				"pictures" => $imagens_anuncio,
				"video_id" => $video_id,
				"accepts_mercadopago" => true,
				"shipping" => array(
		            "mode" => "not_specified",
		            "local_pick_up" => true,
		            "free_shipping" => false,
		            "logistic_type" => "not_specified"
				)
			);
			
			$retorno = $meli->post('/items', $item, array('access_token' => $_SESSION['access_token']));
			
			if($retorno['body']->permalink AND $retorno['body']->id){
				
				$db = new mysql();
				$db->alterar("lojascript_anuncios", array(
					"id_ml"=>$retorno['body']->id,
					"endereco"=>$retorno['body']->permalink,
					"atualizado"=>"1"
				), "codigo='$anuncio' ");
				
				$this->irpara(DOMINIO.'lojascript/alterar/codigo/'.$data_anuncio->produto.'/aba/anuncios');
				exit;
				
			} else {

				echo '<pre>';
				print_r($retorno);
				echo '</pre>';

			}


		} else {
			$this->msg('Faça o login com o MercadoLivre para continuar!');
			$this->volta(1);
		}

	}

	public function alterar_anuncio(){

		$valores = new model_valores();

		$anuncio = $this->get('codigo');

		$db = new mysql();
		$exec_anuncio = $db->Executar("SELECT * FROM lojascript_anuncios WHERE codigo='$anuncio' ");
		$data_anuncio = $exec_anuncio->fetch_object();
 		
 		// configuracao mercado livre
		$mercadolivre = new model_mercadolivre();
		$config_ml = $mercadolivre->config();
		
		// pega dados do produto
		$produto = new model_lojascript();
		$data_produto = $produto->seleciona($data_anuncio->produto);
		
		// monta na template a descricao
 		$descricao = str_replace("%descricao%", $data_produto->descricao, $config_ml->template);
 		$valor = $valores->trata_valor($data_produto->valor);
 		$valor = str_replace(".", "", $valor); 
 		$valor = str_replace(",", ".", $valor); 

 		// imagens
 		$imagens_anuncio = array();
 		$n = 0;
 		foreach ($produto->imagens_anuncios($anuncio, $data_anuncio->produto) as $key => $value) {
			$imagens_anuncio[$n]['source'] = $value['imagem_g'];
			$n++;
 		}
 		
 		//echo '<pre>'; print_r($imagens_anuncio); echo '</pre>'; exit;

 		// video
 		if($data_produto->youtube){
 			$video = explode('v=', $data_produto->youtube);
 			$video_id = $video[1];
 		} else {
 			$video_id = null;
 		}

		require_once('mercadolivre/meli.php');

		if($_SESSION['access_token']){

			$meli = new Meli($_SESSION['access_token'], $_SESSION['refresh_token']); 			
			
 			// descricao
			$item = array(
				"text" => $descricao
			);			
			$retorno = $meli->put('/items/'.$data_anuncio->id_ml.'/description/', $item, array('access_token' => $_SESSION['access_token']));

			// titulo e valor e imagem
			$item = array(
				"title" => "$data_anuncio->titulo",
				"price" => $valor,
				"pictures" => $imagens_anuncio,
				"video_id" => $video_id,
				"shipping" => array(
		            "mode" => "not_specified",
		            "local_pick_up" => true,
		            "free_shipping" => false,
		            "logistic_type" => "not_specified"
				)
			);
			$retorno = $meli->put('/items/'.$data_anuncio->id_ml, $item, array('access_token' => $_SESSION['access_token']));
			
			if($retorno['body']->permalink){

				$db = new mysql();
				$db->alterar("lojascript_anuncios", array(
					"endereco"=>$retorno['body']->permalink,
					"atualizado"=>"1"
				), "codigo='$anuncio' ");

				$this->irpara(DOMINIO.'lojascript/alterar/codigo/'.$data_anuncio->produto.'/aba/anuncios');
				exit;

			} else {

				echo '<pre>';
				print_r($retorno);
				echo '</pre>';

			}


		} else {
			$this->msg('Faça o login com o MercadoLivre para continuar!');
			$this->volta(1);
		}

	}

	public function testar_anuncio(){

 		// modulo ml
		require_once('mercadolivre/meli.php');

		$anuncio = $this->get('id');

		if($_SESSION['access_token']){

			$meli = new Meli($_SESSION['access_token'], $_SESSION['refresh_token']);					 
			
			// We call the post request to list a item
			echo '<pre>';
			print_r($meli->get("/items/$anuncio", array('access_token' => $_SESSION['access_token'])));
			echo '</pre>';
 			

		} else {
			$this->msg('Faça o login com o MercadoLivre para continuar!');
			$this->volta(1);
		}

	}

	public function listar_vendas(){

 		// modulo ml
		require_once('mercadolivre/meli.php');

		if($_SESSION['access_token']){

			$meli = new Meli($_SESSION['access_token'], $_SESSION['refresh_token']);					 
			
			$seller_id = $_SESSION['id_user_mercadolivre'];

			$array = array(
				"seller"=>$seller_id,
				"status"=>"paid",
				"access_token"=>$_SESSION['access_token']
			);

			$retorno = $meli->get("/orders/search", $array);	

			echo '<pre>';
			print_r($retorno);
			echo '</pre>';
			
		} else {
			$this->msg('Faça o login com o MercadoLivre para continuar!');
			$this->volta(1);
		}

	}

	public function listar_clientes(){

 		// modulo ml
		require_once('mercadolivre/meli.php');

		if($_SESSION['access_token']){

			$meli = new Meli($_SESSION['access_token'], $_SESSION['refresh_token']);					 
			
			$seller_id = $_SESSION['id_user_mercadolivre'];

			$array = array(
				"seller"=>$seller_id,
				"status"=>"paid",
				"offset"=>0,
				"access_token"=>$_SESSION['access_token']
			);

			$retorno = $meli->get("/orders/search", $array);	

			$cli = 1;
			foreach ($retorno['body']->results as $key => $value) {

				$produto = $value->order_items[0]->item->title;
				$nome = $value->buyer->nickname.' - '.$value->buyer->first_name;
				$email = $value->buyer->email;
				$fone = $value->buyer->phone->area_code.' '.$value->buyer->phone->number;

				echo "
				<div style='margin-top:30px;'>
					<div>$cli</div>
					<div>Produto: ".$produto."</div>
					<div>Nome: ".$nome."</div>
					<div>Nome: ".$email."</div>
					<div>Telefone: ".$fone."</div>
				</div>
				-<br>-<br>
				";

			$cli++;
			}
			
		} else {
			$this->msg('Faça o login com o MercadoLivre para continuar!');
			$this->volta(1);
		}

	}

	public function testar_venda(){

 		// modulo ml
		require_once('mercadolivre/meli.php');

		if($_SESSION['access_token']){

			$meli = new Meli($_SESSION['access_token'], $_SESSION['refresh_token']);					 
			
			$seller_id = $_SESSION['id_user_mercadolivre'];

			$id_venda = $this->get('id');

			$array = array(
				"access_token"=>$_SESSION['access_token']
			);

			$retorno = $meli->get("/orders/$id_venda", $array);
 

			echo '<pre>';
			print_r($retorno);
			echo '</pre>';
			
		} else {
			$this->msg('Faça o login com o MercadoLivre para continuar!');
			$this->volta(1);
		}

	}

	public function mensagens_venda(){
		
 		// modulo ml
		require_once('mercadolivre/meli.php');

		if($_SESSION['access_token']){

			$meli = new Meli($_SESSION['access_token'], $_SESSION['refresh_token']);					 
			
			$seller_id = $_SESSION['id_user_mercadolivre'];
			
			$id_venda = $this->get('id');

			$array = array(
				"access_token"=>$_SESSION['access_token']
			);

			$retorno = $meli->get("messages/orders/$id_venda", $array);
 			
			echo '<pre>';
			print_r($retorno);
			echo '</pre>';
			
		} else {
			$this->msg('Faça o login com o MercadoLivre para continuar!');
			$this->volta(1);
		}

	}

	public function importar_anuncios(){
		
		//mostra carregando
		echo "<div style='font-family:arial; font-size:16px; color:#666; width:100%; padding-top:20px; text-align:center; '><img src='".DOMINIO."_views/img/loading.gif' style='width:25px; margin-bottom:10px;'><br>Aguarde finalizar o processo.</div>";

		// modulo ml
		require_once('mercadolivre/meli.php');

		if($_SESSION['access_token']){

			$meli = new Meli($_SESSION['access_token'], $_SESSION['refresh_token']);			
			$seller_id = $_SESSION['id_user_mercadolivre'];

			if($this->get('pag')){
				$pagina_atual = $this->get('pag');
			} else {
				$pagina_atual = 0;
			}

			$total_paginas = 20;

			$array = array(
				"access_token"=>$_SESSION['access_token'],
				"orders"=>"stop_time_desc",
				'limit'=>$total_paginas,
				'offset'=>$pagina_atual
			);

			$retorno = $meli->get("/users/$seller_id/items/search", $array);
 			
			// paginacao 			 
			//echo '<pre>';
			//print_r($retorno);
			//echo '</pre>'; exit;	

			// marca todos como inativo para depois ativar somente os listados

			if(count($retorno['body']->results) > 0){

				if($pagina_atual == 0){

					$db = new mysql();
					$db->alterar("lojascript_anuncios", array(
						"ativo"=>1
					), " 'id'!='0' ");

				}
				
				$n_anu = 0;
				foreach ($retorno['body']->results as $key => $value) {
					
					$n_anu++;

					$db = new mysql();
					$exec = $db->Executar("SELECT id, produto FROM lojascript_anuncios WHERE id_ml='$value' ");
					if($exec->num_rows == 0){

						$codigo = $this->gera_codigo();

						// grava no banco
						$db = new mysql();
						$db->inserir("lojascript_anuncios", array(
							"codigo"=>$codigo,
							"id_ml"=>$value,
							"ativo"=>0,
							"atualizado"=>1,
							"vendidos"=>0
						));

					} else {

						$data_anuncio = $exec->fetch_object();
						$produto = $data_anuncio->produto;

						//confer se o produto não foi removido
						$db = new mysql(); 
						$exec = $db->Executar("SELECT id FROM lojascript WHERE codigo='$produto' ");
						if($exec->num_rows == 0){
							$produto = "";
						}

						// grava no banco
						$db = new mysql();
						$db->alterar("lojascript_anuncios", array(
							"produto"=>$produto,
							"ativo"=>0,
							"atualizado"=>1
						), " id_ml='$value' ");

					}

					//echo $n_anu.'<br>';
					//echo $value.'<br>';
				}

				// se tiver itens vai para proxima paguna
				if($n_anu != 0){
					
					$proxima_pg = $pagina_atual+$total_paginas;
					$this->irpara(DOMINIO.'mercadolivre/importar_anuncios/pag/'.$proxima_pg);

				}


			}
 			
			$this->irpara(DOMINIO.'mercadolivre/importar_anuncios_sincronizar');

		} else {
			$this->msg('Faça o login com o MercadoLivre para continuar!');
			$this->volta(1);
		}

	}

	public function importar_anuncios_sincronizar(){
		
		//mostra carregando
		echo "<div style='font-family:arial; font-size:16px; color:#666; width:100%; padding-top:20px; text-align:center; '><img src='".DOMINIO."_views/img/loading.gif' style='width:25px; margin-bottom:10px;'><br>Aguarde finalizar o processo.</div>";

		// modulo ml
		require_once('mercadolivre/meli.php');

		if($_SESSION['access_token']){

			$meli = new Meli($_SESSION['access_token'], $_SESSION['refresh_token']);			
			$seller_id = $_SESSION['id_user_mercadolivre'];

			$array = array(
				"access_token"=>$_SESSION['access_token']
			);

			$db = new mysql();
			$exec_anuncio = $db->Executar("SELECT id, id_ml FROM lojascript_anuncios where ativo='0' AND atualizado='1' order by id desc limit 20");
			$n = 0;
			while($data_anuncio = $exec_anuncio->fetch_object()){
				
				$n++;

				$retorno = $meli->get("/items/".$data_anuncio->id_ml, array('access_token' => $_SESSION['access_token']));
				
				//echo '<pre>';
				//print_r($retorno);
				//echo '</pre>';
				
				if($retorno['body']->id){

					$numero_vendas = $retorno['body']->sold_quantity;
					$valor = $retorno['body']->price;
					$permalink = $retorno['body']->permalink;
					$categoria = $retorno['body']->category_id;
					$titulo = $retorno['body']->title;

					// grava no banco
					$db = new mysql();
					$db->alterar("lojascript_anuncios", array(
						"categoria"=>"$categoria",
						"titulo"=>"$titulo",
						"valor"=>"$valor",
						"endereco"=>"$permalink",
						"atualizado"=>"0",
						"vendidos"=>"$numero_vendas"
					), " id='$data_anuncio->id' ");

					
					// confere se a categoria ja existe no banco
					$db = new mysql();
					$exec = $db->Executar("SELECT id FROM mercadolivre_categorias where codigo='$categoria' ");
					if($exec->num_rows == 0){

						$retorno = $meli->get("/categories/".$categoria, array('access_token' => $_SESSION['access_token'])); 

						if(isset($retorno['body']->name)){

							$titulo = $retorno['body']->name;

							$db = new mysql();
							$db->inserir("mercadolivre_categorias", array(
								"codigo"=>"$categoria",
								"titulo"=>"$titulo"
							));
						}						
					}


				}

			}

			if($n == 0){
				$this->irpara(DOMINIO.'lojascript/anuncios');
			} else {
				$this->irpara(DOMINIO.'mercadolivre/importar_anuncios_sincronizar');
			}
			
		} else {
			$this->msg('Faça o login com o MercadoLivre para continuar!');
			$this->volta(1);
		}

	}


}