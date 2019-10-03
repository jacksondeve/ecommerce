<?php

namespace Hcode\Model;
use Hcode\Model;

class Products extends Model {

   

    public static function listAll()
    {
        $sql = new Sql();

       return  $sql->select("SELECT * FROM tb_products  ORDER BY desproduct");
    }
    
    public function save()
    {
        $sql = new Sql();

        $results =  $sql->select("CALL sp_products_save(:idproduct,:desproduct, :vlprice, :vlwidth, :vlheight, :vllength :vlweight, :desur, :dtregister)",
         array(
        ":idproduct"=>$this->getidproduct(),
        ":despoduct"=>$this->getdespoduct(),
        ":vlprice"=>$this->getvlprice(),
        ":vlwidth"=>$this->getvlwidth(),
        ":vlheight"=>$this->getvlheight(),
        ":vllength"=>$this->getvllength(),
        ":vlheight"=>$this->getvlheight(),
        ":vlweight"=>$this->getvlweight(),
        ":desur"=>$this->getdesur(),
        ":dtregister"=>$this->getdtregister()

         ));
        

        $this->setData($results[0]);

        category::updateFile();
    }

    public function get($idproduct)
    {
        $sql = new Sql();

        $results =  $sql->select("SELECT * FROM tb_products WHERE idproduct = :idproduct", [':idproduct'=>$idproduct]);

        $this->setData($results[0]);
    }

    public function delete()
    {
        $sql = new Sql();

        $sql->query("DELETE FROM tb_products WHERE idproduct",[
            'idproduct'=>getidproduct()
        ]);
    }

    //tras foto
    public function checkPhoto()
    {
        //diretorio do sistema operacional
    if(file_exists(['DOCUMENT_ROOT']. DIRECTORY_SEPARATOR.
    "res".DIRECTORY_SEPARATOR.
    "site".DIRECTORY_SEPARATOR.
    "img".DIRECTORY_SEPARATOR.
    "products".DIRECTORY_SEPARATOR.
    $this->getidproduct()."jpg")){
        //retorna a url
        return "/res/site/img/products/". $this->getidproduct()."jpg";
    }else{
        $url =  "/res/site/img/product.jpg/";
    }

    }

    public function getValues()
    {
        $value =  parent::getValues();
        return  $value;
    }
    
     public function $this->setdesPhoto($file)
     {
        $extension = explode('.',$_FILES['file']);
        $extension = end($extension);

        switch($extension){

            case "jpg":
            case "jpeg":
            $image = imagecreatefromjpeg($file["tpm_name"]);
            break;

            case "gif":
            $image = imagecreatefromgif($file["tpm_name"]);
            break;
            
            case "png":
            $image = imagecreatefrompng($file["tpm_name"]);
            break;
        }
        $dist = $_SERVER['DOCUMENT_ROOT']. DIRECTORY_SEPARATOR.
        "res".DIRECTORY_SEPARATOR.
        "site".DIRECTORY_SEPARATOR.
        "img".DIRECTORY_SEPARATOR.
        "products".DIRECTORY_SEPARATOR.
        $this->getidproduct()."jpg";
        imagejpeg($image,$dist);

        imagedestroy($image);

        $this->checkPhoto();
     }
 

?>