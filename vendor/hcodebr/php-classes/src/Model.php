<?php

namespace Hcode;

class Model{
    private $value = [];

    public function __call($name,$args)
    {
        $method = substr($name, 0,3);
        $fildname = substr($name, 3,strlen($name));

        var_dump($method, $fildname);
       
        switch ($method)
        {
            case "get":
            return $this->values[$fildname];
            break;

            case "set":
            $this->value[$fildname] = $args[0];
            break;

        }
    }
    public function setData($data = array())
    {
        foreach ($data as $key => $value){
            $this{"set".$key.($value)};
        }
    }

    public function getvalue()
    {
        return $this->value;
    }
}


?>

