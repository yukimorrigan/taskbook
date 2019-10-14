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
    } else if (window.location.href.indexOf('admin') > -1) {
        $('#login_menu_item').addClass('active');
    } else {
        $('#list_menu_item').addClass('active');
    }

    /* Отображение модального окна 'Текст задачи' */
    $('#descriptionModal').on('show.bs.modal', function (event) {
        // Скрыть уведомления
        $('.modal-body .alert').addClass('sr-only');
        // Кнопка, на которую нажал пользователь
        var button = $(event.relatedTarget);
        // Информация, записанная в data-whatever
        var id = button.data('whatever');
        // Асинхронный запрос - Получить информацию о задаче по id
        $.post('/task/getTask/' + id, {}, function (data) {
            var task = JSON.parse(data);
            $('#view-name').html(task['name']);
            $('#view-email').html(task['email']);
            $('#description').html(task['description']);
            $('#task-img').attr('src', task['image']);
            $('#admin-task-id').html(task['id']);
            $('#admin-task-description').val(task['description']);
            $('#admin-task-status').val(task['status']);
        });
    });

    /* Отображение страницы 'Добавить задачу' */    
    // Если пришел ответ от сервера
    // И результат выполнения операции не был успешен
    if ($('#verification-messages').children().length > 0 && 
        $('#verification-messages').children('.alert-success').length == 0) {
        // Проверяем форму
        checkCreateForm(false);
    }

    // Проверить валидность формы
    function checkCreateForm(addMessages = true) {
        // Данные из формы
        var name = $('#form-name').val();
        var email = $('#form-email').val();
        var description = $('#form-description').val();
        var image = $('#form-image').val();
        // Флаг - наличие пустых полей
        var empty = false;
        // Флаг успешность проверки
        var result = true;
        
        // Проверяем, являются ли текстовые поля пустыми 
        // Если да - обводим их красным контуром,
        // Иначе - убираем красный контур
        if (name == '') {
            $('#form-name').addClass('is-invalid');
            empty = true;
        } else {
            $('#form-name').removeClass('is-invalid');
        }

        if (email == '') {
            $('#form-email').addClass('is-invalid');
            empty = true;
        } else {
            $('#form-email').removeClass('is-invalid');
        }

        if (description == '') {
            $('#form-description').addClass('is-invalid');
            empty = true;
        } else {
            $('#form-description').removeClass('is-invalid');
        }

        // Если есть пустые поля
        if (empty) {
            // Если можно добавлять сообщения
            if (addMessages) {
                // Добавить блок "заполните поля"
                $('#verification-messages').append(
                    '<div class="alert alert-danger" role="alert">Заполните поля!</div>');
            }
            // Форма не валидна
            result = false;
        }

        // Если имейл валиден
        if (checkEmail(email)) {
            // Убрать красную обводку вокруг поля
            $('#form-email').removeClass('is-invalid');           
        } else {
            // Добавить красную обводку вокруг поля
            $('#form-email').addClass('is-invalid');
            // Если можно добавлять сообщения
            if (addMessages) {
                // Добавить блок "неверный e-mail"
                $('#verification-messages').append(
                    '<div class="alert alert-danger" role="alert">Неверный email</div>');
            }
            // Форма не валидна
            result = false;
        }

        // Если файлы загружались через форму
        if (document.getElementById('form-image').files[0] != null) {
            // Получить имя файла
            var fileName = document.getElementById('form-image').files[0].name;
            // Проверить формат файла
            var reg = /\.(png|gif|jpe?g)$/i;
            // Если имя файла прошло проверку 
            if (reg.test(fileName)) {
                // Убрать красную обводку вокруг поля
                $('#form-image').removeClass('is-invalid');
            } else {
                // Если можно добавлять сообщения
                if (addMessages) {
                    // Добавить блок "неверный формат"
                    $('#verification-messages').append(
                        '<div class="alert alert-danger" role="alert">' + 
                        'Неверный формат изображения. Допустимые форматы: JPG/GIF/PNG</div>');
                }
                // Добавить красную обводку вокруг поля
                $('#form-image').addClass('is-invalid');
                // Форма не валидна
                result = false;
            }
        } else {
            // Если можно добавлять сообщения
            if (addMessages) {
                // Добавить блок "Изображение не было загружено"
                $('#verification-messages').append(
                    '<div class="alert alert-danger" role="alert">' + 
                    'Изображение не было загружено</div>');
            }
            // Добавить красную обводку вокруг поля
            $('#form-image').addClass('is-invalid');
            // Форма не валидна
            result = false;
        }

        // Вернуть успешность проверки валидации
        return result;
    }

    // Проверить валидность email-а
    function checkEmail(email) {
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) {
            return true;
        } else {
            return false;
        }
    }

    // При нажатии на кнопку "предварительный просмотр"
    $('#preview-click').click(function() {
        // Удалить все сообщения
        $('#verification-messages').empty();
        // Проверить валидность заполненных данных
        var valid = checkCreateForm();
        // Если данные валидны
        if (valid) {
            // Показать форму
            $('#preview').removeClass('sr-only');
            
        } else {
            // Иначе - скрыть
            $('#preview').addClass('sr-only');
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

    /* Редактирование задач для админа */
    $('#save-changes').click(function(){
        // id задачи
        var id = $('#admin-task-id').text();
        // поля задачи
        options = {};
        options['name'] = $('#view-name').text();
        options['email'] = $('#view-email').text();
        options['description'] = $('#admin-task-description').val();
        options['status'] = $('#admin-task-status').val();
        // парсим в строку JSON
        options = JSON.stringify(options);
        // асинхронный запрос
        $.post('/admin/edit/' + id + '/' + options, {}, function (response) {
            // если результат выполнения операции успешен
            if (response) {
                // Показать уведомления
                $('.modal-body .alert').removeClass('sr-only');
                // Обновить поле в таблице
                $.post('/task/getTask/' + id, {}, function (data) {
                    // парсим из JSON в массив
                    var task = JSON.parse(data);
                    // находим строку таблицы с data-whatever=id
                    var descField = $('[data-whatever="' + id + '"]');
                    // заменяем описание в строке таблицы
                    descField.text(getShort(task['description']));
                    // заменяем статус в строке таблицы
                    if (task['status'] == '1') {
                        descField.siblings('#status-table').text('Выполнена');
                    } else {
                        descField.siblings('#status-table').text('Не выполнена');
                    }
                });
            }
        });
    });

    /* Получить первые 100 символов */
    function getShort(text) {
        if (text.length > 100) {
            return text.substr(0, 100) + '...';
        } else {
            return text.substr(0, 100);
        }
    }

    /* Сортировка на странице админа */
    if (window.location.href.indexOf('admin') > -1) {
        try {
            // Если в поисковое строке есть страница - достаем ее
            $page = window.location.href.match('page-([0-9]+)')[1];
        } catch (err) {
            // Иначе - страница будет первой
            $page = 1;
        }
        // Для каждого заголовка столбца, что отвечает за сортировку
        $('.sort a').each(function() {
            // Заменяем страницу в ссылке на текущую страницу
            var link = $(this).attr('href').replace(/[0-9]+/, $page);
            $(this).attr('href', link);
            // Переключаем порядок сортировки
            if ($(this).hasClass('ASC')){
                // Если выбрана сортировка по возрастанию
                // Заменяем порядок сортировки в ссылке на сортировку по убыванию
                var link = $(this).attr('href').replace(/ASC/, 'DESC');
                $(this).attr('href', link);
            } else {
                // Если выбрана сортировка по убыванию
                // Заменяем порядок сортировки в ссылке на сортировку по возрастанию
                var link = $(this).attr('href').replace(/DESC/, 'ASC');
                $(this).attr('href', link);
            }
        });

    }
});