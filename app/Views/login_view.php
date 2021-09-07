<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo base_url()?>/assets/css/bootstrap.min.css" type="text/css">
    <title>МПТ-ХАНТЕР | Авторизация</title>
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
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-7 col-sm-8">
                    <div class="text-center">
                        <img src="<?php echo base_url()?>/assets/img/design/logo.png"
                            class="img-fluid mx-auto d-block my-2" style="width:150px; height=150px;" alt="">
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center">
                                <div class="row">
                                    <h2>Авторизация</h2>
                                </div>
                            </div>
                            <form method="post" action="<?= site_url('/login') ?>" id="user_login_form"
                                name="user_login_form">
                                <div class="row g-1">
                                    <label for="email" class="form-label">Email:</label>
                                    <input type="email" class="form-control" name="email"
                                        required>
                                    <div id="email_nameBlock" class="form-text">
                                        Введите адрес своей почты
                                    </div>
                                </div>
                                <div class="row g-1">
                                    <label for="password" class="form-label">Пароль:</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="password"
                                            required>
                                            <a class="btn btn-outline-danger" href="<?echo base_url()?>/email/send_resset">Забыли?</a>
                                    </div>
                                    <div id="password_HelpBlock" class="form-text">
                                        Введите пароль.
                                    </div>
                                </div>
                        </div>
                        <?php if(session()->getFlashdata('success_reg')):?>
                        <div class="alert alert-success" role="alert">
                            <?= session()->getFlashdata('success_reg') ?>
                        </div>
                        <?php endif;?>
                        <?php if(session()->getFlashdata('error_login')):?>
                        <div class="alert alert-danger" role="alert">
                            <?= session()->getFlashdata('error_login') ?>
                        </div>
                        <?php endif;?>
                        <?php if(session()->getFlashdata('error_verify')):?>
                        <div class="alert alert-danger" role="alert">
                            <?= session()->getFlashdata('error_verify') ?>
                        </div>
                        <?php endif;?>
                        <?php if(session()->getFlashdata('success_verify')):?>
                        <div class="alert alert-success" role="alert">
                            <?= session()->getFlashdata('success_verify') ?>
                        </div>
                        <?php endif;?>
                        <?php if(session()->getFlashdata('error_resset_pswd')):?>
                        <div class="alert alert-danger" role="alert">
                            <?= session()->getFlashdata('error_resset_pswd') ?>
                        </div>
                        <?php endif;?>
                        <?php if(session()->getFlashdata('success_resset_pswd')):?>
                        <div class="alert alert-success" role="alert">
                            <?= session()->getFlashdata('success_resset_pswd') ?>
                        </div>
                        <?php endif;?>
                        <div class="row g-3 mb-2 text-center">
                            <div class="col">
                                <input type="submit" class="btn btn-danger" value="Войти">
                                <?php if (isset($login_button)){ 
                                echo $login_button ;
                                }
                                ?>
                            </div>
                        </div>
                        <div class="row g-3 text-center">
                            <div class="col">
                                <a href="register" class="text-danger">Ещё нет аккаунта</a>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js"></script>
    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.js"></script>
    <script src="<?php echo base_url()?>/assets/js/bootstrap.min.js"></script>
</body>
</html>