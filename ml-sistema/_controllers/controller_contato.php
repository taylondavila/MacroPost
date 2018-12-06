<?php
class contato extends controller {
	
	public function init(){
		
	}
	
	public function inicial(){
		
		//definições basicas (OBS: tudo que estiver na array dados é enviado como variavel para a view)
		$layout = new model_layout();
		$dados['_base'] = $layout->carrega();
		$dados['objeto'] = DOMINIO.$this->_controller.'/';
		$dados['controller'] = $this->_controller;
			
		
		//carrega view e envia dados para a tela
		$this->view('contato', $dados);
	}

	public function captcha(){

		$codigoCaptcha = substr(md5( time()) ,0,5);

		$_SESSION['captcha'] = $codigoCaptcha;

		$imagemCaptcha = imagecreatefrompng("_views/img/fundocaptch.png");

		$fonteCaptcha = imageloadfont("_views/img/anonymous.gdf");

		$corCaptcha = imagecolorallocate($imagemCaptcha,255,0,0);

		imagestring($imagemCaptcha,$fonteCaptcha,15,5,$codigoCaptcha,$corCaptcha);

		header("Content-type: image/png");

		imagepng($imagemCaptcha);

		imagedestroy($imagemCaptcha);
		exit;
	}
	
	public function enviar(){
		
		$nome = $this->post('nome');
		$email = $this->post('email');
		$fone = $this->post('fone');
		$mensagem = $this->post('msg');
		
		$captcha = $this->post('captcha');
		if($_SESSION['captcha'] != $captcha){			
			echo "Código inválido, tente novamente!";
			exit;
		}
		
		/* mensagem */
		$msg = "<div style='padding:10px; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000;'><p>Contato enviado pelo Website</p></div>";	
		$msg .= "<div style='padding:10px; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000;'><p><strong>Nome:</strong> ".$nome."</p></div>";
		$msg .= "<div style='padding:10px; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000;'><p><strong>E-mail:</strong> ".$email."</p></div>";
		$msg .= "<div style='padding:10px; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000;'><p><strong>Telefone:</strong> ".$fone."</p></div>";
		$msg .= "<div style='padding:10px; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000;'><p><strong>Mensagem:</strong> ".$mensagem."</p></div>";
		
		//pega do banco o email de destino cadastrado no painel
		$db = new mysql();
		$exec = $db->executar("select * from contato WHERE codigo='147017157989381' ");
		$data = $exec->fetch_object();
		
		$destino = $data->email;
		
		$db = new mysql();
		$exec = $db->executar("SELECT * FROM adm_config WHERE id='1' ");
		$data_config = $exec->fetch_object();

		require_once("_api/phpmailer/class.phpmailer.php");
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->Host = $data_config->email_host;
		$mail->Port = $data_config->email_porta;
		$mail->SMTPAuth = true;
		$mail->Username = $data_config->email_usuario;
		$mail->Password = $data_config->email_senha;
		$mail->From = $data_config->email_origem;
		$mail->FromName = $data_config->email_nome;
		$mail->AddAddress($destino, "");
		$mail->WordWrap = 50;
		$mail->IsHTML(true); //enviar em HTML
		$mail->AddReplyTo("$email", "");
		$mail->Subject = "Contato website";
		$mail->Body = utf8_decode($msg);
		
		if($mail->Send()){
			echo "<div style='text-align:center; font-size:16px;' >Muito obrigado!<br><br>Sua mensagem foi enviada com sucesso!<br><br>Em breve nossa equipe entrará em contato!</div>"; exit;
		} else {
			echo "Erro ao enviar mensagem!"; exit;
		}
		 
	}
	
	
}





 

 