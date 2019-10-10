$( document ).ready(function() {
    // Отображение модального окна 'Текст задачи'
    $('#descriptionModal').on('show.bs.modal', function (event) {
        // Кнопка, на которую нажал пользователь
        var button = $(event.relatedTarget);
        // Информация, записанная в data-whatever
        var id = button.data('whatever');
        // Асинхронный запрос - Получить информацию о задаче по id
        $.post('/task/showDescription/' + id, {}, function (data) {
            var task = JSON.parse(data);
            $('.modal-body #description').html(task['description']);
            $('.modal-body img').attr('src', task['image']);
        });
    });
});