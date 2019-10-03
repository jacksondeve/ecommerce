<?php

use Hcode\PageAdmin;
use \Hcode\Model\User;

$app->get('/admin/users', function($iduser){

User::verifylogin();

$users = User::listAll();

$page = new PageAdmin();

$page->setTpl("users", array(
    "users" =>$users
));

});


$app->get('/admin/users/:iduser', function($idusers){

User::verifylogin();

$user = new User();

$user->get((int)$iduser);


$page = new PageAdmin();

$page->setTpl("users-update", array(
    "user"=>$user->getvalues()
));

});


$app->delete("/admin/users/:iduser/delete",function($iduser){
User::verifylogin();
$user->get((int)$iduser);

$user->delete();

header("location : /admin/users");

});


$app->post("/admin/users/create", function () {

User::verifyLogin();

$user = new User();

$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

$_POST['despassword'] = password_hash($_POST["despassword"], PASSWORD_DEFAULT, [

    "cost"=>12

]);

$user->setData($_POST);

$user->save();

header("Location: /admin/users");
exit;

});



$app->post("/admin/users/:iduser",function($iduser){
User::verifylogin();

$user = new User();

$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

$user->get((int)$iduser);

$user->setData($_POST);

$user->update();

header("location: admin/users");
exit;

});

?>