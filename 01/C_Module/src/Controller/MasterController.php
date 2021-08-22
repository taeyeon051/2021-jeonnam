<?php

namespace Kty\Controller;

class MasterController
{
    public function render($view_name, $data = [])
    {
        extract($data);
        // echo $sql;
        require_once(__VIEWS . "/layout/header.php");
        require_once(__VIEWS . "/{$view_name}.php");
        require_once(__VIEWS . "/layout/footer.php");
        // require_once(__VIEWS . "/layout/popup.php");
    }
}
