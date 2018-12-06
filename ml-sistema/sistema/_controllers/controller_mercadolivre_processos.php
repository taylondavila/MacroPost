<?php

class mercadolivre_processos extends controller {
	
	protected $_modulo_nome = "Mercado Livre";
	
	public function init(){
		
		// modulo ml
		require_once('mercadolivre/meli.php');
		
		$db = new mysql();
		$exec = $db->executar("SELECT * FROM mercadolivre_config WHERE id='1' ");
		$data = $exec->fetch_object();
		
		$meli = new Meli($data->app_id, $data->secret_key, $data->ultimo_token, $data->ultimo_refresh_token);
		$refresh = $meli->refreshAccessToken();
		
		$_SESSION['access_token'] = $refresh['body']->access_token;
		$_SESSION['refresh_token'] = $refresh['body']->refresh_token;

		if($_SESSION['access_token']){
			
			// api mercado livre
			$retorno = $meli->get('/users/me', array('access_token' => $_SESSION['access_token']));
			
			//echo '<pre>'; print_r($retorno); echo '</pre>';
			
			$_SESSION['id_user_mercadolivre'] = $retorno['body']->id;
			$ml_nome = $retorno['body']->nickname;

			$db = new mysql();
			$db->alterar("mercadolivre_config", array(
				"ultimo_token"=>$_SESSION['access_token'],
				"ultimo_refresh_token"=>$_SESSION['refresh_token']
			), " id='1' ");
			
		}

	}
	
	public function processa_nao_qualificadas(){
		
 		// modulo ml
		require_once('mercadolivre/meli.php');

		if($_SESSION['access_token']){

			$config = new model_mercadolivre();
 			$data_conmfig = $config->config();

			$mensagem =  $data_conmfig->nao_qualificados;

			// conecta ml
			$meli = new Meli($_SESSION['access_token'], $_SESSION['refresh_token']);					 
			
			$seller_id = $_SESSION['id_user_mercadolivre'];

			$dia_inicio = date('Y-m-d', strtotime('-20 days')).'T00:00:00.000-00:00';		
			$dia_fim = date('Y-m-d').'T00:00:00.000-00:00';

			$array = array(
				"seller"=>$seller_id,
				"order.status"=>"paid",
				"order.date_created.from"=>$dia_inicio,
				"order.date_created.to"=>$dia_fim,
				"access_token"=>$_SESSION['access_token']
			);
			
			$retorno = $meli->get("/orders/search", $array);
			
			foreach ($retorno[body]->results as $key => $value) {
				// confere se tem qualificação
				if(!isset($value->feedback->purchase->id)){
					// confere se ja foi entregue
					if(isset($value->feedback->sale->id)){
							
							//echo "data: ".$value->date_created."<br>";

							$comprador = $value->buyer->id;
							//echo $comprador; exit;

							//retorno feedback -- Taylon
							$mensagem = str_replace("%idpedido%", $value->id, $mensagem);
							
							$from = array("user_id"=>$seller_id);
							$to = array(array(
								"user_id"=>$comprador,
								"resource"=>"orders",
								"resource_id"=>$value->id,
								"site_id"=>"MLB"
							));
							$text = array(
								"plain"=>"$mensagem"
							);

							$item = array(
								"from"=>$from,
								"to"=>$to,
								"text"=>$text
							);

							$meli = new Meli($_SESSION['access_token'], $_SESSION['refresh_token']);
							$retorno_msg = $meli->post("/messages", $item, array('access_token' => $_SESSION['access_token']));

							if(isset($retorno_msg['body'][0]->message_id)){
								echo "Venda: ".$value->id.": ".$retorno_msg['body'][0]->message_id."<br>";
							} else {
								echo "Venda: ".$value->id.": Erro ao enviar mensagem";
							}

							//echo '<pre>';
							//print_r($retorno_msg);
							//echo '</pre>';
							//exit;

					}
				}
			}

			//echo '<pre>';
			//print_r($retorno);
			//echo '</pre>';			

			exit;

		} else {
			echo "Faça o login com o MercadoLivre para continuar";
			exit;
		}

	}
	
