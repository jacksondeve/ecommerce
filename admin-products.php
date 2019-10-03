<?php
use Hcode\PageAdmin;
use \Hcode\Model\User;
use \Hcode\Model\Products;


$app->get("/admin/products",function(){
    User::varifyLogin();
});

$products = products::listAll();
$page = new PageAdmin();

//passa a lista dos produtos
$page->setTpl("products",[
    "products"=>$products 
]);


$app->get("/admin/products/create",function(){
    User::varifyLogin();

    $page = new PageAdmin();

//passa a lista dos produtos
$page->setTpl("products-create");
});

$app->post("/admin/products/create",function(){
    User::varifyLogin();

    User::verifyLogin();
    $product = new Product();

    setData($_POST);
    $product->save();

    header("location:/admin/products");
    exit;

});

$app->get("/admin/products/:idproduct",function($idproduct){
    User::varifyLogin();

    $product = new Product();
    $product->get((int)$idproduct);

    $page = new PageAdmin();


//passa a lista dos produtos
$page->setTpl("products-update",[
    'product'=>$product->getvalue()
]);


$app->post("/admin/products/:idproduct",function($idproduct){
    User::varifyLogin();

    $product = new Product();
    $product->get((int)$idproduct);


    $product->setData($_POST);

    $product->save();
    $product->setPhoto($_FILES['file']);

    header('location: /admin/products');
    exit;

});

$app->get("/admin/products/:idproduct",function($idproduct){
    User::varifyLogin();

    $product = new Product();
    $product->get((int)$idproduct);


    $product->delete();

    header('location: /admin/products');
    exit;

});

?>