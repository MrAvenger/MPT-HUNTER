<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
    .u-AhunterSuggestions {
        margin-top: 50px;
        border: 1px solid #AAAAAA;
        background: white;
        overflow: auto;
        border-radius: 2px;
    }

    .u-AhunterSuggestion {
        padding: 5px;
        white-space: nowrap;
        overflow: hidden;
    }

    .u-AhunterSelectedSuggestion {
        background: #E7E7E7;
    }

    .u-AhunterSuggestions strong {
        font-weight: bold;
        color: #1B7BB1;
    }

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
                            <h5 class="card-title">Редактирование данных организации</h5>
                            <div class="container">
                                <div class="row my-2">
                                    <form method="post" id="org_data" name="org_data"
                                        action="<?php echo base_url()?>/organization/edit"
                                        enctype="multipart/form-data">
                                        <div class="row my-2 g-3">
                                            <div class="col-md-6 col-sm-12 my-1 bg-light">
                                                <div class="mb-3">
                                                    <label for="org_name" class="form-label">Название
                                                        организации</label>
                                                    <input class="form-control" id="org_name" name="org_name"
                                                        type="text" aria-label="" value="<?= htmlspecialchars(session('org_name'))?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-12 my-1 bg-light">
                                                <div class="mb-3">
                                                    <label for="post" class="form-label">Ваша должность</label>
                                                    <input class="form-control" id="post" name="post" type="text"
                                                        aria-label="" value="<?= session('post')?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row my-2 g-3">
                                            <div class="col-md-6 col-sm-12 my-1 bg-light">
                                                <div class="mb-3">
                                                    <label for="org_adress" class="form-label">Адрес организации</label>
                                                    <input class="form-control" id="org_adress" name="org_adress"
                                                        type="text" aria-label="" value="<?= session('org_adress')?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-12 my-1 bg-light">
                                                <div class="mb-3">
                                                    <label for="formFile" class="form-label">Новое фото
                                                        организации</label>
                                                    <input class="form-control" type="file" id="org_photo"
                                                        name="org_photo">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row my-2 g-3">
                                            <div class="col-md-6 col-sm-12 my-1 bg-light">
                                                <div class="mb-3">
                                                    <label for="formFile" class="form-label">Описание
                                                        организации</label>
                                                    <textarea class="form-control" id="org_description"
                                                        name="org_description" rows="3"><?= session('org_description')?></textarea>
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
                                        <?php if(session()->getFlashdata('error_edit_org')):?>
                                        <div class="alert alert-danger" role="alert">
                                            <?= session()->getFlashdata('error_edit_org') ?>
                                        </div>
                                        <?php endif;?>
                                        <div class="row my-2">
                                            <div class="col-md-4 col-sm-12 col-xl-3">
                                                <input type="submit" class="btn btn-outline-danger"
                                                    value="Сохранить"></input>
                                                <a class="btn btn-outline-dark"
                                                    href="<?php echo site_url('profile')?>">Отмена</a>
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
    <script src="<?php echo base_url()?>/assets/js/ahunter_suggest.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/additional-methods.min.js"></script>
    <script src="<?php echo base_url()?>/assets/js/org_data.js"></script>
</body>
</html>