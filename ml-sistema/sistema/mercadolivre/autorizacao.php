<?php
header('Content-Type: text/html; charset=UTF-8');
date_default_timezone_set("Brazil/East");
		
		require_once('../../_config.php');

		//////////////////////////////////////////////////////////////
		// Definiçoes

		if($_SERVER['HTTP_HOST'] == 'localhost'){
		    define("SERVIDOR", $config['SERVIDOR_LOCAL']);
		    define("USUARIO", $config['USUARIO_LOCAL']);
		    define("SENHA", $config['SENHA_LOCAL']);
		    define("BANCO", $config['BANCO_LOCAL']);
		} else {
		    define("SERVIDOR", $config['SERVIDOR']);
		    define("USUARIO", $config['USUARIO']);
		    define("SENHA", $config['SENHA']);
		    define("BANCO", $config['BANCO']);
		}
		require_once('../_system/mysql.php');
		
		if($_SERVER['HTTP_HOST'] == 'localhost'){
			$config_dominio = "https://".$_SERVER['HTTP_HOST']."/".$config['PASTA_LOCAL']."/";
		} else {
		    if($config['PASTA']){
			    $config_dominio = "https://".$_SERVER['HTTP_HOST']."/".$config['PASTA']."/"; 
			} else {
				$config_dominio = "https://".$_SERVER['HTTP_HOST']."/";
			}
		}

		define("DOMINIO", $config_dominio);
		
		function webservice($endereco_requisicao){

			$header = array();

			$ch = curl_init($endereco_requisicao);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_TIMEOUT, 900);
			$response = curl_exec($ch);
			$json = json_decode($response);
			$resposta_http = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			return $json;
		}

		session_start();

		//////////////////////////////////////////////////////////////
		
		$db = new mysql();
		$exec = $db->executar("SELECT * FROM mercadolivre_config WHERE id='1' ");
		$data = $exec->fetch_object();		 

		require_once('meli.php');

		$meli = new Meli($data->app_id, $data->secret_key, $_SESSION['access_token'], $_SESSION['refresh_token']);

		if($_GET['code'] || $_SESSION['access_token']) {
			
			// If code exist and session is empty
			if($_GET['code'] && !($_SESSION['access_token'])) {
				// If the code was in get parameter we authorize
				$user = $meli->authorize($_GET['code'], DOMINIO."sistema/mercadolivre/autorizacao.php");
				
				// Now we create the sessions with the authenticated user
				$_SESSION['access_token'] = $user['body']->access_token;
				$_SESSION['expires_in'] = time() + $user['body']->expires_in;
				$_SESSION['refresh_token'] = $user['body']->refresh_token;
				
				$db = new mysql();
				$db->alterar("mercadolivre_config", array(
					"ultimo_token"=>$_SESSION['access_token'],
					"ultimo_refresh_token"=>$_SESSION['refresh_token']
				), " id='1' ");
				
			} else {
				// We can check if the access token in invalid checking the time
				if($_SESSION['expires_in'] < time()) {
					try {
						// Make the refresh proccess
						$refresh = $meli->refreshAccessToken();
						
						// Now we create the sessions with the new parameters
						$_SESSION['access_token'] = $refresh['body']->access_token;
						$_SESSION['expires_in'] = time() + $refresh['body']->expires_in;
						$_SESSION['refresh_token'] = $refresh['body']->refresh_token;
						
						$db = new mysql();
						$db->alterar("mercadolivre_config", array(
							"ultimo_token"=>$_SESSION['access_token'],
							"ultimo_refresh_token"=>$_SESSION['refresh_token']
						), " id='1' ");
						
					} catch (Exception $e) {
					  	echo "Exception: ",  $e->getMessage(), "\n";
					}
				}
			}
			
		}	

		$ml_nome = "";

		if($_SESSION['access_token']){

			// usando curl
			//$retorno = webservice("https://api.mercadolibre.com/users/me?access_token=".$_SESSION['access_token']);

			// api mercado livre
			$retorno = $meli->get('/users/me', array('access_token' => $_SESSION['access_token']));
			
			//echo '<pre>'; print_r($retorno); echo '</pre>';
			
			$_SESSION['id_user_mercadolivre'] = $retorno['body']->id;
			$ml_nome = $retorno['body']->nickname;
			
		}

		//echo '<pre>'; print_r($_SESSION); echo '</pre>';

?>

<div style="width:100%; text-align: center; padding-top:20px; "><img src="<?=DOMINIO?>sistema/mercadolivre/ml.jpg" style="width: 200px;"></div>

<div style="padding: 20px; text-align: center;">Autorização do uso da aplicação na conta do mercado livre</div>

<div style="padding: 20px; text-align: center;">

	<?php if($_SESSION['access_token']){ ?>
	    <p><strong>Logado como,</strong> <?=$ml_nome?>
	    </p>
	    <p><a href="<?=DOMINIO?>sistema/lojascript">Ir para os Produtos</a>
	    </p>
    <?php } else { ?>
		<div>
			<a href="<?=$meli->getAuthUrl(DOMINIO."sistema/mercadolivre/autorizacao.php", Meli::$AUTH_URL['MLB'])?>">Acessar com a conta do MercadoLivre</a>
		</div>
	<?php } ?>

</div>