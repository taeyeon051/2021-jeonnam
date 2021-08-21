<?php

namespace Kty\App;

class DB
{
    private static $con = null;

    public static function getDB()
    {
        if (self::$con == null) self::$con = new \PDO("mysql:host=localhost; dbname=2021_jeonnam; charset=utf8mb4", "root", "");
        return self::$con;
    }

    public static function fetchAll($sql, $data = [])
    {
        $con = self::getDB();
        $q = $con->prepare($sql);
        $q->execute($data);
        return $q->fetchAll(\PDO::FETCH_OBJ);
    }

    public static function fetch($sql, $data = [])
    {
        $con = self::getDB();
        $q = $con->prepare($sql);
        $q->execute($data);
        return $q->fetch(\PDO::FETCH_OBJ);
    }

    public static function execute($sql, $data = [])
    {
        $con = self::getDB();
        $q = $con->prepare($sql);
        return $q->execute($data);
    }
}