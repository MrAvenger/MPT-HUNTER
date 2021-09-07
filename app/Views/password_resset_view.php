<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo base_url()?>/assets/css/bootstrap.min.css" type="text/css">
    <title>МПТ-ХАНТЕР | Изменение пароля</title>
</head>

<body class="bg-light">
    <main>
        <div class="container mt-5 mb-5">
            <div class="row justify-content-center ">
                <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-7 col-sm-8">
                    <div class="text-center">
                        <img src="<?php echo base_url()?>/assets/img/design/logo.png"
                            class="img-fluid mx-auto d-block my-2" style="width:150px; height=150px;" alt="">
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center">
                                <div class="row">
                                    <h2>Изменение пароля</h2>
                                </div>
                            </div>
                            <form method="post" action="<?= site_url('/password/change/'.$code) ?>"
                                id="user_change_pswd_form" name="user_change_pswd_form">
                                <div class="row g-1">
                                    <label for="password" class="form-label">Пароль:</label>
                                    <input type="password" class="form-control" name="password"
                                        placeholder="MegaSecter!Pa1" required>
                                    <div id="password_nameBlock" class="form-text">
                                        Введите новый пароль
                                    </div>
                                </div>
                                <div class="row g-1">
                                    <label for="password" class="form-label">Подтвердите пароль:</label>
                                    <input type="password" class="form-control" name="confirm_password"
                                        placeholder="MegaSecter!Pa1" required>
                                    <div id="confirm_password_nameBlock" class="form-text">
                                        Введите пароль ещё раз
                                    </div>
                                </div>
                        </div>
                        <?php if(session()->getFlashdata('success_change_pswd')):?>
                        <div class="alert alert-success" role="alert">
                            <?= session()->getFlashdata('success_change_pswd') ?>
                        </div>
                        <?php endif;?>
                        <?php if(session()->getFlashdata('error_change_pswd')):?>
                        <div class="alert alert-danger" role="alert">
                            <?= session()->getFlashdata('error_change_pswd') ?>
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
                                <input type="submit" class="btn btn-danger" value="Изменить">
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