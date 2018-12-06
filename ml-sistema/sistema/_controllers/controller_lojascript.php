<?php

class lojascript extends controller {
	
	protected $_modulo_nome = "Scripts";

	public function init(){
		$this->autenticacao();
		$this->nivel_acesso(59);
	}

	public function inicial(){
		
		$dados['_base'] = $this->base_layout();
		$dados['_titulo'] = $this->_modulo_nome;
		$dados['_subtitulo'] = "";
		
		$produtos = new model_lojascript();
		$dados['lista'] = $produtos->lista();
		
		$this->view('lojascript', $dados);
	}
	
	public function novo(){
		
		$dados['_base'] = $this->base_layout();
		$dados['_titulo'] = $this->_modulo_nome;
 		$dados['_subtitulo'] = "Novo";

 		$dados['aba_selecionada'] = "dados";

 		$valores = new model_valores();
 		$produtos = new model_lojascript();
 		$dados['grupos'] = $produtos->grupos();
 		
		$this->view('lojascript.novo', $dados);
	}

	public function novo_grv(){
		
		$titulo = $this->post('titulo');
		$previa = $this->post('previa');
		$descricao = $_POST['descricao'];
		$grupo = $this->post('grupo');
		$valor = $this->post('valor');
		$arquivo_link = $_POST['arquivo_link']; 
		$esconder = $this->post('esconder');
		
		$valores = new model_valores();
		$valor = $valores->trata_valor_banco($valor);
		
		$this->valida($titulo);
		$this->valida($grupo);		 
		
		$codigo = $this->gera_codigo();
		
		$db = new mysql();
		$db->inserir("lojascript", array(
			"codigo"=>"$codigo",
			"grupo"=>"$grupo",
			"titulo"=>"$titulo",
			"previa"=>"$previa",
			"descricao"=>"$descricao",
			"valor"=>"$valor",
			"arquivo_link"=>"$arquivo_link",
			"esconder"=>"$esconder"
		));
	 			
		$this->irpara(DOMINIO.$this->_controller.'/alterar/aba/imagem/codigo/'.$codigo);
	}
	
	public function alterar(){
		
		$dados['_base'] = $this->base_layout();
		$dados['_titulo'] = $this->_modulo_nome;
 		$dados['_subtitulo'] = "Alterar";

 		$codigo = $this->get('codigo');

 		$aba = $this->get('aba');
 		if($aba){
 			$dados['aba_selecionada'] = $aba;
 		} else {
 			$dados['aba_selecionada'] = 'dados';
 		}

 		$valores = new model_valores();
 		$produtos = new model_lojascript();

 		$dados['data'] = $produtos->seleciona($codigo);

 		$dados['grupos'] = $produtos->grupos($dados['data']->grupo);

 		$dados['valor'] = $valores->trata_valor($dados['data']->valor);

 		//imagens
        $dados['imagens'] = $produtos->imagens($codigo);

        //anuncios
        $dados['anuncios'] = $produtos->anuncios($codigo);

 		
		$this->view('lojascript.alterar', $dados);
	}
	
	public function alterar_grv(){
		
		$codigo = $this->post('codigo');
		
		$titulo = $this->post('titulo');
		$previa = $this->post('previa');
		$descricao = $_POST['descricao'];
		$grupo = $this->post('grupo');
		$valor = $this->post('valor');
		$arquivo_link = $_POST['arquivo_link'];
		$youtube = $_POST['youtube'];
		$esconder = $this->post('esconder');

		$valores = new model_valores();
		$valor = $valores->trata_valor_banco($valor);	
		
		$this->valida($codigo);
		$this->valida($titulo);
		$this->valida($grupo);
		
		$db = new mysql();
		$db->alterar("lojascript", array(
			"grupo"=>"$grupo",
			"titulo"=>"$titulo",
			"previa"=>"$previa",
			"descricao"=>"$descricao",			 
			"valor"=>"$valor",
			"arquivo_link"=>"$arquivo_link",
			"youtube"=>"$youtube",
			"esconder"=>"$esconder"
		), " codigo='$codigo' ");
		
		$db = new mysql();
		$db->alterar("lojascript_anuncios", array(
			"atualizado"=>"0"
		), " produto='$codigo' ");
				
		$this->irpara(DOMINIO.$this->_controller.'/alterar/codigo/'.$codigo);		
	}

