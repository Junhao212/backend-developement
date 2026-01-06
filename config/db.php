<?php

abstract class Db
{
    private static $db;

    public static function getConnection()
    {
        if (self::$db) {
            return self::$db;
        }

        self::$db = new PDO(
            'mysql:host=127.0.0.1;dbname=webshop;charset=utf8mb4',
            'root',
            ''
        );

        self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return self::$db;
    }
}