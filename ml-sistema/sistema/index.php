<?php
header('Content-Type: text/html; charset=UTF-8');
date_default_timezone_set("Brazil/East");

require_once('../_config.php');

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Definiçoes de Pastas
if($config['SSL']){ 
    
    // deixa apenas conexão com https
    
    if($_SERVER['HTTP_HOST'] == 'localhost'){
    	$config_dominio = "https://".$_SERVER['HTTP_HOST']."/".$config['PASTA_LOCAL']."/";
    } else {
        if($config['PASTA']){
    	    $config_dominio = "https://".$_SERVER['HTTP_HOST']."/".$config['PASTA']."/"; 
    	} else {
    		$config_dominio = "https://".$_SERVER['HTTP_HOST']."/";
    	}
    }
    
} else {
    
    if($_SERVER['HTTP_HOST'] == 'localhost'){
        $config_dominio = "http://".$_SERVER['HTTP_HOST']."/".$config['PASTA_LOCAL']."/";
    } else {
        if($config['PASTA']){
            $config_dominio = "http://".$_SERVER['HTTP_HOST']."/".$config['PASTA']."/"; 
        } else {
            $config_dominio = "http://".$_SERVER['HTTP_HOST']."/";
        }
    }
    
}

define("DOMINIO", $config_dominio."sistema/");
define("PASTA_CLIENTE", $config_dominio."sistema/arquivos/");

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Outras Definições
define("AUTOR", "ComprePronto.com.br");
define("TOKEN", md5($config['TOKEN']) );
define("TITULO_VIEW", "Gerenciador Web");

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//SISTEMA
session_start();

define("CONTROLLERS", '_controllers/'); 
define("VIEWS", '_views/');
define("MODELS", '_models/');

define("LAYOUT", $config_dominio."sistema/".VIEWS);
define("FAVICON",  LAYOUT.'img/ico.png');

require_once('_system/system.php');
require_once('_system/mysql.php');
require_once('_system/controller.php');
require_once('_system/model.php');

//carrega os models automaticamente
function __autoload( $arquivo ){
    if(file_exists(MODELS.$arquivo.".php")){
      require_once(MODELS.$arquivo.".php");
    } else {
        echo "Erro: O Model '".$arquivo."' não foi encontrado!";
        exit;
    }
}

$start = new system();
$start->run();