	public function apagar_imagem(){
		
		$codigo = $this->get('codigo');
		$id = $this->get('id');

		if($id){

			$db = new mysql();
			$exec = $db->executar("SELECT * FROM lojascript_imagem where id='$id' ");
			$data = $exec->fetch_object();

			if($data->imagem){
				unlink('arquivos/img_lojascript_g/'.$codigo.'/'.$data->imagem);
				unlink('arquivos/img_lojascript_p/'.$codigo.'/'.$data->imagem);
			}

			$conexao = new mysql();
			$conexao->apagar("lojascript_imagem", " id='$id' ");
		}

		$this->irpara(DOMINIO.$this->_controller.'/alterar/codigo/'.$codigo.'/aba/imagem');
	}

	public function ordenar_imagem(){

		$codigo = $this->post('codigo');
		$list = $this->post('list');
		
		$this->valida($codigo);
		$this->valida($list);

		$output = array();
		parse_str($list, $output);
		$ordem = implode(',', $output['item']);

		$db = new mysql();
		$db->inserir("lojascript_imagem_ordem", array(
			"codigo"=>"$codigo",
			"data"=>"$ordem"
		));
		
		$db = new mysql();
		$db->alterar("lojascript_anuncios", array(
			"atualizado"=>"0"
		), " codigo='$codigo' ");
		
	}
	
	public function legenda(){
		
		$dados['_base'] = $this->base_layout();
		
		$id = $this->get('id');
		$codigo = $this->get('codigo');
		
		$dados['codigo'] = $codigo;
		$dados['id'] = $id;
		
		$db = new mysql();
		$exec = $db->executar("SELECT * FROM lojascript_imagem_legenda where id_img='$id' ");
		$data = $exec->fetch_object();
		
		if(isset($data->id)){
			$dados['legenda'] = $data->legenda;
		} else {
			$dados['legenda'] = '';
		}
		
		$this->view('lojascript.legenda', $dados);
	}

	public function legenda_grv(){

		$id = $this->post('id');
		$legenda = $this->post('legenda');
		$codigo = $this->post('codigo');

		$db = new mysql();
		$exec = $db->executar("SELECT * FROM lojascript_imagem_legenda where id_img='$id' ");
		$data = $exec->fetch_object();

		if(isset($data->id)){
			$db = new mysql();
			$db->alterar("lojascript_imagem_legenda", array(
				"legenda"=>"$legenda"
			), " id='$data->id' ");
		} else {
			$db = new mysql();
			$db->inserir("lojascript_imagem_legenda", array(
				"id_img"=>"$id",
				"legenda"=>"$legenda"
			));
		}

		$this->irpara(DOMINIO.$this->_controller.'/alterar/codigo/'.$codigo.'/aba/imagem');
	}

	public function apagar_varios(){
		
		$db = new mysql();
		$exec = $db->Executar("SELECT * FROM lojascript ");
		while($data = $exec->fetch_object()){
			
			if($this->post('apagar_'.$data->id) == 1){
 				
				$db = new mysql();
				$exec_img = $db->executar("SELECT * FROM lojascript_imagem where codigo='$data->codigo' ");
				while($data_img = $exec_img->fetch_object()){

					if($data_img->imagem){
						unlink('arquivos/img_lojascript_g/'.$data->codigo.'/'.$data_img->imagem);
						unlink('arquivos/img_lojascript_p/'.$data->codigo.'/'.$data_img->imagem);
					}
					
				}

				$conexao = new mysql();
				$conexao->apagar("lojascript_imagem", " codigo='$data->codigo' ");

				$conexao = new mysql();
				$conexao->alterar("lojascript_anuncios", array( "ativo"=>"1" ), " produto='$data->codigo' ");

				$conexao = new mysql();
				$conexao->apagar("lojascript", " codigo='$data->codigo' ");
				
			}
		}

		$this->irpara(DOMINIO.$this->_controller);
		
	}

