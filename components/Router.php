<?php 

/**
 * Класс Router
 * Компонент для работы с маршрутами
 */
class Router
{
	// Свойство для хранения массива роутов
	private $routes;

	public function __construct()
	{
		// Путь к файлу с роутами
		$routesPath = ROOT.'/config/routes.php';
		// Получаем роуты из файла
		$this->routes = include($routesPath);
	}

	/**
     * Возвращает строку запроса
     */
	private function getURI()
	{
		if (!empty($_SERVER['REQUEST_URI']))
		{
			return trim($_SERVER['REQUEST_URI'], '/');
		}
	}

	public function run()
	{
		// Получить строку запроса
		$uri = $this->getURI();

		// Проверить наличие такого запроса в routes.php
		foreach ($this->routes as $uriPattern => $path)
		{
			// Сравниваем $uriPattern и $uri
			if (preg_match( "~$uriPattern~", $uri))
			{
				// Получем внутренний путь из внешнего согласно правилу
				$internalRoute = preg_replace("~$uriPattern~", $path, $uri);

				// Определить какой controller и action обрабатывают запрос
				$segments = explode('/', $internalRoute);
				
				$controllerName = array_shift($segments) . 'Controller';
				$controllerName = ucfirst($controllerName);

				$actionName = 'action'.ucfirst(array_shift($segments));

				$parameters = $segments;

				// Подключить файл класса-конроллера
				$controllerFile = ROOT. '/controllers/' . $controllerName . '.php';

				if (file_exists($controllerFile))
				{
					include_once($controllerFile);
				}

				// Создать объект, вызвать метод (т.е. action)
				$controllerObject = new $controllerName;
				
				// Вызвать функцию $actionName у объекта $controllerObject, при этом, передавая массив с параметрами parameters
				$result = call_user_func_array(array($controllerObject, $actionName), $parameters);
				
				if ($result != null)
				{
					break;
				}
			}
		}
	}
}

 ?>