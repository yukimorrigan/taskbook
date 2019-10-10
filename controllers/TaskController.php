<?php 
/**
 * Контроллер TaskController 
 * Задача
 */
class TaskController 
{   /**
     * Action для страницы просмотра списка задач
     * @param integer $page <p>страница просмотра</p>
     */
    public function actionView($page = 1) {
        // Список задач
        $tasks = array();
        $tasks = Task::getTasksList($page);
        // Количество задач
        $total = Task::getTotalTasks();
        // Создаем объект Pagination - постраничная навигация
        $pagination = new Pagination($total, $page, Task::SHOW_BY_DEFAULT, 'page-');
        // Подключаем вид
        require_once(ROOT . '/views/task/view.php');
        return true;
    }
    /**
     * Action для получения задачи при помощи асинхронного запроса (ajax)
     * @param integer $id <p>id задачи</p>
     */
    public function actionShowDescription($id) {
        // Получаем задачу по id
        $task = Task::getTaskById($id);
        // Получаем путь к изображению задачи
        $task['image'] = Task::getImage($id);
        // Переводим в формат JSON
        echo json_encode($task, JSON_UNESCAPED_UNICODE);
        return true;
    }
}

?>