	public function grupos(){
		
		$dados['_base'] = $this->base_layout();
		$dados['_titulo'] = $this->_modulo_nome;
		$dados['_subtitulo'] = "Grupos";

		$produtos = new model_lojascript();
		$dados['lista'] = $produtos->grupos();
		
		$this->view('lojascript.grupos', $dados);
	}

	public function novo_grupo(){
		
		$dados['_base'] = $this->base_layout();
		$dados['_titulo'] = $this->_modulo_nome;
 		$dados['_subtitulo'] = "Nova Categoria";


		$this->view('lojascript.grupos.novo', $dados);
	}

	public function novo_grupo_grv(){
		
		$titulo = $this->post('titulo');

		$this->valida($titulo);
		
		$codigo = $this->gera_codigo();

		$db = new mysql();
		$db->inserir("lojascript_grupo", array(
			"codigo"=>"$codigo",
			"titulo"=>"$titulo"
		));
	 	
		$this->irpara(DOMINIO.$this->_controller.'/grupos');
	}

	public function alterar_grupo(){
		
		$dados['_base'] = $this->base_layout();
		$dados['_titulo'] = $this->_modulo_nome;
 		$dados['_subtitulo'] = "Alterar Grupo";

 		$codigo = $this->get('codigo');

 		$produtos = new model_lojascript();
		$dados['data'] = $produtos->seleciona_grupo($codigo);
		if(!$dados['data']) {
			$this->irpara(DOMINIO.$this->_controller.'/grupos');
		}

		$this->view('lojascript.grupos.alterar', $dados);
	}

	public function alterar_grupo_grv(){
		
		$codigo = $this->post('codigo');
		$titulo = $this->post('titulo');		 
		
		$this->valida($codigo);
		$this->valida($titulo);

		$db = new mysql();
		$db->alterar("lojascript_grupo", array(
			"titulo"=>"$titulo"
		), " codigo='$codigo' ");
	 	
		$this->irpara(DOMINIO.$this->_controller.'/grupos');
		
	}

	public function apagar_grupos(){
		
		$db = new mysql();
		$exec = $db->Executar("SELECT * FROM lojascript_grupo ");
		while($data = $exec->fetch_object()){
			
			if($this->post('apagar_'.$data->id) == 1){
				
				$conexao = new mysql();
				$conexao->apagar("lojascript_grupo", " id='$data->id' ");
				
			}
		}

		$this->irpara(DOMINIO.$this->_controller.'/grupos');		
	}

	public function upload(){
		
		//carrega normal
		$dados['_base'] = $this->base_layout();

 		$codigo = $this->get('codigo');
 		$dados['codigo'] = $codigo;
 		
		$this->view('enviar_imagens', $dados);
	}
	
