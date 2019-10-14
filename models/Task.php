<?php 

/**
 * Класс Task - модель для работы с задачами
 */
class Task 
{
    // Количество отображаемых задач по умолчанию
    const SHOW_BY_DEFAULT = 9;

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
        return R::findAll('task', ' LIMIT ? OFFSET ?', 
            array(self::SHOW_BY_DEFAULT, $offset));
    }

    /**
     * Возвращает отсортированный список задач
     * @param string $sortColumn <p>Столбец, по которому производится сортировка</p>
     * @param string $sortOrder <p>Порядок сортировки</p>
     * @param type $page [optional] <p>Номер страницы</p>
     * @return type <p>Массив с задачами</p>
     */    
    public static function getSortTasksList($sortColumn = 'status', $sortOrder = 'ASC', $page = 1)
    {
        $page = intval($page);
        // Параметры сортировки
        $partOfQuery = sprintf(' ORDER BY %s %s ', $sortColumn, $sortOrder);
        // Смещение (для запроса)
        $offset = ($page - 1) * self::SHOW_BY_DEFAULT;
        // Запрос
        return R::findAll('task', $partOfQuery . ' LIMIT ? OFFSET ?', 
            array(self::SHOW_BY_DEFAULT, $offset));
    }

    /**
     * Возвращает количество задач
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
     * Добавляет новую задачу
     * @param string $name <p>Название</p>
     * @param string $email <p>E-main</p>
     * @param string $description <p>Текст задачи</p>
     * @return boolean <p>Результат добавления записи в таблицу</p>
     */
    public static function createTask($name, $email, $description) {
        // Сосздаем объект task
        $task = R::dispense('task');
        // Заполняем его поля
        $task->name = $name;
        $task->email = $email;
        $task->description = $description;
        $task->status = 0;
        // Сохраняем в таблице
        $id = R::store($task);
        // Вернуть результат операции
        return $id;
    }

    /**
     * Редактирует задачу с заданным id
     * @param integer $id <p>id задачи</p>
     * @param array $options <p>Массив с информацей о задаче</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function updateTask($id, $options) {
        $id = intval($id);
        // Находим объект task по id
        $task = R::load('task', $id);
        // Изменяем его поля
        $task->name = $options['name'];
        $task->email = $options['email'];
        $task->description = $options['description'];
        $task->status = (int) $options['status'];
        // Сохраняем в таблице
        $id = R::store($task);
        // Вернуть результат операции
        return $id;
    }

    /**
     * Возвращает аттрибут таблицы Auto_increment
     * @return integer
     */
    public static function getAutoIncrement() {
        return R::getRow("SHOW TABLE STATUS FROM `taskbook` WHERE `name` LIKE 'task'")['Auto_increment'];
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
        // Проверяем формат
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

    /**
     * Проверяет email
     * @param string $email <p>E-mail</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function checkEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Проверяет формат изображения
     * @param string $fileName <p>Путь к файлу</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function checkImageFormat($fileName) {
        // Получить формат изображения
        $imageType = exif_imageType($fileName);
        // Если формат изображения JPG/GIF/PNG - вернуть значение истина
        if ($imageType == IMAGETYPE_JPEG || $imageType == IMAGETYPE_GIF || $imageType == IMAGETYPE_PNG) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Изменяет размер изображения
     * @param string $fileName <p>Путь к файлу</p>
     * @param int $maxWidth <p>Ширина изображения после изменения</p>
     * @param int $maxHeight <p>Высота изображения после изменения</p>
     * @param int $altWidth <p>Ширина изображения до изменения</p>
     * @param int $altHeight <p>Высота изображения до изменения</p>
     */
    public static function changeImageSize($fileName, $maxWidth, $maxHeight, $altWidth, $altHeight) {
        // Получить формат изображения
        $imageType = exif_imageType($fileName);
        // Пропорциональное соотношение ширины и высоты изображения
        $ratio = $altWidth / $altHeight;
        // Задаем изображению новые ширину и высоту
        if( $ratio >= 1) {
            // Если ширина больше или равна высоте
            $width = $maxWidth;
            $height = $maxWidth / $ratio;
        }
        else {
            // Если высота больше чем ширина
            $width = $maxHeight * $ratio;
            $height = $maxHeight;
        }
        // Ресурс исходного изображения
        $src = imagecreatefromstring(file_get_contents($fileName));
        // Ресурс целевого изображения
        $dst = imagecreatetruecolor($width, $height);
        // Изменить размеры изображения
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $altWidth, $altHeight);
        // Сохранить изображение с нужным форматом   
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                imagejpeg($dst, $fileName);
                break;
            case IMAGETYPE_GIF:
                imagegif($dst, $fileName);
                break;
            case IMAGETYPE_PNG:
                imagepng($dst, $fileName);
                break;
        }
        // Освободить память
        imagedestroy($src);
        imagedestroy($dst);
    }
}

?>