<?php

namespace core\classes;

use core\extensions\ExtFileManager;
use core\classes\SysPath;

/**
 * Class SysAssets
 * @package core\classes
 * @version 1.0
 * @author farZa
 * @copyright 2015
 *
 * SysAssets - системный класс, где сосредоточена вся логика
 * подготовки и подключения стилей, скриптов и т.д.
 */
class SysAssets {

    /**
     * @var array $style
     * Данное свойство будет накапливать пути со стилями в порядке
     * их подключения. Чтобы начать накаплиать стили в массив
     * необходимо воспользоваться методом setAssets
     */
    private static $style = [];

    /**
     * @var array $script
     * Данное свойство будет накапливать пути со скриптами в порядке
     * их подключения. Чтобы начать накаплиать скрипты в массив
     * необходимо воспользоваться методом setAssets
     */
    private static $script = [];

    /**
     * Данный метод запускается при создании текущего класса.
     * Подготваливает все системные скрипты и стили для подключения
     * Подготовленные скрипты в этом методе будут доступны на любой странице
     * экшена
     */
    public static function initCoreAssets()
    {
        SysAssets::setAssets('jquery/external/jquery/jquery.js', 'system');
        SysAssets::setAssets('jquery/jquery-ui.min.js', 'system');
        SysAssets::setAssets('jquery/jquery.blockUI.js', 'system');
        SysAssets::setAssets('jquery/notify.js', 'system');
        SysAssets::setAssets('jquery/jquery-ui.theme.min.css', 'system');
        SysAssets::setAssets('jquery/jquery-ui.structure.min.css', 'system');
        SysAssets::setAssets('jquery/jquery-ui.min.css', 'system');

        SysAssets::setAssets('bootstrap/css/bootstrap.min.css', 'system');
        SysAssets::setAssets('bootstrap/css/bootstrap-theme.min.css', 'system');
        SysAssets::setAssets('bootstrap/js/bootstrap.min.js', 'system');
        SysAssets::setAssets('bootstrap/js/bootstrap-tooltip.js', 'system');
        SysAssets::setAssets('bootstrap/js/bootstrap-confirmation.js', 'system');

        SysAssets::setAssets('dad/css/jquery.dad.css', 'system');
        SysAssets::setAssets('dad/js/jquery.dad.js', 'system');

        SysAssets::setAssets('lucent/css/style.css', 'system');
        SysAssets::setAssets('lucent/js/script.js', 'system');
        SysAssets::setAssets('lucent/js/system.js', 'system');
    }

    /**
     * @author farZa
     * Метод должен брать все глобальные ассеты
     * чтобы они подключались независимо от того, нахожусь
     * ли я в модуле или нет
     */
    public static function setGlobalModuleAssets()
    {
        $coreModules = $moduleNames = SysModule::getAllModules('system');
        $appModules = $moduleNames = SysModule::getAllModules('app');
        $modules = array_merge($coreModules, $appModules);

        foreach ($modules as $module) {
            if (isset($module['assets']['global'])) {

                if (isset($module['assets']['js'])) {
                    foreach ($module['assets']['js'] as $jsPath) {
                        $fileName = basename($jsPath);
                        $path = strtolower($module['name']) . '/js/' . $fileName;
                        SysAssets::setAssets($path, 'modules');
                    }
                }

                if (isset($module['assets']['css'])) {
                    foreach ($module['assets']['css'] as $cssPath) {
                        $fileName = basename($cssPath);
                        $path = strtolower($module['name']) . '/css/' . $fileName;
                        SysAssets::setAssets($path, 'modules');
                    }
                }
            }
        }
    }

    public static function initModuleAssets()
    {
        self::setGlobalModuleAssets();
        $moduleName = SysModule::getCurrentModuleName();

        if (false !== $moduleName) {
            $moduleInfo = SysModule::getModuleInfo($moduleName);

            if (!isset($moduleInfo['assets'])) {
                return false;
            }

            $moduleAssets = $moduleInfo['assets'];

            if (isset($moduleAssets['js'])) {
                foreach ($moduleAssets['js'] as $jsPath) {
                    $fileName = basename($jsPath);
                    $path = $moduleName . '/js/' . $fileName;
                    SysAssets::setAssets($path, 'modules');
                }
            }

            if (isset($moduleAssets['css'])) {
                foreach ($moduleAssets['css'] as $cssPath) {
                    $fileName = basename($cssPath);
                    $path = $moduleName . '/css/' . $fileName;
                    SysAssets::setAssets($path, 'modules');
                }
            }

            return true;

            //var_dump($moduleInfo['assets']);
        }

        return false;
    }

    /**
     * Данный метод сравнивает закрытую системную директорию с ассетами в /core и
     * открытую директорию в /app. Если какие либо основные директории (те, которые находятся в корне
     * /app/assets/system) отсутствуют, производится автоматическое копирование недостающих
     * директорий с /core/assets в /app/assets/system
     */
    public static function filesAttach()
    {
        $fileManager = new ExtFileManager();
        $coreAssets = $fileManager->scanDir(SysPath::directory('core') . '/assets');

        if (!file_exists(SysPath::directory('app') . '/assets/system')) {
            mkdir(SysPath::directory('app') . '/assets/system');
        }

        $appAssetsSystem =  $fileManager->scanDir(SysPath::directory('app') . '/assets/system');

        foreach ($coreAssets as $coreA) {
            if (!in_array($coreA, $appAssetsSystem)) {
                $fileManager->recurse_copy(
                    SysPath::directory('core') . '/assets/' . $coreA,
                    SysPath::directory('app') . '/assets/system/' . $coreA
                );
            }
        }
    }

