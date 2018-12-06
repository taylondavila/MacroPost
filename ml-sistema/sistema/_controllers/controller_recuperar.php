<?php

class recuperar extends controller {
	
	protected function inicial(){
		
		$logo = new model_logo();
		$dados['_logo'] = $logo->endereco;
		$dados['_logo_b'] = $logo->endereco2;
		
		$this->view('recuperar', $dados);
		
	}

	protected function enviar(){

		//confere se foi digitado
		$valida = new model_valida();

		$email = $this->post('email');
		if(!$valida->email($email)){
			$this->msg('E-mail inválido!');
			$this->volta(1);
		}
		
		$db = new mysql();
		$exec = $db->executar("SELECT * FROM adm_usuario WHERE email_recuperacao='$email' ");

		if($exec->num_rows != 0){

			$lista_de_contas = '';
			$conta_n = 0;
			while($data_users = $exec->fetch_object()){
				
				$usuario = "usuario_".$data_users->id;
				$senha = rand(11111, 99999);
				
				$usuario_md5 = md5($usuario);
				$senha_md5 = md5($senha);

				$db = new mysql();
				$altera = $db->alterar("adm_usuario", array(

					"usuario" => "$usuario_md5",
					"senha" => "$senha_md5"
					
				), " id='$data_users->id' ");
				
				$conta_n++;

				$lista_de_contas .= "
				<div style='font-size:13px; color:#000;'><p></p></div>
				<div style='font-size:13px; color:#000;'><p>---</p></div>
				<div style='font-size:13px; color:#000;'><p><strong>Conta $conta_n:</strong> $data_users->nome</p></div>
				<div style='font-size:13px; color:#000;'><p><strong>Novo usuário:</strong> $usuario</p></div>
				<div style='font-size:13px; color:#000;'><p><strong>Nova senha:</strong> $senha</p></div>
				";

			}

			$msg = "
			<div style='font-size:13px; color:#000;'><p><strong>Solicitação de recuperação de senha Sistema GestorNuvem </strong></p></div>
			<div style='font-size:13px; color:#000;'><p></p></div>
			<div style='font-size:13px; color:#000;'><p>Foram emcontrada(s) $conta_n conta(s) vinculadas a este e-mail!</p></div>
			$lista_de_contas
			<div style='font-size:13px; color:#000;'><p></p></div>
			<div style='font-size:13px; color:#000;'><p>-</p></div>
			<div style='font-size:13px; color:#000;'><p>-</p></div>
			<div style='font-size:13px; color:#000;'><p>-</p></div>
			<div style='font-size:13px; color:#000;'><p>Este e-mail foi gerado automáticamente, por favor não responda.</p></div>
			";

			$enviar = new model_envia_email();
			$enviar->destino($email);
			$enviar->assunto('Recuperação de Conta');
			$enviar->conteudo($msg);
			if($enviar->enviar()){
				$this->msg('O email de recuperação de conta foi enviado com sucesso!');
			} else {
				$this->msg('Ocorreu um erro ao enviar email, tente novamente mais tarde!');
			}

			$this->irpara(DOMINIO."autenticacao");

		} else {
			$this->msg('Não encontramos nenhuma conta vinculada a este e-mail!');
			$this->volta(1);
		}

 	}

}