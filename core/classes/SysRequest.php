<?php
namespace core\classes;

/**
 * Class SysRequest
 * @package core\classes
 * Класс для работы с запросами
 */
class SysRequest {

    public static function post()
    {
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
            return $_POST;
        }

        return false;
    }

    public static function get($name)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if (isset($_GET[$name])) {
                $get = $_GET[$name];
                return $get;
            }
        }

        return false;
    }

    public static function redirect($path)
    {
        header('Location: ' . $path, true, 303);
        exit();
    }

    public static function refresh()
    {
        header("Refresh:0");
        die();
    }
}