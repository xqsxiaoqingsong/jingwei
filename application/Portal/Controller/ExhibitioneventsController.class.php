<?php

namespace Portal\Controller;


use Common\Controller\HomebaseController;

class ExhibitioneventsController extends HomebaseController
{
    function Index()
    {
        $this->display(":exhibitionevents");
    }
}