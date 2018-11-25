<?php

namespace Portal\Controller;


use Common\Controller\HomebaseController;

class ContactController extends HomebaseController
{
    function Index()
    {
        $this->display(":contact");
    }
}