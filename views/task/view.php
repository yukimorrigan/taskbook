<?php include ROOT . '/views/layouts/header.php' ?>

<div class="container-fluid">
    <div class="row">
        <div class="col">
        	<!-- Список задач -->
            <table class="table-bordered table-striped table">
                <tr>
                    <th>Имя</th>
                    <th>E-mail</th>
                    <th>Текст задачи</th>
                    <th>Статус</th>
                </tr>
                <?php foreach ($tasks as $task): ?>
                    <tr>
                        <td><?php echo $task['name']; ?></td>
                        <td class="text-break"><?php echo $task['email']; ?></td>
                        <td class="description" data-toggle="modal" data-target="#descriptionModal" data-whatever="<?php echo $task['id']; ?>">
                            <?php echo Task::getShortDescription($task['description']); ?>
                        </td>
                        <td><?php echo Task::getStatus($task['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <!-- Постраничная навигация -->
            <div class="d-flex justify-content-center"><?php echo $pagination->get(); ?></div>
            <!-- Модальное окно с полным текстом задачи -->
            <div class="modal fade" id="descriptionModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Текст задачи</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-4">
                                    <img src="/upload/images/no-image.png" class="img-fluid" alt="Изображение к тексту задачи">
                                </div>
                                <div class="col-8">
                                    <p id="description"></p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include ROOT . '/views/layouts/footer.php' ?>