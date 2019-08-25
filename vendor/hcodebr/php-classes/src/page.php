<?php

namespace Hcode;
use Rain\Tpl;

class Page{

    private $defaults = [
        "data" =>[]
    ];
    private $options = [];
    private $Tpl;


    function __construct($opts = array()){

        $this->$options = array_merge($this->default, $opts); // array_merge junta dois array colocando o array 
        $config = array(                                      // predominante por ultimo o caso sobrescrevendo o array default

            "tpl_dir"       => $_SERVER["DOCUMENT_ROOT"]."ecommerce/view/",//variavel que mostra o root
            "cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."ecommerce/views-cache/",
            "debug"         => false // set to false to improve the speed
        );

        Tpl::configure( $config );

        $Tpl = new Tpl;

        $this->setDATA($this->options["data"]);//methodo data

        $this->Tpl->draw("header");

        //methodo unico
        function setDATA($data = array())
         {
            foreach($data as $key => $value){
                $this->Tpl->assign($key, $value);
            }
        }

        //metodo que seta o nome o array e retorna o html
         function setTPL($name, $data = array(), $returnHTML = false)
        {
            $this->Tpl->setDATA($data);

          return  $this->Tpl->draw($name, $returnHTML);
        }
    }
    function __destruct(){
        if($this->$options["footer"]=== true){
        $this->Tpl->draw("footer");
         }
    }
}


?>