	public function imagem_redimencionada(){

		$codigo = $this->post('codigo');
		
		//pasta onde vai ser salvo os arquivos
		$pasta = "lojascript";
		$diretorio_g = "arquivos/img_".$pasta."_g/".$codigo."/";
		$diretorio_p = "arquivos/img_".$pasta."_p/".$codigo."/";

		//confere e cria pasta se necessario
		if(!is_dir($diretorio_g)) {
			mkdir($diretorio_g);
		}
		if(!is_dir($diretorio_p)) {
			mkdir($diretorio_p);
		}
		
		//carrega model de gestao de imagens
		$img = new model_arquivos_imagens();		 
		
		// Recuperando imagem em base64
		// Exemplo: data:image/png;base64,AAAFBfj42Pj4
		$imagem = $_POST['imagem'];
		$nome_original = $this->post('nomeimagem');

		$nome_foto  = $img->trata_nome($nome_original);
		$extensao = $img->extensao($nome_original);
		
		// Separando tipo dos dados da imagem
		// $tipo: data:image/png
		// $dados: base64,AAAFBfj42Pj4
		list($tipo, $dados) = explode(';', $imagem);
		
		// Isolando apenas o tipo da imagem
		// $tipo: image/png
		list(, $tipo) = explode(':', $tipo);
		
		// Isolando apenas os dados da imagem
		// $dados: AAAFBfj42Pj4
		list(, $dados) = explode(',', $dados);

		// Convertendo base64 para imagem
		$dados = base64_decode($dados);

		// Gerando nome aleatório para a imagem
		$nome = md5(uniqid(time()));

		// Salvando imagem em disco
		if(file_put_contents($diretorio_g.$nome_foto, $dados)) {			
				
				//confere e se jpg reduz a miniatura
				if( ($extensao == "jpg") OR ($extensao == "jpeg") ){
					
					// foto minuatura
					// foto grande
					$largura_g = 1200;
					$altura_g = $img->calcula_altura_jpg($tmp_name, $largura_g);
					$largura_p = 300;
					$altura_p = $img->calcula_altura_jpg($diretorio_g.$nome_foto, $largura_p);
					//redimenciona
					$img->jpg($diretorio_g.$nome_foto, $largura_g , $altura_g , $diretorio_g.$nome_foto);
					$img->jpg($diretorio_g.$nome_foto, $largura_p , $altura_p , $diretorio_p.$nome_foto);
					
				} else {
					
					//caso nao possa redimencionar copia a imagem original para a pasta de miniaturas
					copy($diretorio_g.$nome_foto, $diretorio_p.$nome_foto);
					
				}

				$db = new mysql();
				$db->inserir("lojascript_imagem", array(
					"codigo"=>"$codigo",
					"imagem"=>"$nome_foto"
				));

				$ultid = $db->ultimo_id();

				// confere ordem no produto principal
				$db = new mysql();
				$exec = $db->executar("SELECT * FROM lojascript_imagem_ordem where codigo='$codigo' order by id desc limit 1");
				$data = $exec->fetch_object();
				
				if(isset($data->data)){
					$novaordem = $data->data.",".$ultid;
				} else {
					$novaordem = $ultid;
				}
				
				$db = new mysql();
				$db->inserir("lojascript_imagem_ordem", array(
					"codigo"=>"$codigo",
					"data"=>"$novaordem"
				));
				
				//confere ordem nos anuncios do produto
				$db = new mysql();
				$exec_anuncios = $db->executar("SELECT codigo FROM lojascript_anuncios where produto='$codigo' ");
				while($data_anuncios = $exec_anuncios->fetch_object()){

					// confere ordem no produto principal
					$db = new mysql();
					$exec = $db->executar("SELECT * FROM lojascript_imagem_ordem where codigo='$data_anuncios->codigo' order by id desc limit 1");
					$data = $exec->fetch_object();
					
					if(isset($data->data)){
						$novaordem = $data->data.",".$ultid;
					} else {
						$novaordem = $ultid;
					}
					
					$db = new mysql();
					$db->inserir("lojascript_imagem_ordem", array(
						"codigo"=>"$data_anuncios->codigo",
						"data"=>"$novaordem"
					));
				}

		}

	}