	public function processa_vendas(){
		
		$produtos = new model_lojascript();

		$config = new model_mercadolivre();
 		$data_conmfig = $config->config();

 		$texto_entrega = $data_conmfig->entrega_automatica;

 		// modulo ml
		require_once('mercadolivre/meli.php');
		
		if($_SESSION['access_token']){
			
			$meli = new Meli($_SESSION['access_token'], $_SESSION['refresh_token']);					 
			
			$seller_id = $_SESSION['id_user_mercadolivre'];
 			
 			// lista vendas
			$array = array(
				"seller"=>$seller_id,
				"order.status"=>"paid",
				"access_token"=>$_SESSION['access_token']
			);
			$retorno = $meli->get("/orders/search/recent", $array);
			
			foreach ($retorno['body']->results as $key => $value) {
				// confere se tem qualificação
				if(!isset($value->feedback->purchase->id)){
				// confere se ja foi entregue
				if(!isset($value->feedback->sale->id)){
					
					// confere se ja foi enviado mensagem
					$meli = new Meli($_SESSION['access_token'], $_SESSION['refresh_token']);
					$retorno_mensagens = $meli->get("messages/orders/".$value->id, array("access_token"=>$_SESSION['access_token']));
					$mensagens_enviadas = 0;
					foreach ($retorno_mensagens['body']->results as $key2 => $value2) {
						if(isset($value2->from->user_id)){
							if($seller_id == $value2->from->user_id){
								$mensagens_enviadas++;
							}
						}
					}

					// verifica se ja foi enviado alguma mensagem se ja foi não continua
					//echo "Mensagens enviadas: ".$mensagens_enviadas."<br>";
					if($mensagens_enviadas == 0){
						
						echo "Venda: ".$value->id."<br>";
						$comprador = $value->buyer->id; 

						//pega itens
						$mensagem = "";
						$numero_de_links = 0;

						foreach ($value->order_items as $key2 => $value2) {
							
							echo "Anuncio: ".$value2->item->id."<br>";

							$anuncio = $produtos->seleciona_anuncio($value2->item->id);
							$produto = $produtos->seleciona($anuncio->produto);

							echo "Produto: ".$produto->titulo."<br>";
					 		
							// mensagem com link do produto e qualificacao
							if($produto->arquivo_link){

								$arquivo_link_txt = $produto->arquivo_link;
								
								$numero_de_links++;
							}
							
							$mensagem .= str_replace("%descricao%", $arquivo_link_txt, $texto_entrega);							

						} 	
						//echo $mensagem;

						if($numero_de_links != 0){

							// envia mensagem com produto						
							$from = array("user_id"=>$seller_id);
							$to = array(array(
								"user_id"=>$comprador,
								"resource"=>"orders",
								"resource_id"=>$value->id,
								"site_id"=>"MLB"
							));
							$text = array(
								"plain"=>"$mensagem"
							);
							$item = array(
								"from"=>$from,
								"to"=>$to,
								"text"=>$text
							);
							
							$meli = new Meli($_SESSION['access_token'], $_SESSION['refresh_token']);
							$retorno_msg = $meli->post("/messages", $item, array('access_token' => $_SESSION['access_token']));
							if(isset($retorno_msg['body'][0]->message_id)){

								echo "Mensagem enviada: ".$retorno_msg['body'][0]->message_id."<br>";

								// qualificação automatica
								$array = array(
									"fulfilled"=>true,
									"rating"=>"positive",
									"message"=>"Ótimo Comprador, recomendamos a toda comunidade do ML"
								);
								
								$meli = new Meli($_SESSION['access_token'], $_SESSION['refresh_token']);
								$retorno_qual = $meli->post("/orders/".$value->id."/feedback", $array, array('access_token' => $_SESSION['access_token']));

								echo '<pre>';
								print_r($retorno_qual);
								echo '</pre>';							

							} else {
								echo "Venda: ".$value->id.": Erro ao enviar produto";
							}

							//echo '<pre>';
							//print_r($retorno_msg);
							//echo '</pre>';
						}
					}
				}
				}
			}
			
			//echo '<pre>';
			//print_r($retorno);
			//echo '</pre>';
			
			echo date('d/m/Y H:i')." - Processo Finalizado";	
			exit;
			
		} else {
			echo date('d/m/Y H:i')." - Faça o login com o MercadoLivre para continuar";
			exit;
		}

	}
	
}