<?php include ROOT . '/views/layouts/header.php' ?>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <br>
            <div class="row">
            	<div class="col-lg-3"></div>
	            <div class="col">
			        <div class="alert alert-danger sr-only" role="alert">
						Заполните поля!
					</div>
					<p id="check" class="sr-only"><?php echo $check; ?></p>
					<?php if ($check == 1): ?>
						<div class="alert alert-success" role="alert">
							Ваша задача добавлена в список задач!
						</div>
					<?php endif; ?>
				</div>
				<div class="col-lg-3"></div>
			</div>
            <form action="#" method="post" enctype="multipart/form-data">
                <div class="form-group row">
                    <div class="col-lg-3"></div>
                    <label class="col-lg-1 col-form-label">Ваше имя*</label>
                    <div class="col">
                        <input id="form-name" type="text" name="name" class="form-control" placeholder="Введите имя..." value="<?php echo $name; ?>">
                    </div>
                    <div class="col-lg-3"></div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-3"></div>
                    <label class="col-lg-1 col-form-label">Ваш e-mail*</label>
                    <div class="col">
                        <input id="form-email" type="email" name="email" class="form-control" placeholder="Введите e-mail..." value="<?php echo $email; ?>">
                    </div>
                    <div class="col-lg-3"></div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-3"></div>
                    <label class="col-lg-1 col-form-label">Текст задачи*</label>
                    <div class="col">
                        <textarea id="form-description" name="description" class="form-control" placeholder="Введите текст задачи..."><?php echo $description; ?></textarea>
                    </div>
                    <div class="col-lg-3"></div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-3"></div>
                    <label class="col-lg-1 col-form-label">Картинка*</label>
                    <div class="col">
                        <div class="custom-file">
                            <input id="form-image" type="file" name="image" class="custom-file-input" id="customFileLang">
                            <label class="custom-file-label" for="customFile" data-browse="Обзор">Выберите файл</label>
                        </div>
                    </div>
                    <div class="col-lg-3"></div>
                </div>
                <div class="row">
                    <div class="col text-center">
                        <a id="preview-click" class="btn btn-light">
                            Предварительный просмотр
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3"></div>
                    <div class="col">
                        <div id="preview" class="mb-3 sr-only" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Текст задачи</h5>
                                    </div>
                                    <div class="modal-body">
                                        <h6 id="preview-user" class="pb-3"></h6>
                                        <div class="row">
                                            <div class="col">
                                                <img src="/upload/images/no-image.png" class="preview-img" alt="Изображение к тексту задачи">
                                            </div>
                                            <div class="col">
                                                <p id="preview-description"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3"></div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-3"></div>
                    <div class="col">
                        <button type="submit" name="submit" class="btn btn-primary float-right">Отправить</button>
                    </div>
                    <div class="col-lg-3"></div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include ROOT . '/views/layouts/footer.php' ?>