	public function imagem_manual(){

		$arquivo = $_FILES['arquivo'];
		$tmp_name = $_FILES['arquivo']['tmp_name'];

		$codigo = $this->get('codigo');		

		$nome_original = $_FILES['arquivo']['name'];

		//definições de pasta
		$pasta = "lojascript";
		$diretorio_g = "arquivos/img_".$pasta."_g/".$codigo."/";
		$diretorio_p = "arquivos/img_".$pasta."_p/".$codigo."/";
		
		if(!is_dir($diretorio_g)) {
			mkdir($diretorio_g);
		}
		if(!is_dir($diretorio_p)) {
			mkdir($diretorio_p);
		}
		
		$img = new model_arquivos_imagens();
 		
		if($tmp_name) {
	 		
			$nome_foto  = $img->trata_nome($nome_original);
			$extensao = $img->extensao($nome_original);
			
			if(copy($tmp_name, $diretorio_g.$nome_foto)){
				
				if( ($extensao == "jpg") OR ($extensao == "jpeg") ){
					
					// foto grande
					$largura_g = 1200;
					$altura_g = $img->calcula_altura_jpg($tmp_name, $largura_g);
					// foto minuatura
					$largura_p = 300;
					$altura_p = $img->calcula_altura_jpg($tmp_name, $largura_p);
					//redimenciona
					$img->jpg($diretorio_g.$nome_foto, $largura_g , $altura_g , $diretorio_g.$nome_foto);
					$img->jpg($diretorio_g.$nome_foto, $largura_p , $altura_p , $diretorio_p.$nome_foto);

				} else {
					
					copy($diretorio_g.$nome_foto, $diretorio_p.$nome_foto);

				}
				
				
				$db = new mysql();
				$db->inserir("lojascript_imagem", array(
					"codigo"=>"$codigo",
					"imagem"=>"$nome_foto"
				));
				
				$ultid = $db->ultimo_id();

				//ordem principal
				$db = new mysql();
				$exec = $db->executar("SELECT * FROM lojascript_imagem_ordem where codigo='$codigo' order by id desc limit 1");
				$data = $exec->fetch_object();
				
				if(isset($data->data)){
					$novaordem = $data->data.",".$ultid;
				} else {
					$novaordem = $ultid;
				}
				
				$db = new mysql();
				$db->inserir("lojascript_imagem_ordem", array(
					"codigo"=>"$codigo",
					"data"=>"$novaordem"
				));

				//confere ordem nos anuncios do produto
				$db = new mysql();
				$exec_anuncios = $db->executar("SELECT codigo FROM lojascript_anuncios where produto='$codigo' ");
				while($data_anuncios = $exec_anuncios->fetch_object()){

					// confere ordem no produto principal
					$db = new mysql();
					$exec = $db->executar("SELECT * FROM lojascript_imagem_ordem where codigo='$data_anuncios->codigo' order by id desc limit 1");
					$data = $exec->fetch_object();
					
					if(isset($data->data)){
						$novaordem = $data->data.",".$ultid;
					} else {
						$novaordem = $ultid;
					}
					
					$db = new mysql();
					$db->inserir("lojascript_imagem_ordem", array(
						"codigo"=>"$data_anuncios->codigo",
						"data"=>"$novaordem"
					));
				}


			} else {
				$this->msg('Erro ao gravar imagem!');				
			}

			$this->irpara(DOMINIO.$this->_controller."/alterar/codigo/".$codigo."/aba/imagem");
		}
		
	}

	public function anuncios(){
		
		$dados['_base'] = $this->base_layout();
		$dados['_titulo'] = $this->_modulo_nome;
		$dados['_subtitulo'] = "Anúncios";
		
		$produtos = new model_lojascript();
		$dados['lista'] = $produtos->anuncios();
		
		$this->view('lojascript.anuncios', $dados);
	}

	public function alterar_anuncio(){

		$codigo = $this->get('codigo');

		if(!$codigo){
			echo "Anuncio inválido";
			exit;
		}

		$db = new mysql();
		$exec_anuncios = $db->executar("SELECT titulo, produto FROM lojascript_anuncios where codigo='$codigo' ");
		$data_anuncios = $exec_anuncios->fetch_object();

		$dados['anuncio'] = $codigo;
		$dados['produto'] = $data_anuncios->produto;
		$dados['titulo'] = $data_anuncios->titulo;

		$lojascript = new model_lojascript();
		$dados['lista'] = $lojascript->lista();

		$this->view('lojascript.alterar.anuncio', $dados);
	}

