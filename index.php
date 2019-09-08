<?php 

require_once("vendor/autoload.php");
use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use	\Hcode\model\User;

session_start();
$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {
    
	$page = new Page();
	$page->setTpl("index");

});
$app->get('/admin', function() {
	
	User::verifylogin();
	
	$page = new PageAdmin();

	$page->setTpl("index");

});

$app->get('/admin/login',function(){
	$page = new PageAdmin([
		"header"=> false,
		"footer"=> false
	]);
	$page->setTpl("login");

});

$app->post('/admin/login', function(){

	user::login($_POST["login"],$_POST["password"]);
});

$app->get('/admin/logout',function() {
	User::logout();

	header("location: /admin/logout");
});

$app->run();

 ?>