    /**
     * @return array - массив с путями к стилям и скриптам модулей
     * Сканирует все модули и "кеширует" все скрипты и стили в массив, затем
     * вовзращает его
     */
    private static function moduleScanAssets()
    {
        $moduleNamesCores = SysModule::getAllModules('system');
        $moduleNamesApp = SysModule::getAllModules('app');
        $moduleNames = array_merge($moduleNamesApp, $moduleNamesCores);

        $moduleAssets = [];
        foreach ($moduleNames as $module) {
            if (isset($module['assets'])) {
                if (isset($module['assets']['js'])) {
                    foreach ($module['assets']['js'] as $moduleJs) {
                        $moduleAssets[$module['name']]['js'][] = SysPath::directory('root') . '/' .
                            SysModule::getModulePath($module['name']) . '/' . $moduleJs;
                    }
                }

                if (isset($module['assets']['css'])) {
                    foreach ($module['assets']['css'] as $moduleCss) {
                        $moduleAssets[$module['name']]['css'][] = SysPath::directory('root') . '/' .
                            SysModule::getModulePath($module['name']) . '/' . $moduleCss;
                    }
                }

                if (isset($module['assets']['images'])) {
                    foreach ($module['assets']['images'] as $moduleImage) {
                        $moduleAssets[$module['name']]['images'][] = SysPath::directory('root') . '/' .
                            SysModule::getModulePath($module['name']) . '/' . $moduleImage;
                    }
                }
            }
        }

        return $moduleAssets;
    }

    /**
     * @return array
     * Переносит все скрипты и стили подключенных системных модулей с приватной core директории в
     * публичную app директорию (если это необходимо)
     */
    public static function moduleFilesAttach()
    {
        $files = static::moduleScanAssets();
        $publicAssetPath = SysPath::directory('app') . '/assets/modules/';
        $fileManager = new ExtFileManager();


        if (!file_exists($publicAssetPath)) {
            mkdir(SysPath::directory('app') . '/assets/modules');
        }

        $attached = [];

        //var_dump($files);

        foreach ($files as $moduleName => $moduleSet) {
            if (!file_exists($publicAssetPath . $moduleName)) {

                if (isset($moduleSet['css'])) {
                    foreach ($moduleSet['css'] as $cssPack) {
                        if (!is_dir($publicAssetPath . $moduleName)) {
                            mkdir($publicAssetPath . $moduleName, 0777);
                        }

                        if (!is_dir($publicAssetPath . $moduleName . '/css')) {
                            mkdir($publicAssetPath . $moduleName . '/css', 0777);
                        }

                        copy($cssPack, $publicAssetPath . $moduleName . '/css/' . basename($cssPack));
                        $attached['css'][] = $publicAssetPath . $moduleName . '/css/' . basename($cssPack);
                    }
                }

                if (isset($moduleSet['js'])) {
                    foreach ($moduleSet['js'] as $jsPack) {
                        if (!is_dir($publicAssetPath . $moduleName)) {
                            mkdir($publicAssetPath . $moduleName, 0777);
                        }

                        if (!is_dir($publicAssetPath . $moduleName . '/js')) {
                            mkdir($publicAssetPath . $moduleName . '/js', 0777);
                        }

                        copy($jsPack, $publicAssetPath . $moduleName . '/js/' . basename($jsPack));
                        $attached['js'][] = $publicAssetPath . $moduleName . '/js/' . basename($jsPack);
                    }
                }

                if (isset($moduleSet['images'])) {
                    foreach ($moduleSet['images'] as $imagePack) {
                        if (!is_dir($publicAssetPath . $moduleName)) {
                            mkdir($publicAssetPath . $moduleName, 0777);
                        }

                        $fileManager->recurse_copy($imagePack, $publicAssetPath . $moduleName . '/images');

                        $attached['images'][] = $publicAssetPath . $moduleName . '/images';
                    }
                }
            }
        }

        return $attached;

    }

    /**
     * @param $path
     * @param string $type
     *
     * Подготовка/накапливание/регистрация скриптов и стилей. Первым параметром передаетя
     * путь до файла. Вторым, каким типом является файл, системным или просто
     * пользовательским.
     * Системный - тот, который лежит в /core/assets/system
     * Пользовательский - тот, который лежит в /app/assets/users
     */
    public static function setAssets($path, $type = 'users')
    {
        $path_prepare = '';
        switch ($type) {
            case 'users' :
                $path_prepare = '/assets/users/' . $path;
                break;
            case 'system' :
                $path_prepare = '/assets/system/' . $path;
                break;
            case 'modules' :
                $path_prepare = '/assets/modules/' . $path;
                break;
        }

        if (file_exists($final_path = SysPath::directory('app') . $path_prepare)) {
            $file_info = pathinfo($path_prepare);

            switch ($file_info['extension']) {
                case 'css' :
                    static::$style[] = '<link href="/app'. $path_prepare .'" rel="stylesheet">';
                    break;
                case 'js' :
                    static::$script[] = '<script src="/app'. $path_prepare .'"></script>';
                    break;
            }
        }
    }

    /**
     * @param $type
     * @return bool|string
     *
     * Достает все накопленные/зарегистрированные стили и скрипты и
     * возвращает их как строку. В качестве параметра необходимо
     * указать каким типов является подключаемый файл:
     * style или script
     */
    public static function getAssets($type)
    {
        switch ($type) {
            case 'style' :
                $result = implode('', static::$style);
                static::$style = [];
                break;
            case 'script' :
                $result = implode('', static::$script);
                static::$script = [];
                break;
            default :
                $result = false;
                break;
        }

        return $result;
    }
}