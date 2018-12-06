<?php

class perfil extends controller {
	
	protected $_modulo_nome = "Usuários";

	public function init(){
		$this->autenticacao();
	}


	public function inicial(){
		
		$dados['_base'] = $this->base_layout();
		$dados['_titulo'] = $this->_modulo_nome;
 		$dados['_subtitulo'] = "";
 		
 		$dados['data'] = $this->_dados_usuario;
 		
		if($this->get('aba')){
			$dados['aba_selecionada'] = $this->get('aba');
		} else {
			$dados['aba_selecionada'] = 'informacoes';	
		}
		
		$dados['listamenu'] = $this->lista_menu();
		
		$this->view('perfil', $dados);
	}


	public function alterar_grv(){

		$nome = $this->post('nome');
		$email_recuperacao = $this->post('email_recuperacao');
		
		$valida = new model_valida();
		if(!$valida->email($email_recuperacao)){
			$this->msg('E-mail inválido!');
		}
		$this->valida($nome);
		
		$db = new mysql();
		$db->alterar("adm_usuario", array(
			"nome"=>"$nome",
			"email_recuperacao"=>"$email_recuperacao"
		), " codigo='$this->_cod_usuario' ");  

		$this->irpara(DOMINIO.$this->_controller.'/inicial/aba/informacoes');
	}


	public function apagar_imagem(){

		if($this->_dados_usuario->imagem){
			unlink('arquivos/img_usuarios/'.$this->_dados_usuario->imagem);
		}

		$db = new mysql();
		$db->alterar("adm_usuario", array(
			"imagem"=>""
		), " codigo='$this->_cod_usuario' "); 

		$this->irpara(DOMINIO.$this->_controller.'/inicial/aba/imagem');
	}


	public function alterar_senha(){
		
		$usuario = $this->post('usuario');
		$senha = $this->post('senha');
	 	
		$this->valida($usuario);
		$this->valida($senha);		 

		$usuario_md5 = md5($usuario);
		$senha_md5 = md5($senha);

		$db = new mysql();
		$db->alterar("adm_usuario", array(
			"usuario"=>"$usuario_md5",
			"senha"=>"$senha_md5"
		), " codigo='$this->_cod_usuario' ");

		$this->msg('Senha alterada com sucesso!');
		$this->irpara(DOMINIO.$this->_controller.'/inicial/aba/senha');
	}


	public function imagem(){

		$arquivo_original = $_FILES['arquivo'];
		$tmp_name = $_FILES['arquivo']['tmp_name'];
		
		//// Definicao de Diretorios / 
		$diretorio = "arquivos/img_usuarios/";
		
		if(substr($_FILES['arquivo']['name'],-3)=="exe" || 
				substr($_FILES['arquivo']['name'],-3)=="php" || 
				substr($_FILES['arquivo']['name'],-4)=="php3" || 
				substr($_FILES['arquivo']['name'],-4)=="php4"){
				
				$this->msg('Não é permitido enviar arquivos com esta extenção!');
				$this->volta(1);
			
		} else {				 

				$ext1 = $_FILES['arquivo']['name'];
				$ext2 = explode(".", $ext1); 
				$ext3 = strtolower(end($ext2)); 
				
				$icode = substr(time().rand(10000,99999),-15);				
				$nome_arquivo = sha1(uniqid($icode)).".".$ext3;
				
				$destino = $diretorio.$nome_arquivo;
				
				if(copy($tmp_name, $destino)){
					
					//so redimenciona se for jpg
					if($ext2 == "jpg"){						 

						$larg_ft = 800;
						
						$source = imagecreatefromjpeg($tmp_name); 
						$imagex = imagesx($source);
						$imagey = imagesy($source);
						$newy_ft = round(($larg_ft * $imagey) / $imagex);

						require_once('api/redimenciona/redimenciona.php');
						$redimenciona = new model_redimenciona();
						$redimenciona->reduz_imagem_jpg($destino, $larg_ft , $newy_ft , $destino);

					}
					
					$db = new mysql();
					$db->alterar("adm_usuario", array(
						"imagem"=>"$nome_arquivo"
					), " codigo='$this->_cod_usuario' "); 
					
					$this->irpara(DOMINIO.$this->_controller.'/inicial/aba/imagem');
					
				} else {
					
					$this->msg('Não foi possível copiar o arquivo!');
					$this->volta(1);

				}
				
			}

	}
	

	public function ordem(){

		$list = $this->post('list');
		$output = array();
		parse_str($list, $output);
		$ordem = implode(',', $output['item']);

		$db = new mysql();
		$db->apagar("adm_setores_ordem", " usuario='$this->_cod_usuario' ");
		
		$db = new mysql();
		$db->inserir("adm_setores_ordem", array(
			"usuario"=>"$this->_cod_usuario",
			"data"=>"$ordem"
		));

	}


}