<?php

namespace Kty\Controller;

use Kty\App\DB;

class MainController extends MasterController
{
    public function index()
    {
        $this->render('main');
    }

    public function daejeonBakery()
    {
        $sql = "";

        $this->render('daejeon_bakery');
    }
}
