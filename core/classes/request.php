<?php
namespace core\classes;


class Request {

    //@todo Сделать обработку поста
    public static function post()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            return $_POST;
        }

        return false;
    }

    public static function get($name)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if ($get = $_GET[$name]) {
                return $get;
            }
        }

        return false;
    }

    public static function redirect($path)
    {
        header('Location: ' . $path, true, 303);
    }
}