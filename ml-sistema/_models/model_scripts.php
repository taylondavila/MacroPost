<?php

Class model_scripts extends model{
    
	public $perpage = 10; //itens por pagina
	public $numlinks = 10; //total de paginas mostradas na paginação
	public $busca = '-';
	public $categoria = 0;
	public $startitem = 0;
	public $startpage = 1;
	public $endpage = '';
	public $reven = 1;
	public $destaque = 0;
	public $ordem = ''; // 'rand' para randomico ou em branco para data desc

    public function lista(){
    	
    	//define variaveis
		$perpage = $this->perpage;
		$numlinks = $this->numlinks;
		$busca = $this->busca;
		$categoria = $this->categoria;
		$startitem = $this->startitem;
		$startpage = $this->startpage;
		$endpage = $this->endpage;
		$reven = $this->reven;
		$destaque = $this->destaque;
		$ordem = $this->ordem;
		
		//retorno 
		$dados = array();

    	//FILTROS
		$query = "SELECT * FROM lojascript WHERE esconder='0' ";

		//se tiver busca ignora tudo e faz a busca
		if($busca != "-"){
		    $query = "SELECT * FROM lojascript WHERE esconder='0' AND ( titulo LIKE '%$busca%' OR previa LIKE '%$busca%' ) ";
		} else {

			//se selecionou a categoria tem prioridade sobre o destaque
			if($categoria != 0){
				$query = "SELECT * FROM lojascript WHERE esconder='0' AND grupo='$categoria' ";
			}

		}
		
		//faz a busca no banco e retorno numero de itens para paginação
		$conexao = new mysql();
		$coisas_scripts = $conexao->Executar($query);
		if($coisas_scripts->num_rows) {
		  $numitems = $coisas_scripts->num_rows;
		} else {
		  $numitems = 0;
		}
		$dados['numitems'] = $numitems;
		
		
		//calcula paginação
		if($numitems > 0) {
		  $numpages = ceil($numitems / $perpage); 
		  if($startitem + $perpage > $numitems) { $enditem = $numitems; } else { $enditem = $startitem + $perpage; }
		  if(!$startpage) { $startpage = 1; }
		  if(!$endpage) { 
		    if($numpages > $numlinks) { $endpage = $numlinks; } else { $endpage = $numpages; }
		  }
		} else {
		  $numpages = 0;
		}

		$valores = new model_valores();

		$lista = array();

		//ordena e limita aos itens da pagina
		if($ordem == 'rand'){
			$query .= " ORDER BY RAND() LIMIT $startitem, $perpage";
		} else {
			$query .= " ORDER BY id desc LIMIT $startitem, $perpage";
		}

		$conexao = new mysql();
		$coisas_script = $conexao->Executar($query);
		$n = 0;
		while($data_script = $coisas_script->fetch_object()){
			
			//seta imagem como não existente
			$imagem = "";
			
			//confere se tem imagem ordenada
			$conexao = new mysql();
			$coisas_ordem = $conexao->Executar("SELECT * FROM lojascript_imagem_ordem WHERE codigo='$data_script->codigo' ORDER BY id desc limit 1");
			$data_ordem = $coisas_ordem->fetch_object();
			
			//se tiver ordem segue o baile
			if(isset($data_ordem->data)){

				$order = explode(',', $data_ordem->data);

				$ii = 0;
				foreach($order as $key => $value){

					$conexao = new mysql();
					$coisas_img = $conexao->Executar("SELECT imagem FROM lojascript_imagem WHERE id='$value'");
					$data_img = $coisas_img->fetch_object();

					//pega primeira imagem da ordem e coloca na variavel
					if( ($ii == 0) AND (isset($data_img->imagem)) ){

						$imagem = PASTA_CLIENTE."img_lojascript_g/".$data_script->codigo."/".$data_img->imagem;

					$ii++;
					}
				}
			}

			$lista[$n]['imagem'] = $imagem;

			//verifica nome do grupo
			$conexao = new mysql();
			$coisas_script_cat = $conexao->Executar("SELECT titulo FROM lojascript_grupo WHERE codigo='$data_script->grupo'");
			$data_script_cat = $coisas_script_cat->fetch_object();

			$lista[$n]['grupo'] = $data_script_cat->titulo;
			$lista[$n]['grupo_codigo'] = $data_script->grupo;
			
			//restante
			$lista[$n]['id'] = $data_script->id;
			$lista[$n]['codigo'] = $data_script->codigo;
			$lista[$n]['titulo'] = $data_script->titulo;
			$lista[$n]['valor'] = $valores->trata_valor($data_script->valor);
			$lista[$n]['previa'] = $data_script->previa;
			$lista[$n]['descricao'] = $data_script->descricao;
			
			$lista[$n]['endereco'] = DOMINIO."produto/".$data_script->id;
			
		$n++;
		}
		$dados['lista'] = $lista;

		//lista paginação
		$paginacao = "<ul class='pagination'>";

		if($numpages > 1) { 
			if($startpage > 1) {
				$prevstartpage = $startpage - $numlinks;
				$prevstartitem = $prevstartpage - 1;
				$prevendpage = $startpage - 1;

				$link = DOMINIO."loja/lista/categoria/$categoria/busca/$busca/";
				$link .= "startitem/$prevstartitem/startpage/$prevstartpage/endpage/$prevendpage/reven/$prevstartpage/";

            }

			for($n = $startpage; $n <= $endpage; $n++) {

				$nextstartitem = ($n - 1) * $perpage;

				if($n != $reven) {
					
					$link = DOMINIO."loja/lista/categoria/$categoria/busca/$busca/";
					$link .= "startitem/$nextstartitem/startpage/$startpage/endpage/$endpage/reven/$n/";
					$paginacao .= "<li><a href='$link' >&nbsp;$n&nbsp;</a></li>";
					
				} else {
					$paginacao .= "<li><a href='#' class='active' >&nbsp;$n&nbsp;</a></li>";
				}
			}
			
			if($endpage < $numpages) {

				$nextstartpage = $endpage + 1;

				if(($endpage + $numlinks) < $numpages) { 
					$nextendpage = $endpage + $numlinks; 
				} else {
					$nextendpage = $numpages;
				}

				$nextstartitem = ($n - 1) * $perpage;

				$link = DOMINIO."loja/lista/categoria/$categoria/busca/$busca/";
				$link .= "startitem/$nextstartitem/startpage/$nextstartpage/endpage/$nextendpage/reven/$nextstartpage/";
			}
		}
		$paginacao .= "</ul>";

		$dados['paginacao'] = $paginacao;

		//retorna para a pagina a array com todos as informações
		return $dados;
	}


	//trata nome para url
	public function trata_url_titulo($titulo){

		//remove acentos
		$titulo_tratado = preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"), $titulo);

		//remove caracteres indesejados
		$titulo_tratado = str_replace(array("?", ",", ".", "+", "'", "/", ")", "(", "&", "%", "#", "@", "!", "=", ">", "<", ";", ":", "|", "*", "$"), "", $titulo_tratado);
		//coloca ifen para separar palavras
		$titulo_tratado = str_replace(array(" ", "_", "+"), "-", $titulo_tratado);
		//certifica que não tem ifens repetidos
		$titulo_tratado = preg_replace('/(.)\1+/', '$1', $titulo_tratado);		 

		return $titulo_tratado;
	}


	public function grupos(){
    	
    	//lista categorias para lateral
		$categorias = array();
		$conexao = new mysql();
		$coisas_categorias = $conexao->Executar("SELECT * FROM lojascript_grupo order by titulo asc");
		$n = 0;
		while($data_categorias = $coisas_categorias->fetch_object()){ 
			
			$categorias[$n]['codigo'] = $data_categorias->codigo;			 
			$categorias[$n]['titulo'] = $data_categorias->titulo; 
			
		$n++;
		}
		
		//retorna para a pagina a array com todos as informações
		return $categorias;
	}
	
	public function titulo_grupo($codigo){
    	
		$conexao = new mysql();
		$coisas_categorias = $conexao->Executar("SELECT titulo FROM lojascript_grupo where codigo='$codigo' ");
		$data_categorias = $coisas_categorias->fetch_object();
		
		return $data_categorias->titulo;
	}

	public function config(){

		$db = new mysql();		 
		$exec = $db->executar("SELECT * FROM lojascript_config WHERE id='1' ");
		if($exec){
			return $exec->fetch_object();
		} else {
			return false;
		}
	}

	public function endereco($produto){

		$endereco = "";

		$conexao = new mysql();
		$coisas = $conexao->Executar("SELECT * FROM lojascript_anuncios WHERE produto='$produto' AND ativo='0' order by vendidos desc");
		$n = 0;
		while($data = $coisas->fetch_object()){ 
			
			if( ($n == 0) AND ($data->endereco) AND ($data->id_ml) ){
				$endereco = $data->endereco;
			$n++;
			}

		}
		return $endereco;
	}


}