<?php include ROOT . '/views/layouts/header.php' ?>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <!-- Список задач -->
            <table class="table-bordered table-striped table">
                <tr>
                    <th id="sort-name" class="sort">
                        <a href="/admin/view/name/ASC/page-1" class="<?php if(isset($sortOrder) && $sortColumn == 'name') echo $sortOrder; ?>">
                            Имя <i class="fas fa-filter"></i>
                        </a>
                    </th>
                    <th id="sort-email" class="sort">
                        <a href="/admin/view/email/ASC/page-1" class="<?php if(isset($sortOrder) && $sortColumn == 'email') echo $sortOrder; ?>">
                            Почта <i class="fas fa-filter"></i>
                        </a>
                    </th>
                    <th id="sort-description" class="sort">
                        <a href="/admin/view/status/ASC/page-1" class="<?php if(isset($sortOrder) && $sortColumn == 'status') echo $sortOrder; ?>">
                            Статус <i class="fas fa-filter"></i>
                        </a>
                    </th>
                    <th>Текст задачи</th>
                </tr>
                <?php foreach ($tasks as $task): ?>
                    <tr>
                        <td id="name-table"><?php echo $task['name']; ?></td>
                        <td id="email-table" class="text-break"><?php echo $task['email']; ?></td>
                        <td id="status-table"><?php echo Task::getStatus($task['status']); ?></td>
                        <td id="description-table" class="description" data-toggle="modal" data-target="#descriptionModal" data-whatever="<?php echo $task['id']; ?>">
                            <?php echo Task::getShortDescription($task['description']); ?>
                        </td>
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
                            <!-- Уведомления -->
                            <div class="alert alert-success" role="alert">
                                Изменения сохранены!
                            </div>
                            <!-- ID -->
                            <p id="admin-task-id" class="sr-only"></p>
                            <!-- Имя, Email -->
                            <h6 id="user" class="pb-3"><span id="view-name"></span>, <span id="view-email" class="text-break"></span></h6>
                            <div class="row">
                                <!-- Картинка -->
                                <div class="col-lg-4">
                                    <img src="/upload/images/no-image.png" class="img-fluid" alt="Изображение к тексту задачи">
                                </div>
                                <!-- Поля для редактирования -->
                                <div class="col-lg-8">
                                    <!-- Текст задачи -->
                                    <div class="form-group">
                                        <label>Текст задачи</label>
                                        <textarea rows="5" id="admin-task-description" name="description" class="form-control" placeholder="Введите текст задачи..."></textarea>
                                    </div>
                                    <!-- Статус -->
                                    <div class="form-group">
                                        <label>Статус</label>
                                        <select id="admin-task-status" name="status" class="form-control">
                                            <option value="0">Не выполнена</option>
                                            <option value="1">Выполнена</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                            <button id="save-changes" type="button" class="btn btn-primary">Сохранить</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include ROOT . '/views/layouts/footer.php' ?>