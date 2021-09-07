<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <style>
    input.error {
        border: 1px solid #ff0000;
    }

    label.error {
        color: #ff0000;
        font-weight: normal;
    }
    </style>
</head>

<body class="bg-dark">
    <!--content start-->
    <main class="my-5 pt-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 col-xl-11 col-xxl-10">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Редактирование профиля</h5>
                            <div class="container">
                                <div class="row my-2">
                                    <form id="pers_data" name="pers_data" method="post"
                                        action="<?php echo base_url()?>/profile/edit" enctype="multipart/form-data">
                                        <div class="row my-2 g-3">
                                            <div class="col-md-6 col-sm-12 my-1 bg-light">
                                                <div class="mb-3">
                                                    <label for="first_name" class="form-label">Имя</label>
                                                    <input class="form-control" id="first_name" name="first_name"
                                                        type="text" aria-label="" value="<?= session('first_name')?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-12 my-1 bg-light">
                                                <div class="mb-3">
                                                    <label for="last_name" class="form-label">Фамилия</label>
                                                    <input class="form-control" id="last_name" name="last_name"
                                                        type="text" aria-label="" value="<?= session('last_name')?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row my-2 g-3">
                                            <div class="col-md-6 col-sm-12 my-1 bg-light">
                                                <div class="mb-3">
                                                    <label for="middle_name" class="form-label">Отчество</label>
                                                    <input class="form-control" id="middle_name" name="middle_name"
                                                        type="text" aria-label="" value="<?= session('middle_name')?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-12 my-1 bg-light">
                                                <label for="sex" class="form-label">Пол</label>
                                                <select class="form-select" id="sex" name="sex"
                                                    aria-label="Default select example">
                                                    <?php if(empty(session('sex'))){
                                            echo "<option value='' selected>Не указано</option>";
                                            echo '<option value="Мужской">Мужской</option>';
                                            echo '<option value="Женский">Женский</option>';
                                        }
                                        else if(session('sex')=='Мужской'){
                                            echo '<option value="null">Не указано</option>';
                                            echo '<option value="Мужской" selected>Мужской</option>';
                                            echo '<option value="Женский">Женский</option>'; 
                                        }
                                        else if(session('sex')=='Женский'){
                                            echo '<option value="null">Не указано</option>';
                                            echo '<option value="Мужской">Мужской</option>';
                                            echo '<option value="Женский" selected>Женский</option>'; 
                                        }
                                        ?>
                                                </select>
                                            </div>
                                            <?php if(session('role')=='Студент'): ?>
                                            <div class="col-md-6 col-sm-12 my-1 bg-light">
                                                <div class="mb-3">
                                                    <label for="specialization" class="form-label">Специальность</label>
                                                    <select id="specialization" name="specialization"
                                                        class="form-select" aria-label="Default select example">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-12 my-1 bg-light">
                                                <div class="mb-3">
                                                    <label for="groups" class="form-label">Группа</label>
                                                    <select id="groups" name="groups" class="form-select"
                                                        aria-label="Default select example">
                                                    </select>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="row my-2 g-3">
                                            <div class="col-md-6 col-sm-12 my-1 bg-light">
                                                <div class="mb-3">
                                                    <label for="number_phone" class="form-label">Номер телефона</label>
                                                    <input class="form-control" id="number_phone" name="number_phone"
                                                        type="tel" aria-label="" value="<?= session('number_phone')?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-12 my-1 bg-light">
                                                <div class="mb-3">
                                                    <label for="datepicker" class="form-label">Дата рождения</label>
                                                    <input class="form-control mt-1" id="datepicker" name="date_birth"
                                                        aria-label="" value="<?= session('date_birth')?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row my-2 g-3">
                                            <div class="col-md-6 col-sm-12 my-1 bg-light">
                                                <div class="mb-3">
                                                    <label for="formFile" class="form-label">Новое фото</label>
                                                    <input class="form-control" type="file" id="photo" name="photo">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-12 my-1 bg-light">
                                                <div class="mb-3">
                                                    <label for="password" class="form-label">Новый пароль</label>
                                                    <input class="form-control" id="password" name="password"
                                                        type="password" aria-label="">
                                                </div>
                                            </div>
                                        </div>
                                        <?php if (isset($validation)): ?>
                                        <div class="col-12">
                                            <div class="alert alert-danger" role="alert">
                                                <?= $validation->listErrors() ?>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                        <?php if(session()->getFlashdata('error_edit_profile')):?>
                                        <div class="alert alert-danger" role="alert">
                                            <?= session()->getFlashdata('error_edit_profile') ?>
                                        </div>
                                        <?php endif;?>
                                        <div class="row my-2">
                                            <div class="row">
                                                <div class="col-md-4 col-sm-12 col-xl-3">
                                                    <input type="submit" class="btn btn-outline-danger"
                                                        value="Сохранить"></input>
                                                    <a class="btn btn-outline-dark"
                                                        href="<?php echo site_url('profile')?>">Отмена</a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
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
    <script src="<?php echo base_url()?>/assets/js/select2.min.js"></script>
    <script src="<?php echo base_url()?>/assets/js/select2.ru.js"></script>
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <script src="https://unpkg.com/gijgo@1.9.13/js/messages/messages.ru-ru.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery.maskedinput@1.4.1/src/jquery.maskedinput.min.js"
        type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/additional-methods.min.js"></script>
    <script src="<?php echo base_url()?>/assets/js/personal_data.js"></script>
    <?php if(session('role')=='Студент'):?>
    <script type="text/javascript">
    var select_spec = <?php if(session('specialization_id')): echo session('specialization_id'); else: echo 'null'; endif;?>;
    const select_group = <?php if(session('group_id')): echo session('group_id'); else: echo 'null'; endif;?>;
    const type_select = 2;
    </script>
    <script src="<?php echo base_url()?>/assets/js/script_select_spec_group.js"></script>
    <?php endif;?>
</body>

</html>