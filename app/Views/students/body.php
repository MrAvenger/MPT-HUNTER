<!doctype html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo base_url()?>/assets/css/jquery.toast.css">
</head>

<body id="body" class="bg-dark">
    <!--content start-->
    <main class="my-5 pt-5">
        <div class="container">
            <?php if(empty(session('specialization_id'))&&session('role')!="Работодатель"):?>
            <div class="alert alert-danger" role="alert">
                Вы не привязаны ни к какой специальности! Свяжитесь с администратором!
            </div>
            <?php endif;?>
            <?php if(session('specialization_id')&&session('role')!="Работодатель"):?>
            <button id="transfer_open" type="button" class="btn btn-outline-danger my-2" data-bs-toggle="modal"
                data-bs-target="#Modal_Send_Studs">Перевод студентов</button>
            <button id="btn_add_open" type="button" class="btn btn-outline-danger my-2" data-bs-toggle="modal"
                data-bs-target="#Cru_Group">Добавить новые группы</button>
            <button id="btn_edit_open" type="button" class="btn btn-outline-danger my-2">Изменить группы</button>
            <button id="btn_delete_open" type="button" class="btn btn-outline-light my-2" data-bs-toggle="modal"
                data-bs-target="#Model_Students_Delete">Отчислить студентов</button>
            <?php endif;?>
            <?php if(session('role')=="Работодатель"||session('role')=="Куратор"):?>
            <div id="data_list">

            </div>
            <?php endif;?>
        </div>
    </main>
    <!--content end-->
    <!-- JavaScript -->
    <script src="<?php echo base_url()?>/assets/js/jquery-3.6.0.min.js"></script>
    <script src="https://kit.fontawesome.com/437be00af9.js" crossorigin="anonymous"></script>
    <?php if(session('specialization_id')&&session('role')=='Куратор'):?>
    <script type="text/javascript">
    var select_group = null;
    var select_spec = <?php echo session('specialization_id');?>;
    var role = '<?php echo session('role');?>';
    const type_select = 2;
    </script>
    <script src="<?php echo base_url()?>/assets/js/select2.min.js"></script>
    <script src="<?php echo base_url()?>/assets/js/select2.ru.js"></script>
    <script src="<?php echo base_url()?>/assets/js/script_select_spec_group.js"></script>
    <script src="<?php echo base_url()?>/assets/js/curator_groups.js"></script>
    <script src="<?php echo base_url()?>/assets/js/base_crud_ajax.js"></script>
    <script src="<?php echo base_url()?>/assets/js/jquery.toast.js"></script>
    <?php endif;?>
    <?php if(session('role')=='Работодатель'):?>
    <script type="text/javascript">
    var org_id = '<?php echo session('org_id');?>';
    var role = '<?php echo session('role');?>';
    </script>
    <?php endif;?>
    <?php if(session('specialization_id')||session('role')=="Работодатель"):?>
    <script src="<?php echo base_url()?>/assets/js/students.js"></script>
    <script src="<?php echo base_url()?>/assets/js/script_load_resume_portfolio_data.js"></script>
    <script src="<?php echo base_url()?>/assets/js/base_crud_ajax.js"></script>
    <script src="<?php echo base_url()?>/assets/js/jquery.toast.js"></script>
    <?php endif;?>
</body>

</html>