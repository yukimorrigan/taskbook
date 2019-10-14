<?php include ROOT . '/views/layouts/header.php' ?>

<div class="container-fluid">
    <div class="row">
        <div class="col">
        	<!-- Уведомления -->
            <div class="row">
                <div class="col-lg-3"></div>
                <div id="verification-messages" class="col">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-lg-3"></div>
            </div>
            <!-- Форма -->
            <form id="create-task" action="#" method="post" enctype="multipart/form-data">
                <!-- Логин -->
                <div class="form-group row">
                    <div class="col-lg-3"></div>
                    <label class="col-lg-1 col-form-label">Логин</label>
                    <div class="col">
                        <input type="text" name="name" class="form-control" placeholder="Введите логин...">
                    </div>
                    <div class="col-lg-3"></div>
                </div>
                <!-- Пароль -->
                <div class="form-group row">
                    <div class="col-lg-3"></div>
                    <label class="col-lg-1 col-form-label">Пароль</label>
                    <div class="col">
                        <input type="password" name="password" class="form-control" placeholder="Введите пароль...">
                    </div>
                    <div class="col-lg-3"></div>
                </div>
                <!-- Отправить форму -->
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