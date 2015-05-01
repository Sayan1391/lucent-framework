<?php
namespace core\classes;

/**
 * Class Cview
 * @package core\classes
 * @version 1.0
 * @author farZa
 * @copyright 2015
 * Системный класс Cview отвечает связь контроллера и вьюхи.
 * Для того, чтобы передать данные во вьюху, необходимо создать
 * экземпляр объекта Cview или его потомка, затем в качестве его свойств
 * задать те значения, которые должны появиться во вьюхе. После применения метода display($view)
 * в представлении будут доступны переменные, которые были заданы в качестве свойств
 * Например: $view = new Cview(); $view->title = 'Hello World'; $view->display('home/index.php');
 * В home/index.php станет доступна переменная $title
 */
class Cview
{
    /**
     * @var array
     * $data - массив, который наполняется данными через магический метод __set
     */
    protected $data = [];

    public function __construct()
    {
        $this->initCoreAssets();
    }

    /**
     * Данный метод запускается при создании текущего класса.
     * Подготваливает все системные скрипты и стили для подключения
     * Подготовленные скрипты в этом методе будут доступны на любой странице
     * экшена
     */
    private function initCoreAssets()
    {
        Casset::setAssets('jquery/external/jquery/jquery.js', 'system');
        Casset::setAssets('jquery/jquery-ui.min.js', 'system');
        Casset::setAssets('jquery/jquery-ui.theme.min.css', 'system');
        Casset::setAssets('jquery/jquery-ui.structure.min.css', 'system');
        Casset::setAssets('jquery/jquery-ui.min.css', 'system');

        Casset::setAssets('bootstrap/css/bootstrap.min.css', 'system');
        Casset::setAssets('bootstrap/css/bootstrap-theme.min.css', 'system');
        Casset::setAssets('bootstrap/js/bootstrap.min.js', 'system');

        Casset::setAssets('lucent/css/style.css', 'system');
    }

    /**
     * @param $k
     * @param $v
     * Сеттер обыкновенный. При попытке записать что-либо в неопределенное свойство
     * экземплра данного класса или потомка, данные пишутся в массив $data,
     * где ключ - название свойства, значение - значение
     */
    public function __set($k, $v)
    {
        $this->data[$k] = $v;
    }

    /**
     * @param $k
     * @return mixed
     * Геттер обыкновенный. При попытке обращения к неопределенному свойству экземпляра данного класса
     * или его потомка, данные будут браться из массива $data
     */
    public function __get($k)
    {
        return $this->data[$k];
    }

    /**
     * @param $view
     * @return string
     * Создание переменных для view и непосредственно само подключение
     * Также в методе реализована буферизация для дальнейшей обработки данных
     * Метод напрямую работает с layout
     */
    private function render($view)
    {
        foreach ($this->data as $key => $value) {
            $$key = $value;
        }

        $pathView = Path::setViews() . '/' . Ccontroller::$folder . '/' . $view . '.php';
        if (!file_exists($pathView)) {
            $pathView = Path::setViews('coreModules') . '/' . Ccontroller::$folder . '/' . $view . '.php';
            if (!file_exists($pathView)) {
                $pathView = Path::setViews('appModules') . '/' . Ccontroller::$folder . '/' . $view . '.php';
            }
        }

        ob_start();
        include $pathView;

        $content = ob_get_contents();
        ob_end_clean();

        ob_start();
        include Ccontroller::$layout;
        $content_final = ob_get_contents();
        ob_end_clean();

        return $content_final;
    }

    /**
     * @param $view
     * Отображение данных
     */
    public function display($view)
    {
        echo $this->render($view);
    }
}