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
            <?php if(!session('specialization_id')):?>
            <div class="alert alert-danger" role="alert">
                Вы не привязаны ни к какой специальности! Свяжитесь с администратором!
            </div>
            <?php elseif(session('specialization_id')):?>
            <div class="row justify-content-center">
                <div class="col-md-12 col-xl-11 col-xxl-10">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Данные о специальности:</h5>
                            <form id="form_spec" name="form_spec">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="specialization_name" class="form-label">Наименование
                                                    специальности</label>
                                                <input type="text" class="form-control" id="specialization_name"
                                                    name="specialization_name"
                                                    value="<?php if($specialization):echo $specialization['name'];else: echo ''; endif;?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="groups" class="form-label">Группы</label>
                                                <select id="groups" name="groups[]" class="form-select"
                                                    multiple="multiple">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="exampleFormControlTextarea1" class="form-label">Описание
                                                    (что изучалось студентами по специальности)</label>
                                                <textarea id="description" name="description" class="form-control"
                                                    id="exampleFormControlTextarea1"
                                                    rows="5"><?php if(isset($resume['additionally'])):echo htmlspecialchars($resume['additionally']); else: echo '';endif;?></textarea>
                                                <!-- <div id="additionally_HelpBlock" class="form-text">
                                        Здесь вы можете написать о том, в каких программах вы умеете
                                        работать
                                        (например Photoshop 2019) и тп.
                                    </div> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-7">
                                            <div class="mb-3">
                                                <button id="btn_main_skill" type="button"
                                                    class="btn btn-danger">Добавить
                                                    навыки/умения</button>
                                            </div>
                                            <div id="skills_row" class="row"></div>
                                        </div>
                                        <div class="row mt-3">
                                            <div id="errors" class="col-md-12 alert-danger">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end"><button id="btn_save" type="button"
                                            class="btn btn-outline-danger">Сохранить</button></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif;?>
        </div>
        
    </main>
    <!--content end-->
    <!-- JavaScript -->
    <script src="<?php echo base_url()?>/assets/js/jquery-3.6.0.min.js"></script>
    <script src="<?php echo base_url()?>/assets/js/jquery.toast.js"></script>
    <?php if(session('specialization_id')):?>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/additional-methods.min.js"></script>
    <script src="<?php echo base_url()?>/assets/js/select2.min.js"></script>
    <script src="<?php echo base_url()?>/assets/js/select2.ru.js"></script>
    <script type="text/javascript">
    var specialization_id =
        <?php if(session('specialization_id')):echo session('specialization_id'); else: echo 'null'; endif; ?>;
    var skills = <?php if($skills):echo json_encode($skills); else:echo 'null';endif;?>;
    var groups = <?php if($groups):echo json_encode($groups); else:echo 'null';endif;?>;
    var specialization = <?php if($specialization):echo json_encode($specialization); else:echo '';endif;?>;
    const type_select = 1;
    </script>
    <script src="<?php echo base_url()?>/assets/js/base_crud_ajax.js"></script>
    <script src="<?php echo base_url()?>/assets/js/specialization.js"></script>
    <?php endif;?>
</body>

</html>