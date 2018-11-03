<?php

namespace Todolist\Controllers; 
use Todolist\Constant;

class Home
{
    /**
     * PAGE: index
     * http://todolist.biz/home/index
     */
    public function index()
    {
        require 'app/views/_templates/header.php';
        require 'app/views/home/index.php';
        require 'app/views/_templates/footer.php';
    }
}
