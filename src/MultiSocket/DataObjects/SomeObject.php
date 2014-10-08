<?php

class SomeObject {
    protected $name;

    public function __construct()
    {
        $this->name = array("fname"=>"Cesar", "lname"=>"Flores");
    }
    public function setName($arr)
    {
        $this->name = $arr;
    }
    public function getName()
    {
        return json_encode($this->name);
    }
}