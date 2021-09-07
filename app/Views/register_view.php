<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo base_url()?>/assets/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.1.1/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>/assets/css/bootstrap.min.css" type="text/css">
    <title>МПТ-ХАНТЕР | Регистрация</title>
</head>
<style>
input.error {
    border: 1px solid #ff0000;
}

label.error {
    color: #ff0000;
    font-weight: normal;
}
</style>

<body class="bg-light">
    <main>
        <div class="container mt-5 mb-5">
            <div class="row justify-content-center ">
                <div class="col-xxl-5 col-xl-6 col-lg-7 col-md-8 col-sm-9">
                    <div class="text-center">
                        <img src="<?php echo base_url()?>/assets/img/design/logo.png"
                            class="img-fluid mx-auto d-block my-2" style="width:150px; height=150px;" alt="">
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center">
                                <div class="row">
                                    <h2>Форма регистрации студента</h2>
                                </div>
                            </div>
                            <form method="post" action="<?= site_url('/register') ?>" id="user_registration_form"
                                name="user_registration_form">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="first_name" class="form-label">Имя:</label>
                                        <input type="text" class="form-control" name="first_name" placeholder="Иван"
                                            required>
                                        <div id="first_name_HelpBlock" class="form-text">
                                            Имя должно содержать минимум 1 символ.
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="last_name" class="form-label">Фамилия:</label>
                                        <input type="text" class="form-control" name="last_name" placeholder="Иванов"
                                            required>
                                        <div id="last_name_HelpBlock" class="form-text">
                                            Фамилия должна содержать минимум 1 символ.
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="middle_name" class="form-label">Отчество:</label>
                                        <input type="text" class="form-control" name="middle_name"
                                            placeholder="Иванович">
                                        <div id="middle_name_nameBlock" class="form-text">
                                            Оставьте пустым, если нет отчества.
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="specialization" class="form-label">Специальность</label>
                                        <select id="specialization" name="specialization"
                                            class="form-select"
                                            aria-label="Default select example">
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="groups" class="form-label">Группа</label>
                                        <select id="groups" name="groups" class="form-select"
                                            aria-label="Default select example">
                                        </select>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email:</label>
                                        <input type="email" class="form-control" name="email"
                                            placeholder="vd_50_m.a.ivanov@mpt.ru" required>
                                        <div id="email_nameBlock" class="form-text">
                                            Введите адрес своей почты мпт
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="password" class="form-label">Пароль:</label>
                                        <input type="password" class="form-control" name="password"
                                            placeholder="mYSup11!" required>
                                        <div id="password_HelpBlock" class="form-text">
                                            Пароль должен содержать минимум 8 символов.
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <?php if(session()->getFlashdata('error_reg')):?>
                        <div class="alert alert-danger" role="alert">
                            <?= session()->getFlashdata('error_reg') ?>
                        </div>
                        <?php endif;?>
                        <?php if (isset($validation)): ?>
                        <div class="col-12">
                            <div class="alert alert-danger" role="alert">
                                <?= $validation->listErrors() ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="row g-3 mb-2 text-center">
                            <div class="col">
                                <input type="submit" class="btn btn-danger" value="Зарегистрироваться" />
                            </div>
                        </div>
                        <div class="row g-3 text-center">
                            <div class="col">
                                <a href="login" class="text-danger">Уже есть аккаунт</a>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
        </div>
    </main>
    <script src="<?php echo base_url()?>/assets/js/jquery-3.6.0.min.js"></script>
    <script src="<?php echo base_url()?>/assets/js/select2.min.js"></script>
    <script src="<?php echo base_url()?>/assets/js/select2.ru.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/additional-methods.min.js"></script>
    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.js"></script>
    <script src="<?php echo base_url()?>/assets/js/bootstrap.min.js"></script>
    <script type="text/javascript">
    const select_spec=null;
    const select_group =null;
    const type_select=2;
    </script>
    <script src="<?php echo base_url()?>/assets/js/register_user.js"></script>
    <script src="<?php echo base_url()?>/assets/js/script_select_spec_group.js"></script>
</body>

</html>