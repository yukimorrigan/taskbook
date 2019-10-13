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
        require_once(ROOT . '/views/task/index.php');
        return true;
    }

    /**
     * Action для получения информации о задаче при помощи асинхронного запроса (ajax)
     * @param integer $id <p>id задачи</p>
     */
    public function actionGetTask($id) {
        // Получаем задачу по id
        $task = Task::getTaskById($id);
        // Получаем путь к изображению задачи
        $task['image'] = Task::getImage($id);
        // Переводим в формат JSON
        echo json_encode($task, JSON_UNESCAPED_UNICODE);
        return true;
    }

    /**
     * Action для страницы "Добавить задачу"
     */
    public function actionCreate() {
        // Инициализация переменных
        $name = '';
        $email = '';
        $description = '';
        // Если форма отправлена
        if (isset($_POST['submit'])) {
            // Флаг ошибок
            $errors = false;
            // Флаг результатов
            $result = false;
            // Получаем данные из формы
            $name = $_POST['name'];
            $email = $_POST['email'];
            $description = $_POST['description'];
            // Если хоть одно текстовое поле формы пустое
            if ($name == '' || $email == '' || $description == '') {
                $errors[] = 'Заполните поля!';
            }
            // Если E-mail не валидный
            if (!Task::checkEmail($email)) {
                $errors[] = 'Неверный email';
            }          
            // Получаем id, который будет иметь запись, после вставки в БД
            $id = Task::getAutoIncrement();
            // Получаем изображение из формы
            $fileName = $_FILES['image']['tmp_name'];
            // Флаг проверки формата изображения
            $check_format = true;
            // Проверяем, загружалось ли изображение через форму 
            if (is_uploaded_file($fileName) && $check_format = Task::checkImageFormat($fileName)) {
                // Если не было ошибок
                if ($errors == false) {
                    // Добавляем новую задачу в БД
                    Task::createTask($name, $email, $description);
                    // Сохраняем изображение
                    // Максимально допустимая ширина изображения
                    $max_width = 320;
                    // Максимально допустимая высота изображения
                    $max_height = 240;
                    // Ширина и высота загруженного изображения
                    list($width, $height) = getimagesize($fileName);
                    // Если изображение привышает максимальные размеры
                    if ($width > $max_width || $height > $max_height) {
                        // Пропорционально уменьшаем изображение
                        Task::changeImageSize($fileName, $max_width, $max_height, $width, $height);
                    }
                    // Формат загруженного изображения
                    $format = preg_split('/\./', $_FILES["image"]["name"])[1];
                    // Сохраняем изображение
                    move_uploaded_file($_FILES["image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/upload/images/{$id}." . $format);
                    // Запись удачно добавлена
                    $result = true;
                }
            } else {
                // Данные не прошли проверку
                if ($check_format) { 
                    $errors[] = 'Изображение не было загружено';
                } else {
                    $errors[] = 'Неверный формат изображения. Допустимые форматы: JPG/GIF/PNG';
                }
            }
            
        }
        // Подключаем вид
        require_once(ROOT . '/views/task/create.php');
        return true;
    }
}

?>