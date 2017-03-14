<?php

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        return $this->dispatcher->forward(
            [
                'controller' => "orders",
                'action' => 'index'
            ]
        );
    }

}

