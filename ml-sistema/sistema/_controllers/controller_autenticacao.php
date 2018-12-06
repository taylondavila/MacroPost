<?php
class autenticacao extends controller {
	
	protected function inicial(){
		
		$logo = new model_logo();
		$dados['_logo'] = $logo->endereco;
		$dados['_logo_b'] = $logo->endereco2;
		
		$this->view('entrar', $dados);
		
	}
	
	protected function login(){
		
		$usuario = $this->post('usuario');
		$senha = $this->post('senha');

		$this->valida($usuario, 'Digite o usuário!');
		$this->valida($senha, 'Digite o usuário!');		 
		
		$time = time();
		$ip = $_SERVER["REMOTE_ADDR"];
		
		//grava log
		$db = new mysql();
		$exex = $db->inserir("adm_historico_acesso", array(
			"data"=>"$time",
			"ip"=>"$ip",
			"usuario"=>"$usuario"
		));
		$id_log = $db->ultimo_id(); 
		
		$username = md5($usuario);
		$password = md5($senha);
		
		$db = new mysql();
		$exec = $db->executar("SELECT codigo, usuario FROM adm_usuario WHERE usuario='$username' AND senha='$password' ");
		
		if($exec->num_rows != 1){
			$this->msg('Usuário ou senha incorretos!');
			$this->volta(1);
		} else {
			
			$data = $exec->fetch_object();
			
			$_SESSION['autenticacao'] = true;
			$_SESSION['cod_usuario'] = $data->codigo;
			$_SESSION['usuario'] = $data->usuario;
			
			if(isset($_SESSION['pagina_acionada'])){
				//$this->irpara($_SESSION['pagina_acionada']);
				$this->irpara( DOMINIO );
			} else {
				$this->irpara( DOMINIO );
			}
		}
		
 	}

 	protected function logout(){
 		session_destroy();
 		$this->irpara( DOMINIO );
 	}
	
}