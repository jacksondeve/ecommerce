<?php

namespace Hcode\model;
use Hcode\Model;

class User extends Model {

    const SESSION = "User";

    public static function login($login, $password)
    {
        $sql = new Sql();

        $results = $sql->select("SELECT *FROM tb_usuarios WHERE deslogin = :LOGIN", array(
            ":LOGIN"=>$login
        ));
        
        if(cont($results)=== 0 ){
            throw new Exception("Usuarios ienexiste ou senha invalida");

        }
        $data = $results[0];

        if(password_verify($password, $data["despassword"]) === true)
        {
            $user = new User();

            $user->setdata($data["iduser"]);

            $_SESSION[User::SESSION] = $user->getvalue();

            return $user;
        }else{
            throw new Exception("Usuarios ienexiste ou senha invalida");

        }
    }

    public static function verifylogin($inadmin = true)
    {
        if(
            !isset($_SESSION[User::SESSION]) 
            || !$_SESSION[User::SESSION]
            || !(int)$_SESSION[User::SESSION]["iduser"]> 0
            ||  (bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin
        ){
            header("location: /admin/login");
            exit;
        }
    }

    public static function logout()
    {
        $_SESSION[User:SESSION]= NULL;
    }
}


?>