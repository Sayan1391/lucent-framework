<?php

namespace core\classes;

use core\classes\exception\E404;
use core\system\app;
use core\extensions\ExtFileManager;

/**
 * Class SysController
 * @package core\classes
 * @version 1.0
 * @author farZa
 * @copyright 2015
 * Системный класс контроллера.
 */
class SysController
{
    /**
     * @var string $folder
     * Свойство с наименованием дериктории, в которой должно лежать представление.
     * Это свойство возможно перегрузить в дочернем классе. Системный класс SysView дергает
     * данное свойство для получения наименования дериктории
     */
    public static $folder;

    public static $currentName;

    public static $currentActionName;

    /**
     * @var string $name
     * Полное наименование вызываемого контроллера
     */
    public static $name;

    public static $action_name;

    public static $title = '';

    public function __call($name, $value)
    {
        throw new E404;
    }

    /**
     * @var string $path
     * Полный путь до вызываемого котроллера
     */
    public static $path;

    /**
     * @var string $layout
     * Свойство с адресом до layout. По умолчанию данное свойство
     * слушает путь до /app/views/layout/default.php
     * Путь со стандартным layout можно перегрузить в любом дочернем классе.
     */
    public static $layout;

    public function __construct()
    {
        $this->folder_like_controller_name();
        $this->setLayout();
        $this->getPathController();
        $this->getName();
        $this->getActionName();
    }

    /**
     * @return bool
     * Права доступа к действиям контроллера
     */
    public static function permission()
    {
        return true;
    }

    /**
     * @return bool
     * Метод который вызывается до запуска действия
     */
    public function beforeAction()
    {
        return true;
    }

    /**
     * @return bool
     * Метод который запускается после запуска действия
     */
    public function afterAction()
    {
        return true;
    }

    public function breadcrumbs()
    {
        return false;
    }

    /**
     * @param $action_name - имя действия
     * @return bool
     * Проверяет права доступа к действию (права для каждого действия описываются в контроллере)
     * 'index' => ['user', '-'], где
     * 'index' - действие, 'user' - роль, '-' - неавторизованный пользователь
     * В методе permission желаемого контроллера указываются действия и роли, которым доступ запрещен
     * Возвращает true - если доступ рпзрещен, в противном случае false
     */
    public function allow_action($action_name)
    {
        $permissions = static::permission();

        if (is_array($permissions)) {
            foreach ($permissions as $permission => $roles) {
                if ($action_name == $permission) {
                    foreach ($roles as $role) {
                        $current_role = SysAuth::getCurrentRole();
                        if (false === $current_role && $role === '-') {
                            return false;
                        }

                        if ($current_role === $role) {
                            return false;
                        }
                    }
                }
            }
        }

        return true;
    }

    /**
     * Получить поолный путь до вызываемого контроллера
     */
    private function getPathController()
    {
        $called_class = get_called_class();
        $reflector = new \ReflectionClass($called_class);

        $fn = $reflector->getFileName();
        $tt =  dirname($fn);

        static::$path = $tt;
    }

    /**
     * Получить полное имя контроллера
     */
    private function getName()
    {
        $class = get_called_class();
        $classArr = explode('\\', $class);
        static::$name = end($classArr);
    }

    private function getActionName()
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $pathParts = explode('/', $path);

        if (count($pathParts) >= 4) {
            $act = !empty($pathParts[3]) ? $pathParts[3] : App::$config['default_action'];
        } else {
            $act = !empty($pathParts[2]) ? $pathParts[2] : App::$config['default_action'];
        }

        static::$action_name = $act;
    }

    /**
     * folder_like_controller_name() - метод для получения наименования
     * будущей дериктории из класса контролера
     */
    private function folder_like_controller_name()
    {
        $folder = get_called_class();
        $folderArr = explode('\\', $folder);
        $folderName = str_replace('Controller', '', array_pop($folderArr));

        static::$folder = strtolower($folderName);
    }

    /**
     * Метод, который задает значение для layout по умолчанию. Вызывается автоматически при
     * создании экземпляра данного или дочернего класса.
     */
    private function setLayout()
    {
        static::$layout = SysPath::directory('core') . '/views/layouts/default.php';
    }

    public static function getCurrentName()
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $pathParts = explode('/', $path);

        $name = $pathParts[1];
        if (count($pathParts) >= 4) {
            $name = $pathParts[2];
        }

        return $name;
    }

}