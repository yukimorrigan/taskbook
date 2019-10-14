<?php 
/**
 * Контроллер AdminController
 * Админ
 */
class AdminController
{
    /**
     * Action для страницы "Авторизация для админа"
     */
    public function actionLogin() {
        // Если форма отправлена
        if (isset($_POST['submit'])) {
            // Получаем данные из формы
            $name = $_POST['name'];
            $password = $_POST['password'];
            // Флаг ошибок
            $error = false;
            // Проверка логина и пароля
            if (Admin::checkAdmin($name, $password)) {
                // Перенаправляем пользователя на страницу редактирования задач
                header("Location: /admin/view");
            } else {
                $error = 'Неверный Логин/Пароль';
            }
        }
        // Подключаем вид
        require_once(ROOT . '/views/admin/login.php');
        return true;
        
    }

    /**
     * Action для страницы "Вывод списка задач для админа"
     * @param integer $page <p>страница просмотра</p>
     */
    public function actionView($page = 1) {
        // Проверка доступа
        if (!Admin::checkLogged()) {
            return true;
        }
        // Список задач
        $tasks = array();
        $tasks = Task::getTasksList($page);
        // Количество задач
        $total = Task::getTotalTasks();
        // Создаем объект Pagination - постраничная навигация
        $pagination = new Pagination($total, $page, Task::SHOW_BY_DEFAULT, 'page-');
        // Подключаем вид
        require_once(ROOT . '/views/admin/view.php');
        return true;
    }

    /**
     * Action для страницы "Вывод отсортированного списка задач для админа"
     * @param string $sortColumn <p>Столбец, по которому производится сортировка</p>
     * @param string $sortOrder <p>Порядок сортировки</p>
     * @param integer $page <p>страница просмотра</p>
     */
    public function actionViewSort($sortColumn = 'status', $sortOrder = 'ASC', $page = 1) {
        // Проверка доступа
        if (!Admin::checkLogged()) {
            return true;
        }
        // Список задач
        $tasks = array();
        $tasks = Task::getSortTasksList($sortColumn, $sortOrder, $page);
        // Количество задач
        $total = Task::getTotalTasks();
        // Создаем объект Pagination - постраничная навигация
        $pagination = new Pagination($total, $page, Task::SHOW_BY_DEFAULT, 'page-');
        // Подключаем вид
        require_once(ROOT . '/views/admin/view.php');
        return true;
    }

    /**
     * Action для получения информации о задаче при помощи асинхронного запроса (ajax)
     * @param integer $id <p>id задачи</p>
     * @param array $task <p>Массив с информацей о задаче</p>
     */
    public function actionEdit($id, $task) {
        // Проверка доступа
        if (!Admin::checkLogged()) {
            return true;
        }
        $task = urldecode($task);
        $task = json_decode($task, JSON_UNESCAPED_UNICODE);
        echo Task::updateTask($id, $task);
        return true;
    }
}

?>