<?php
class system {
    
    private $_url;
    private $_explode;
    public $_controller;
    public $_action;
    public $_params;
    public $_sessao;
    public $_autenticado = false;

    public function __construct() {
        $this->setSessao();
        $this->setUrl();
        $this->setExplode();
        $this->setController();
        $this->setAction();
        $this->setParams();        
    }

    private function setUrl() {
        $string = str_replace(array("<", ">", "\\", "=", "?", "#"), "", $_GET['url']);
        $string = trim($string);
        $string = strip_tags($string);
        $string = (get_magic_quotes_gpc()) ? $string : addslashes($string);
        $string = ($string == null ? 'inicial' : $string);
        $this->_url = $string;
    }

    private function setExplode(){
        $this->_explode = explode('/', $this->_url);
    }

    private function setController(){
        $this->_controller = $this->_explode[0];
    }

    private function setAction(){
        $string = (!isset($this->_explode[1]) || $this->_explode == "inicial" ? "inicial" : $this->_explode[1]);
        $string = ($string == null ? 'inicial' : $string);
        $this->_action = $string;
    }

    private function setParams(){
        unset($this->_explode[0], $this->_explode[1] );
        
        if( end($this->_explode) == null ) array_pop( $this->_explode );

        $i = 0;
        if( !empty($this->_explode) ){
            foreach ( $this->_explode as $val) {
                if( $i % 2 == 0 ){
                    $ind[] = $val;
                } else {
                    $value[] = $val;
                }
                $i++;
            }
        } else {
            $ind = array();
            $value = array();
        }

        if( isset($ind) AND isset($value) ){
            if( count($ind) == count($value) && !empty($ind) && !empty($value)){
                $this->_params = array_combine($ind, $value);
            } else {
                $this->_params = array();
            }
        } else {
            $this->_params = array();
        }
    }

    public function setSessao(){
        if(!isset($_SESSION['sessao']) ){
            $cod_sessao = substr(time().rand(10000,99999),-15);
            $this->_sessao = $cod_sessao;
            $_SESSION['sessao'] = $cod_sessao;
        } else {
            $this->_sessao = $_SESSION['sessao'];
        }
    }

    public function get($name){
        if(isset($this->_params[$name])){
            return $this->_params[$name];
        } else {
            return '';
        }
    }

    public function post($name){
        if(isset($_POST[$name])){
            $string = str_replace(array("<", ">", "\\", "//", "#"), "", $_POST[$name]);
            $string = trim($string);
            $string = strip_tags($string);
            $string = (get_magic_quotes_gpc()) ? $string : addslashes($string);
            return trim($string);
        } else {
            return '';
        }
    }
    
    public function irpara($endereco, $destino = '_self'){
        echo "<script> window.open('".$endereco."', target='$destino');</script>";
        exit();
    }

    public function volta($n){
        echo "<script> history.go(-".$n."); </script>";
        exit();
    }

    public function msg($msg){
        echo "<script> alert('".$msg."'); </script>";
    }

    public function erro(){

        $logo = new model_logo();
        $_logo = $logo->endereco;
        $_logo_b = $logo->endereco2;

        require_once('_views/htm_erro.php');
        exit;
    }
    
    public function run(){
        
        $controllers_path = CONTROLLERS.'controller_'.$this->_controller.'.php';
        
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //teste de url
        //echo "Controller: $this->_controller <br> Action = $this->_action <br></br> Parametros: "; print_r($this->_params); exit;
        //echo $controllers_path; exit;
        
        if(!file_exists($controllers_path)){
            //nao existe o arquivo do controller na pasta
            $this->erro();
        } else {
            
            require_once($controllers_path);
            $app = new $this->_controller();
            $app->init();
            $action = $this->_action;
            if(!method_exists($app, $action) ){
                //erro caso o metodo nao exista
                $this->erro();
            } else {
                $app->$action();
            }
        }
    }
}