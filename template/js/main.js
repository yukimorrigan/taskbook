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
});