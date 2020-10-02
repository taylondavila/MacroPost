<?php 
//OBSERVAÇÕES
//O gerenciador tem uma programação independente do site ele esta ligado ao site apenas pelo banco de dados e este arquivo de configuração portanto não é necessário outra configurações alem deste arquivo para utilizar o script, apenas se quiser editar o sistema.

$config = array();
/////////////////////////////////////////////////////////////////////////////////////////////////
// Configrações de base de dados do servidor (coloque aqui os dados do banco de dados MYSQL da sua hospedagem)
$config['SERVIDOR'] = "mysql.hostinger.com.br";
$config['USUARIO'] = "u457172881_ml";
$config['SENHA'] = "romney0720";
$config['BANCO'] = "u457172881_ml";

/////////////////////////////////////////////////////////////////////////////////////////////////
// Configrações de base de dados local (apenas para trabalhar em ambiente local)
$config['SERVIDOR_LOCAL'] = "localhost";
$config['BANCO_LOCAL'] = "mldb";
$config['USUARIO_LOCAL'] = "root";
$config['SENHA_LOCAL'] = "";

/////////////////////////////////////////////////////////////////////////////////////////////////
// Configrações de Pasta
$config['PASTA'] = "ml"; //caso os arquivos não fiquem na raiz da hospedagem e sim em uma pasta dentro do servidor
$config['PASTA_LOCAL'] = "Macropost\ml-sistema"; //caso utilize xampp para trabalhar local ficaria localhost/nome da pasta local

/////////////////////////////////////////////////////////////////////////////////////////////////
// Certificado Digital
$config['SSL'] = false; // se utilizar https:// com certificado digital marque este campo como "true" caso contrario use "false

/////////////////////////////////////////////////////////////////////////////////////////////////
// Token
$config['TOKEN'] = "mercadodigital"; // qualquer palavra para gerar o token de segurança

/////////////////////////////////////////////////////////////////////////////////////////////////
// Analytcs

$config['analytics'] = "

";
