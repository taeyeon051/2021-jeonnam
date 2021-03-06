<?php

namespace Kty\App;

use Kty\Library\Lib;

class Route
{
    private static $actions = [];

    public static function __callStatic($name, $args)
    {
        $req = strtolower($_SERVER['REQUEST_METHOD']);
        if ($name == $req) self::$actions[] = $args;
    }

    public static function init()
    {
        $path = explode("?", $_SERVER['REQUEST_URI']);
        $path = $path[0];

        foreach (self::$actions as $a) {
            if ($a[0] === $path) {
                $action = explode("@", $a[1]);
                $controllerName = 'Kty\\Controller\\' . $action[0];
                $c = new $controllerName();
                $c->{$action[1]}();
                return;
            }
        }

        Lib::msgAndBack("없는 주소입니다.");
    }
}
