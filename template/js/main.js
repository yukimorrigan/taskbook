/* Предосмотр изображений */
function readURL(input, img) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $(img).attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

$( document ).ready(function() {
    /* Определить активную страницу */
    if (window.location.href.indexOf('task/create') > -1) {
        $('#create_menu_item').addClass('active');
    } else if (window.location.href.indexOf('login') > -1) {
        $('#login_menu_item').addClass('active');
    } else {
        $('#list_menu_item').addClass('active');
    }

    /* Отображение страницы 'Список задач' */
    // Отображение модального окна 'Текст задачи'
    $('#descriptionModal').on('show.bs.modal', function (event) {
        // Кнопка, на которую нажал пользователь
        var button = $(event.relatedTarget);
        // Информация, записанная в data-whatever
        var id = button.data('whatever');
        // Асинхронный запрос - Получить информацию о задаче по id
        $.post('/task/getTask/' + id, {}, function (data) {
            var task = JSON.parse(data);
            $('.modal-body #user').html(task['name'] + ', ' + task['email']);
            $('.modal-body #description').html(task['description']);
            $('.modal-body img').attr('src', task['image']);
        });
    });

    /* Отображение страницы 'Добавить задачу' */
    // Ответ возвращаемый от сервера
    switch ($('#check').text()) {
        // Неверный формат изображения
        case '-1':
            // Показать блок "неверный формат"
            $('#check-format').removeClass('sr-only');
            // Обвести поле загрузки файла красным контуром
            $('#form-image').addClass('is-invalid');
            break;
        // Пустые поля формы
        case '0':
            // Что-то не так, проверяем валидность формы
            checkCreateForm();
            break;
        // Все данные валидны
        case '1':
            // Убрать блок "неверный формат"
            $('#check-format').addClass('sr-only');
            // Убрать красную обводку у поля загрузки файла
            $('#form-image').removeClass('is-invalid');
            break;
        default:
            break;
    }

    // При нажатии на кнопку "предварительный просмотр"
    $('#preview-click').click(function() {
        // Проверить валидность заполненных данных
        var valid = checkCreateForm();
        if (!valid) {
            // Если данные не валидны
            // Скрыть форму
            $('#preview').addClass('sr-only');
        } else {
            // Иначе
            // Показать форму
            $('#preview').removeClass('sr-only');
        }
    });

    // При вводе текста в форму
    $('#create-task input, #create-task textarea').keyup(function(){
        // убрать красную обводку
        $(this).removeClass('is-invalid');
        // изменить текст в блоке предварительного просмотра
        var formName = $(this).attr('id').split(/\-/)[1];
        $('#preview-' + formName).html($(this).val());
    });

    // Проверить валидность формы
    function checkCreateForm() {
        // Данные из формы
        var name = $('#form-name').val();
        var email = $('#form-email').val();
        var description = $('#form-description').val();
        var image = $('#form-image').val();
        // Флаг валидности формы
        var valid = true;
        
        // Проверяем, являются ли текстовые поля пустыми 
        // Если да - обводим их красным контуром (форма не валидна),
        // Иначе - убираем красный контур
        if (name == '') {
            $('#form-name').addClass('is-invalid');
            valid = false;
        } else {
            $('#form-name').removeClass('is-invalid');
        }

        if (email == '') {
            $('#form-email').addClass('is-invalid');
            valid = false;
        } else {
            $('#form-email').removeClass('is-invalid');
        }

        if (description == '') {
            $('#form-description').addClass('is-invalid');
            valid = false;
        } else {
            $('#form-description').removeClass('is-invalid');
        }

        // Если файлы загружались через форму
        if (document.getElementById('form-image').files[0] != null) {
            // Получить имя файла
            var fileName = document.getElementById('form-image').files[0].name;
            // Проверить формат файла
            var reg = /\.(png|gif|jpe?g)$/i;
            // Проверить формат файла
            if (reg.test(fileName)) {
                // Если имя файла прошло проверку
                // Скрыть блок "неверный формат"
                $('#check-format').addClass('sr-only');
                // Убрать красную обводку вокруг поля
                $('#form-image').removeClass('is-invalid');
            } else {
                // Плказать блок "неверный формат"
                $('#check-format').removeClass('sr-only');
                // Добавить красную обводку вокруг поля
                $('#form-image').addClass('is-invalid');
                // Форма не валидна
                valid = false;
            }
        } else {
            // Добавить красную обводку вокруг поля
            $('#form-image').addClass('is-invalid');
            // Форма не валидна
            valid = false;
        }

        // Если форма валидна
        if (valid) {
            // Скрыть сообщение об ошибке
            $('#check-field').addClass('sr-only');
            // Показать сообщение об успехе
            $('.alert-success').removeClass('sr-only');
        } else {
            $('#check-field').removeClass('sr-only');
            $('.alert-success').addClass('sr-only');
        }

        // Вернуть успешность прохождения проверки валидации
        return valid;
    }

    // Предосмотр картинки
    $('#form-image').change(function(){
        // Показать путь
        var fileName = 'Выберите файл';
        if (document.getElementById('form-image').files[0] != null) {
            fileName = document.getElementById('form-image').files[0].name;
        }   
        $(this).next('.custom-file-label').addClass('selected').html(fileName);
        // Показать картинку
        readURL(this, '.preview-img');
    });
});