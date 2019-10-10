<?php 

/**
 * Класс Task - модель для работы с задачами
 */
class Task 
{
    // Количество отображаемых задач по умолчанию
    const SHOW_BY_DEFAULT = 10;

    /**
     * Возвращает список задач
     * @param type $page [optional] <p>Номер страницы</p>
     * @return type <p>Массив с задачами</p>
     */    
    public static function getTasksList($page = 1)
    {
        $page = intval($page);
        // Смещение (для запроса)
        $offset = ($page - 1) * self::SHOW_BY_DEFAULT;
        // Запрос
        return R::getAll('SELECT * FROM `task` LIMIT ? OFFSET ?', 
            array(self::SHOW_BY_DEFAULT, $offset));
    }

    /**
     * Возвращаем количество задач
     * @return integer
     */
    public static function getTotalTasks() {
        return R::count('task');
    }

    /**
     * Возвращает задачу с указанным id
     * @param integer $id <p>id задачи</p>
     * @return array <p>Массив с информацией о задаче</p>
     */
    public static function getTaskById($id) {
        $id = intval($id);
        return R::load('task', $id)->export();
    }

    /**
     * Возвращает сокращенный текст задачи (первые 100 символов)
     * @param string $description <p>текст задачи</p>
     * @return string <p>сокращенный текст задачи</p>
     */
    public static function getShortDescription($description) {
        if (strlen($description) > 100) {
            return substr($description, 0, 100) . '...';
        } else {
            return $description;
        }
    }

    /**
     * Возвращает текстое пояснение статуса для задачи:<br/>
     * <i>0 - Не выполнена, 1 - Выполнена</i>
     * @param integer $status <p>Статус</p>
     * @return string <p>Текстовое пояснение</p>
     */
    public static function getStatus($status) {
        switch ($status) {
            case '0':
            return 'Не выполнена';
            break;
            case '1':
            return 'Выполнена';
            break;
        }
    }

    /**
     * Возвращает путь к изображению
     * @param integer $id
     * @return string <p>Путь к изображению</p>
     */
    public static function getImage($id) {
        // Название изображения-пустышки
        $noImage = 'no-image.png';
        // Путь к папке с изображениями
        $path = '/upload/images/';
        // Форматы
        $formats = ['.png', '.jpg', 'gif'];
        foreach ($formats as $format) {
            // Путь к изображению
            $pathToProductImage = $path . $id . $format;
            // Если изображение существует
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . $pathToProductImage)) { 
                // Возвращаем путь изображения
                return $pathToProductImage;
            }
        }
        // Возвращаем путь изображения-пустышки
        return $path . $noImage;
    }
}

?>