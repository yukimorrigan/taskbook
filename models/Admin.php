<?php 

/**
 * Класс Admin - модель для работы с администратором
 */
class Admin
{
    /**
     * Проверяет логин и пароль
     * @param string $name <p>Логин</p>
     * @param string $password <p>Пароль</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function checkAdmin($name, $password) 
    {
        if ($name == 'admin' && $password == '123') {
            $_SESSION['admin'] = 'admin';
        	return true;
        } else {
            unset($_SESSION['admin']);
        	return false;
        }
    }

    /**
     * Возвращает истину, если администратор авторизирован.<br/>
     * Иначе перенаправляет на страницу входа
     */
    public static function checkLogged()
    {
        if (isset($_SESSION['admin'])) {
            return true;
        }
        header("Location: /admin/login");
    }
}

 ?>