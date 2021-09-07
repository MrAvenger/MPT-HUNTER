<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body class="bg-dark">
    <!--content start-->
    <main class="my-5 pt-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 col-xl-11 col-xxl-10">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Ваш профиль</h5>
                            <div class="container">
                                <div class="row my-2">
                                    <h5>Личные данные:</h5>
                                    <div class="col-md-6 col-sm-12 my-1 bg-light">
                                        <label>Имя: <?= session('first_name')?></label>
                                    </div>
                                    <div class="col-md-6 col-sm-12 my-1 bg-light">
                                        <label>Фамилия: <?= session('last_name')?></label>
                                    </div>
                                    <div class="col-md-6 col-sm-12 my-1 bg-light">
                                        <label>Отчество:
                                            <?php if(empty(session('middle_name'))) echo 'Не указано';else echo session('middle_name')?></label>
                                    </div>
                                    <div class="col-md-6 col-sm-12 my-1 bg-light">
                                        <label>Пол:
                                            <?php if(empty(session('sex'))) echo 'Не указано';else echo session('sex')?></label>
                                    </div>
                                    <div class="col-md-6 col-sm-12 my-1 bg-light">
                                        <label>Email: <?= session('email')?></label>
                                    </div>
                                    <div class="col-md-6 col-sm-12 my-1 bg-light">
                                        <label>Номер телефона:
                                            <?php if(empty(session('number_phone'))) echo 'Не указано';else echo session('number_phone')?></label>
                                    </div>
                                    <div class="col-md-12 col-sm-12 my-1 bg-light">
                                        <label>Дата рождения:
                                            <?php if(empty(session('date_birth'))) echo 'Не указано';else echo session('date_birth')?></label>
                                    </div>
                                    <div class="col-md-12 col-sm-12 my-1 bg-light">
                                        <label>Фото:
                                            <?php if(empty(session('photo'))) echo '<img src="'.base_url().'/assets/img/design/default.jpg" class="img-fluid" style="max-width:200px;" alt="">';else echo '<img src="'.base_url().'/writable/uploads/profile/'.session('id').'/'.session('photo').'" class="img-fluid" style="max-width:200px;" alt="">' ?></label>
                                    </div>
                                </div>
                                <div class="row my-2">
                                    <?php if(session()->getFlashdata('success_edit_profile')):?>
                                    <div class="alert alert-success" role="alert">
                                        <?= session()->getFlashdata('success_edit_profile') ?>
                                    </div>
                                    <?php endif;?>
                                </div>
                                <div class="row my-2">
                                    <div class="col-md-4 col-sm-12">
                                        <a class="btn btn-outline-danger"
                                            href="<?php echo base_url().'/profile/edit'?>">Редактировать личные данные
                                        </a>
                                    </div>
                                </div>
                                <?php if(session('role')=='Работодатель'):?>
                                <div class="row my-2">
                                    <h5>Данные по организации:</h5>
                                    <div class="col-md-6 col-sm-12 my-1 bg-light">
                                        <label>Ваша должность:
                                            <?php if(session('post')): echo session('post'); else: echo 'Не указано'; endif;?></label>
                                    </div>
                                    <div class="col-md-6 col-sm-12 my-1 bg-light">
                                        <label>Наименование:
                                            <?php if(session('org_name')): echo session('org_name'); else: echo 'Не указано'; endif;?></label>
                                    </div>
                                    <div class="col-md-6 col-sm-12 my-1 bg-light">
                                        <label>Адрес:
                                            <?php if(session('org_adress')): echo session('org_adress'); else: echo 'Не указано'; endif;?></label>
                                    </div>
                                    <div class="col-md-6 col-sm-12 my-1 bg-light">
                                        <label>Описание:
                                            <?php if(empty(session('org_description'))) echo 'Не указано';else echo session('org_description')?></label>
                                    </div>
                                    <div class="col-md-12 col-sm-12 my-1 bg-light">
                                        <label>Фото:
                                            <?php if(empty(session('org_photo'))) echo '<img src="'.base_url().'/assets/img/design/org_photo.jpg" class="img-fluid" style="max-width:200px;" alt="">';else echo '<img src="'.base_url().'/writable/uploads/organizations/'.session('id').'/'.session('org_photo').'" class="img-fluid" style="max-width:200px;" alt="">'?></label>
                                    </div>
                                </div>
                                <div class="row my-2">
                                    <?php if(session()->getFlashdata('success_edit_org')):?>
                                    <div class="alert alert-success" role="alert">
                                        <?= session()->getFlashdata('success_edit_org') ?>
                                    </div>
                                    <?php endif;?>
                                </div>
                                <div class="row my-2">
                                    <div class="col-md-4 col-sm-12">
                                        <a class="btn btn-outline-danger"
                                            href="<?php echo base_url().'/organization/edit'?>">Редактировать данные
                                            организации</a>
                                    </div>
                                </div>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!--content end-->
    <!-- JavaScript -->
    <script src="<?php echo base_url()?>/assets/js/jquery-3.6.0.min.js"></script>
</body>

</html>