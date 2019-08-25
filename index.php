<?php 

require_once("vendor/autoload.php"); //tras as depenpendencial e auto carregamento

use \Slim\Slim;
use \hcode\Page;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {//coloca o diretorio raiz seo

	$page = new Page();//criar variavel page para ele inicializar os pages

	$page->setTPL("index");//inicializa o ariquivo principal
});

$app->run();// tudo carregado roda a aplicaçao

 ?>