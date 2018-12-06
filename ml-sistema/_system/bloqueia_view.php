<?php
if(!defined('TOKEN')){
	$dominio = "http://".$_SERVER['HTTP_HOST']."/";
	echo "<script>window.location='".$dominio."';</script>";
	exit();
}