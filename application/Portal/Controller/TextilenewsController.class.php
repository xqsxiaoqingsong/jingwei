<?php

namespace Portal\Controller;


use Common\Controller\HomebaseController;

class TextilenewsController extends HomebaseController
{
    public function Index()
    {
        $this->display(":textilenews");
    }
}