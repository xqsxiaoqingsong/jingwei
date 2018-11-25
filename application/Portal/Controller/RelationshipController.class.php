<?php

namespace Portal\Controller;


use Common\Controller\HomebaseController;

class RelationshipController extends HomebaseController
{
    public function Index()
    {
        $this->display(":investor");
    }
    public function index1(){
        $this->display(":investor1");
    }
}