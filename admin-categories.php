
<?php

use \Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\$Category;


$app->get("/categories/:idcategory", function($idcategory){
	$category = new category();

	$category->get((int)$idcategory);

	$page = new Page();
	$page->setTpl("category", [
		'category'=>$category->getvalues(),
		'productss'=>[]
	]);
});


$app->get("/admin/categories/create",function(){

	User::verifylogin();
	$page = new PageAdmin();

	$categories = Categories::listALL();

	$page->setTpl("categories-create");
});

$app->get("/admin/categories/create",function(){

	User::verifylogin();
	$category = new category();

	$category->set($_POST);

	$category->saveData();

	header('location: /admin/categories');
	exit;

});

$app->get("/admin/categories/:idcategory/delete", function($idcategory){

	User::verifylogin();
	$category = new category();

	$category->get((int)$idcategory);

	$category->delete();
});

$app->get("/admin/categories/:idcategory/delete", function($idcategory){

	User::verifylogin();
	$category = new category();

	$category = new PageAdmin();
	$category->get((int)$idcategory);

	$page->setTpl("categories-update", [
		'category'=>$category->getvalues()
	]);
});

$app->get("/admin/categories/:idcategory/delete", function($idcategory){

	User::verifylogin();
	$category = new category();

	$category->get((int)$idcategory);
	$category->setData($_POST);

	$category->save();
	
});


?>