	public function alterar_anuncio_grv(){

		$anuncio = $this->post('anuncio');
		$produto = $this->post('produto');

		if($anuncio AND $produto){ } else {
			$this->msg('informações inválidas');
			$this->irpara(DOMINIO.'lojascript/anuncios/');
		}

		if($produto == "novo"){

			require_once('mercadolivre/meli.php');

			if($_SESSION['access_token']){

				$meli = new Meli($_SESSION['access_token'], $_SESSION['refresh_token']);			
				$seller_id = $_SESSION['id_user_mercadolivre'];

				$array = array(
					"access_token"=>$_SESSION['access_token']
				);

				$db = new mysql();
				$exec_anuncios = $db->executar("SELECT * FROM lojascript_anuncios where codigo='$anuncio' ");
				$data_anuncios = $exec_anuncios->fetch_object();
 				
 				$titulo = $data_anuncios->titulo;
 				$valor = $data_anuncios->valor;

 				// descricao
 				$retorno = $meli->get("/items/".$data_anuncios->id_ml."/description", array('access_token' => $_SESSION['access_token']));
 				$descricao_htm = $retorno['body']->text;
 				$descricao_texto = $retorno['body']->plain_text;

 				if($descricao_htm){
 					$descricao = $descricao_htm;
 				} else {
 					$descricao = $descricao_texto;
 				}

 				// dados do anuncio
 				$retorno = $meli->get("/items/".$data_anuncios->id_ml, array('access_token' => $_SESSION['access_token']));

 				// video
 				$video_id = $retorno['body']->video_id;
 				if($video_id){
 					$video = "https://www.youtube.com/watch?v=".$video_id;
 				} else {
 					$video = "";
 				}

 				// cria codigo para o novo produto
				$codigo = $this->gera_codigo();

				$db = new mysql();
				$db->inserir("lojascript", array(
					"codigo"=>"$codigo",
					"titulo"=>"$titulo",
					"descricao"=>"$descricao",
					"valor"=>"$valor",
					"youtube"=>"$video",
					"esconder"=>"0"
				));
			 	
			 	$ultid = $db->ultimo_id();
			 	$id_unico = $ultid.'-'.'produto';
			 	
				$db = new mysql();
				$db->alterar("lojascript", array(
					"id_unico"=>"$id_unico"
				), " codigo='$codigo' ");

				$db = new mysql();
				$db->alterar("lojascript_anuncios", array(
					"produto"=>"$codigo"
				), " codigo='$anuncio' ");


				///////////////
				// array com links das imagens
				$imagens = $retorno['body']->pictures;

				$img = new model_arquivos_imagens();

				$pasta = "lojascript";
				$diretorio_g = "arquivos/img_".$pasta."_g/".$codigo."/";
				$diretorio_p = "arquivos/img_".$pasta."_p/".$codigo."/";

				//confere e cria pasta se necessario
				if(!is_dir($diretorio_g)) {
					mkdir($diretorio_g);
				}
				if(!is_dir($diretorio_p)) {
					mkdir($diretorio_p);
				}

				$novaordem = "";
				foreach ($imagens as $key => $value) {
					
					$imagem_url = $value->url;

					if($content = file_get_contents($imagem_url)){
						
						$nome_foto  = $img->trata_nome_sem_ext($titulo);
						$nome_novo = $nome_foto.'.jpg';

						$fp = fopen($diretorio_g.$nome_novo, "w");
						fwrite($fp, $content);
						fclose($fp);
						
						if(copy($imagem_url, $diretorio_g.$nome_novo)){
							
							$mime = mime_content_type($diretorio_g.$nome_novo);
							$ext = explode('/', $mime);
							$extencao = $ext[1];
							
							$codigo_img = $this->gera_codigo();							
							$nome_certo = $nome_foto.'_'.$codigo_img.'.'.$extencao;
							
							if(copy($diretorio_g.$nome_novo, $diretorio_g.$nome_certo)){

								unlink($diretorio_g.$nome_novo);

							 	if(copy($diretorio_g.$nome_certo, $diretorio_p.$nome_certo)){

									$db = new mysql();
									$db->inserir("lojascript_imagem", array(
										"codigo"=>"$codigo",
										"imagem"=>"$nome_certo"
									));
									$ultid = $db->ultimo_id();
									$novaordem = $novaordem.",".$ultid;
									
								}							 
							}
						}
					}
					
				}
				
				$db = new mysql();
				$db->inserir("lojascript_imagem_ordem", array(
					"codigo"=>"$codigo",
					"data"=>"$novaordem"
				));

				//confere ordem nos anuncios do produto
				$db = new mysql();
				$exec_anuncios = $db->executar("SELECT codigo FROM lojascript_anuncios where produto='$codigo' ");
				while($data_anuncios = $exec_anuncios->fetch_object()){					 
					
					$db = new mysql();
					$db->inserir("lojascript_imagem_ordem", array(
						"codigo"=>"$data_anuncios->codigo",
						"data"=>"$novaordem"
					));
				}

				$this->irpara(DOMINIO.'lojascript/anuncios'); 
				
			} else {
				$this->msg('Faça o login com o MercadoLivre para continuar!');
				$this->volta(1);
			}
			
		} else {

			$db = new mysql();
			$db->alterar("lojascript_anuncios", array(
				"produto"=>"$produto"
			), " codigo='$anuncio' ");

			$this->irpara(DOMINIO."lojascript/anuncios");
		}

	}

