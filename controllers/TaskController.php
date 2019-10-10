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

    public function actionCreate() {
        $check = '';
        $name = '';
        $email = '';
        $description = '';

        if (isset($_POST['submit'])) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $description = $_POST['description'];

            if ($name == '' || $email == '' || $description == '') {
                $check = 0;
            } else {
                $id = Task::createTask($name, $email, $description);
                if ($id) {
                    $fileName = $_FILES['image']['tmp_name'];
                    if (is_uploaded_file($fileName)) {
                        $new_width = 320;
                        $new_height = 240;
                        list($width, $height) = getimagesize($fileName);
                        if ($width > $new_width || $height > $new_height) {
                            $this->changeImageSize($fileName, $new_width, $new_height);
                        }
                        $format = preg_split('/\./', $_FILES["image"]["name"])[1];
                        move_uploaded_file($_FILES["image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/upload/images/{$id}." . $format);
                        $check = 1;
                    }
                }
            }
        }
        // Подключаем вид
        require_once(ROOT . '/views/task/create.php');
        return true;
    }

    public function changeImageSize($fileName, $new_width, $new_height) {
        $fn = $fileName;
        $size = getimagesize($fn);
        $ratio = $size[0]/$size[1]; // width/height

        if( $ratio >= 1) {
            $width = $new_width;
            $height = $new_width/$ratio;
        }
        else {
            $width = $new_height*$ratio;
            $height = $new_height;
        }
        $src = imagecreatefromstring(file_get_contents($fn));
        $dst = imagecreatetruecolor($width,$height);
        $image = imagecopyresampled($dst,$src,0,0,0,0,$width,$height,$size[0],$size[1]);

        imagedestroy($src);
        imagepng($dst,$fileName);
        imagedestroy($dst);
    }
}

?>