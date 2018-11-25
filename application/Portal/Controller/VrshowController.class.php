<?php

namespace Portal\Controller;


use Common\Controller\HomebaseController;

class VrshowController extends HomebaseController
{
    function Index()
    {
        $this->display(":vrshow");
    }
}