<?php

/**
 * Главный конфигурационный файл приложения
 */
return [
    /** Контроллер по умолчанию*/
    'default_controller' => 'home',
    /** Действие по умолчанию*/
    'default_action' => 'index',
    
    'system_tables' => [
        'users' => 'users',
        'roles' => 'roles',
    ],
];

?>