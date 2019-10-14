<?php 

return array (
	// Авторизация для админа/Вывод списка задач
	'admin/edit/([0-9]+)/(.+)' => 'admin/edit/$1/$2', // actionEdit в AdminController
    'admin/view/(.+)/(.+)/page-([0-9]+)' => 'admin/viewSort/$1/$2/$3', // actionViewSort в AdminController
	'admin/view/page-([0-9]+)' => 'admin/view/$1', // actionView в AdminController
    'admin/view' => 'admin/view', // actionView в AdminController
    'admin/login' => 'admin/login', // actionLogin в AdminController
    // Добавить задачу
    'task/create' => 'task/create', // actionCreate в TaskController
    // Список задач
    'task/getTask/([0-9]+)' => 'task/getTask/$1', // actionGetTask в TaskController
    'index.php/page-([0-9]+)' => 'task/view/$1', // actionView в TaskController
    'page-([0-9]+)' => 'task/view/$1', // actionView в TaskController
    // Главная
    'index.php' => 'task/view/1', // actionView в TaskController
    '' => 'task/view/1', // actionView в TaskController
);

 ?>