	public function novo_anuncio(){
		
		$dados['_base'] = $this->base_layout();
		$dados['_titulo'] = $this->_modulo_nome;

		$dados['produto'] = $this->get('codigo');

		$produtos = new model_mercadolivre();
 		$dados['categorias'] = $produtos->categorias();

		$this->view('lojascript.novo.anuncio', $dados);
	}

	public function novo_anuncio_grv(){
		
		$produto = $this->post('produto');
		$titulo = $this->post('titulo');
		$categoria = $this->post('categoria');

		$this->valida($produto);
		$this->valida($categoria);
		
		$codigo = $this->gera_codigo();

		$db = new mysql();
		$db->inserir("lojascript_anuncios", array(
			"codigo"=>"$codigo",
			"produto"=>"$produto",
			"categoria"=>"$categoria",
			"ativo"=>"0"
		));
	 	
		$this->irpara(DOMINIO.$this->_controller."/alterar/codigo/".$produto."/aba/anuncios");
	}

	public function remover_anuncio(){

		$codigo = $this->get('codigo');
		$produto = $this->get('produto');

		$db = new mysql();
		$db->alterar("lojascript_anuncios", array(
			"ativo"=>"1"
		), " codigo='$codigo' ");

		$this->irpara(DOMINIO.$this->_controller."/alterar/codigo/".$produto."/aba/anuncios");
	}

	public function alterar_anuncio_ml(){

		$codigo = $this->get('codigo');
		$produto = $this->get('produto');
		
		$titulo = $this->post('titulo');

		$this->valida($titulo);

		$db = new mysql();
		$db->alterar("lojascript_anuncios", array(
			"titulo"=>"$titulo",
			"atualizado"=>"0"
		), " codigo='$codigo' ");
		
		$this->irpara(DOMINIO.$this->_controller."/alterar/codigo/".$produto."/aba/anuncios");
	}
	
	public function template_ml(){
		
		$codigo = $this->get('codigo');
		
		$mercadolivre = new model_mercadolivre();
		$config = $mercadolivre->config();
		
		$produto = new model_lojascript();
		$data = $produto->seleciona($codigo);
		
 		$conteudo = str_replace("%descricao%", $data->descricao, $config->template);
 		
 		echo "
 		<div style='padding:30px;'>
 			<textarea style='width:500px; height:300px;' >$conteudo</textarea>
 		</div>
 		<hr>
 		";
 		
 		echo $conteudo;
 		
	}
	
	
//termina classe
}