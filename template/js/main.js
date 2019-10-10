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

    $('#preview-click').click(function() {
        fill = checkAddForm();

        if (!fill) {
            $('#preview').addClass('sr-only');
        } else {
            var user = $('#form-name').val();
            var email = $('#form-email').val();
            var description = $('#form-description').val();
            $('#preview-user').html(user + ', ' + email);
            $('#preview-description').html(description);
            $('#preview').removeClass('sr-only');
        }
    });

    if ($('#check').text() == '0') {
        checkAddForm();
    }

    function checkAddForm() {
        var user = $('#form-name').val();
        var email = $('#form-email').val();
        var description = $('#form-description').val();
        var image = $('#form-image').val();
        var fill = true;

        if (user == '') {
            $('#form-name').addClass('is-invalid');
            fill = false;
        } else {
            $('#form-name').removeClass('is-invalid');
        }

        if (email == '') {
            $('#form-email').addClass('is-invalid');
            fill = false;
        } else {
            $('#form-email').removeClass('is-invalid');
        }

        if (description == '') {
            $('#form-description').addClass('is-invalid');
            fill = false;
        } else {
            $('#form-description').removeClass('is-invalid');
        }

        if (image == '') {
            $('#form-image').addClass('is-invalid');
            fill = false;
        } else {
            $('#form-image').removeClass('is-invalid');
        }

        if (!fill) {
            $('.alert-danger').removeClass('sr-only');
            $('.alert-success').addClass('sr-only');
        } else {
            $('.alert-danger').addClass('sr-only');
            $('.alert-success').removeClass('sr-only');
        }

        return fill;
    }

    // Предосмотр картинки
    $('#form-image').change(function(){
        // Показать путь
        fileName = 'Выберите файл';
        if (document.getElementById('form-image').files[0] != null) {
            fileName = document.getElementById('form-image').files[0].name;
        }   
        $(this).next('.custom-file-label').addClass('selected').html(fileName);

        // Показать картинку
        readURL(this, '.preview-img');
    });
});