<?php

class TestClass {
    protected $obj;

    public function __construct()
    {
        $obj = new SomeObject();
        echo $obj->getNum();
    }

}