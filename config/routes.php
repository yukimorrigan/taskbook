<?php 

return array (
    // Добавить задачу
    'task/create' => 'task/create', // actionCreate в TaskController
    // Главная
    'task/getTask/([0-9]+)' => 'task/getTask/$1', // actionShowDescription в TaskController
    'index.php/page-([0-9]+)' => 'task/view/$1', // actionView в TaskController
    'page-([0-9]+)' => 'task/view/$1', // actionView в TaskController
    'index.php' => 'task/view', // actionView в TaskController
    '' => 'task/view', // actionView в TaskController
);

 ?>