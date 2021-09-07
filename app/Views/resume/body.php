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
        <?php if(session('specialization_id')):?>
            <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 col-xl-11 col-xxl-10">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Резюме:</h5>
                            <form id="resume_form" name="resume_form">
                                <div class="container">
                                    <div class="row my-2">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="exampleFormControlTextarea1" class="form-label">О
                                                    себе</label>
                                                <textarea id="about_me" name="about_me" class="form-control"
                                                    id="exampleFormControlTextarea1" rows="5"><?php if(isset($resume['about_me'])):echo htmlspecialchars($resume['about_me']); else: echo '';endif;?></textarea>
                                                <div id="about_me_HelpBlock" class="form-text">
                                                    Напишите что-то о себе для работодателя.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row my-2">
                                        <div class="col-md-6">
                                            <label for="skills" class="form-label">Навыки</label>
                                            <select id="skills" name="skills[]" size="3" class="js-example-basic-multiple form-select"
                                                aria-label="Default select example" multiple="multiple">
                                            </select>
                                            <div id="skills_HelpBlock" class="form-text">
                                                Укажите только те навыки/умения, которыми обладаете.
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="work_experience" class="form-label">Опыт работы</label>
                                            <input type="text" class="form-control" value="<?php if(isset($resume['work_experience'])):echo htmlspecialchars($resume['work_experience']); else: echo '';endif;?>" name="work_experience">
                                            <div id="work_experience_nameBlock" class="form-text">
                                                Если у вас есть опыт работы, то укажите где (в какой организации
                                                работали).
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row my-2">
                                        <div class="col-md-6">
                                            <label for="education" class="form-label">Образование</label>
                                            <select id="education" name="education" class="form-select"
                                                aria-label="Default select example">
                                                <option value="Среднее">Среднее</option>
                                                <option value="Среднее специальное">Среднее специальное</option>
                                            </select>
                                            <div id="education_HelpBlock" class="form-text">
                                                Укажите образование (если закончии 9 классов - Среднее, если 11 -
                                                Среднее
                                                специальное).
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="nearest_metro" class="form-label">Ближайшее метро</label>
                                            <div class="input-group">
                                                <span class="input-group-text" id="basic-addon1">М.</span>
                                                <input type="text" value="<?php if(isset($resume['nearest_metro'])):echo htmlspecialchars($resume['nearest_metro']); else: echo '';endif;?>" class="form-control" name="nearest_metro">
                                            </div>
                                            <div id="nearest_metro_HelpBlock" class="form-text">
                                                Укажите ближайшее метро.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row my-2">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="exampleFormControlTextarea1"
                                                    class="form-label">Дополнительно</label>
                                                <textarea id="additionally" name="additionally" class="form-control"
                                                    id="exampleFormControlTextarea1" rows="5"><?php if(isset($resume['additionally'])):echo htmlspecialchars($resume['additionally']); else: echo '';endif;?></textarea>
                                                <div id="additionally_HelpBlock" class="form-text">
                                                    Здесь вы можете написать о том, в каких программах вы умеете
                                                    работать
                                                    (например Photoshop 2019) и тп.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div id="errors_resume" class="col-md-12 alert-danger">

                                        </div>
                                    </div>
                                    <div class="row my-2">
                                        <div class="col-md-6">
                                            <button type="button" id="btn_save_resume" class="btn btn-outline-danger">Сохранить</button>
                                            <a class="btn btn-outline-info" href="<?php echo site_url('portfolio');?>">Перейти к
                                                портфолио</a>
                                            <button id="btn_delete_resume" name="btn_delete_resume" type="button" class="btn btn-outline-dark">Удалить</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php else:?>
            <h5 class="text-light text-center">Укажите свою специальность в профиле! <a href="<?php echo site_url('profile/edit')?>">Указать</a></h5>
        <?php endif;?>
    </main>
    <!--content end-->
    <!-- JavaScript -->
    <script src="<?php echo base_url()?>/assets/js/jquery-3.6.0.min.js"></script>
    <script src="<?php echo base_url()?>/assets/js/jquery.toast.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/additional-methods.min.js"></script>
    <script src="<?php echo base_url()?>/assets/js/select2.min.js"></script>
    <script src="<?php echo base_url()?>/assets/js/select2.ru.js"></script>
    <script type="text/javascript">
        var skills=<?php if(isset($skills)):echo json_encode($skills); else: echo 'null'; endif; ?>;
        var resume_id=<?php if(session('resume_id')):echo session('resume_id'); else: echo 'null'; endif; ?>;
    </script>
    <script src="<?php echo base_url()?>/assets/js/resume.js"></script>
    <script src="<?php echo base_url()?>/assets/js/base_crud_ajax.js"></script>
</body>

</html>