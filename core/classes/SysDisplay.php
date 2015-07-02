<?php

namespace core\classes;


class SysDisplay {

    protected static $layout;

    protected $data = [];

    public function __construct($layout = false)
    {
        if (true == $layout) {
            static::$layout = SysPath::directory('app') . '/' . $layout . '.php';
        } else {
            static::$layout = SysPath::directory('core') . '/views/layouts/default.php';
        }
    }

    public function __set($k, $v)
    {
        $this->data[$k] = $v;
    }

    public function __get($k)
    {
        return $this->data[$k];
    }

    public function render($path, $return = false, $layout = false)
    {
        foreach ($this->data as $key => $value) {
            $$key = $value;
        }

        ob_start();
        include SysPath::directory('root') . '/' . $path . '.php';

        $content = SysTokens::apply(ob_get_contents());
        ob_end_clean();

        if (false == $layout) {
            if (!$return) {
                echo $content;
                return true;
            }

            return $content;
        }

        SysAssets::initCoreAssets();
        SysAssets::initModuleAssets();
        ob_start();
        include SysDisplay::$layout;
        $content_final = SysTokens::apply(ob_get_contents());
        ob_end_clean();

        if (!$return) {
            echo $content_final;
            return true;
        }

        return $content_final;
    }
}
