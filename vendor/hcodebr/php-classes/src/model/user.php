<?php

namespace Hcode\model;
use Hcode\Model;

class User extends Model {

    const SESSION = "User";
    const SECRET = "hcodephp7_secret";

    public static function login($login, $password)
    {
        $sql = new Sql();

        $results = $sql->select("SELECT *FROM tb_users WHERE deslogin = :LOGIN despassword = :PASSWORD", array(
            ":LOGIN"=>$login,
            "PASSWORD"=>$password
        ));
        
        if(cont($results)=== 0 ){
            throw new Exception("Usuarios ienexiste ou senha invalida");

        }
        $data = $results[0];

        if(password_verify($password, $data["despassword"]) === true)
        {
            $user = new User();

            $user->setdata($data["iduser"]);

            $_SESSION[User::SESSION] = $user->getValue();

            return $user;
        }else{
            throw new Exception("Usuarios ienexiste ou senha invalida");

        }
    }

    public static function verifyLogin($inadmin = true)
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
        $_SESSION [User::SESSION] = NULL;
    }

    public static function listAll()
    {
        $sql = new Sql();

        $sql->select("SELECT * FROM tb_users INNER JOIN tb_person b using(idperson) ORDER BY b.desperson");
    }

    public function save()
    {
        $sql = new Sql();

      $results =  $sql->select("CALL sp_users_save(:desperson,:deslogin,:despassword,:desemail,:nrphone,:inadmin)",
         array(
        ":iduser"=>$this->getiduser(),
        ":desperson"=>$this->getdesperson(),
        ":deslogin"=>$this->getdeslogin(),
        ":despassword"=>$this->getpassword(),
        ":desemail"=>$this->getdesmail(),
        ":nrphone"=>$this->getnrphone(),
        ":inadmin"=>$this->getinadmin()
        ));

        $this->setData($results[0]);

    }

    public function get($iduser)
    {
        $sql = new Sql();

        $results = $sql->select("SELECT * FROM  tb_users a INNER JOIN tb_persons b USING (idperson) WHERE a.iduser = :iduser",array(
            ":iduser"=>$iduser
        ));

        $this->setData($results[0]);
    }

    public function update()
    {
        $sql = new Sql();

        $results =  $sql->select("CALL sp_usersupdate_save(:iduser,:desperson,:deslogin,:despassword,:desemail,:nrphone,:inadmin)",array(
          ":desperson"=>$this->getdesperson(),
          ":deslogin"=>$this->getdeslogin(),
          ":despassword"=>$this->getpassword(),
          ":desemail"=>$this->getdesmail(),
          ":nrphone"=>$this->getnrphone(),
          ":inadmin"=>$this->getinadmin()
          ));
  
          $this->setData($results[0]);

    }

    public function delete()
    {
        $sql = new Sql();

        $sql->query("CALL sp_users_delete(:iduser)",array(
            ":iduser"=>$this->getiduser()
        ));
    }

    public static function getForgot()
    {
        $sql = new Sql();

        $results = $sql->select(
            "SELECT * FROM tb_persons a
            INNER JOIN tb_users b USING(idperson)
            WHERE  a.desemail = :email;",
            array(
               ":email"=>$email 
            )
        );

        if(cont($results) ===  0 )
        {
            throw new \Exception("nao foi possivel recuperar a senha ");
        }
        else{
            $data = $results[0];

            $results2 =  $sql->select("CALL sp_userpasswordrecovery_create(:iduser, :desip", array(
                "iduser"=>$data["iduser"],
                ":desip"=>$_SERVER["REMOTE_ADDR"]
            ));

            if(cont($results2) === 0)
            {
                throw new \Exception("nao foi possivel recuperar a senha ");
            }
            else{
                $datarecovery = $results2[0];
                
                $code = openssl_encrypt($datarecovery['idrecovery'], 'AES-128-CBC', pack("a16", User::SECRET),0,pack("a16", User::SECRET_IV));
                $code = base64_encode($code);

                $link = "http://www.hcodecommerce.com.br/admin/forgot/reset?=code";

                $mailer = new Mailer($data["desemail"],$data["desperson"], "redefinir senha da store","forgot",
                array(
                "name"=>$data["desperson"],
                "link"=>$link
                ));

                $mailer->send();

                return $data;
            }
        }
    }
    public static function validForgotDecrypt($code)
   {
        base64_decode($code);
        $idrecovery = openssl_decrypt($code, 'AES-128-CBC', pack("a16",User::SECRET),0,pack("a16", User::SECRET_IV));
           
        $results = $sql->select("SELECT * FROM
        tb_userspasswordsrecoveries a 
        INNER JOIN tb_users b USING(idperson)
        INNER JOIN tb_person c USING(idperson)
        WHERE a.idrecovery = :idrecovery
        AND
        a.dtrecovery IS NULL 
        AND 
        DATE_ADD(a.dtregister, INTERVAL 1 HOUR)>= NOW();
        ",array(":idrecovery"=>$idrecovery));

        if(count($results)===0)
        {
            throw new \Exception("nao foi possivel recuperar a senha ");

        }
        else
        {   
            return $results[0];

        }
    }
        public function setForgotUser($idrecovery)
        {
            $sql = new Sql();
            $sql->query("UPDATE tb_userspasswordsrecoveries SET dtrecovery = NOW() WHERE idrecovery = :idrecovery", array(
                "idrecovery"=>$idrecovery
            ));
        }

        public function setPassword()
        {
            $sql = new Sql();

            $sql->query("UPDATE tb_users SET despassword = :password WHERE iduser = :iduser",array(
                "password"=>$password,
                ":iduser"=>$this->getiduser()
            ));
            
        }
}


?>