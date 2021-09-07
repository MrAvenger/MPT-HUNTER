<!doctype html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo base_url()?>/assets/css/jquery.toast.css">
</head>

<body class="bg-dark">
    <!--content start-->
    <main class="my-5 pt-5">
        <div class="container">
            <?php if(session('role')=='Студент'&&!session('in_org')):?>
            <div class="row mb-2">
                <div class="col-md-12 col-lg-4">
                    <div class="mb-3">
                        <label for="" class="form-label text-light">Поиск</label>
                        <input class="form-control" id="search" type="text" placeholder="Например: Артсек"
                            aria-label="">
                    </div>
                </div>
            </div>
            <?php endif;?>
            <?php if((session('role')=='Студент'&&!session('in_org'))||session('role')=='Работодатель'):?>
            <div id="list">

            </div>
            <?php elseif(session('role')=='Студент'&&session('in_org')):?>

            <div class="alert alert-warning" role="alert">
                <h4 class="alert-heading">Вы уже привязаны к организации!</h4>
                <p>В связи с тем, что вы уже привязаны к организации, Вам не будут приходить приглашения на собеседование от других организаций.</p>
                <hr>
                <p class="mb-0"><a href="<?echo site_url('my_org');?>">Перейти к организации</a></p>
            </div>
            <?php endif;?>
        </div>
    </main>
    <!--content end-->
    <!-- JavaScript -->
    <script src="<?php echo base_url()?>/assets/js/jquery-3.6.0.min.js"></script>
    <?php if(session('role')=='Студент'&&!session('in_org')):?>
    <script src="<?php echo base_url()?>/assets/js/student_invitions.js"></script>
    <?php endif;?>
    <?php if(session('role')=='Работодатель'):?>
    <script type="text/javascript">
    const page = 2;
    var org_id = '<?php echo session('org_id');?>';
    var role = '<?php echo session('role');?>';
    </script>
    <script src="<?php echo base_url()?>/assets/js/resume_emploers_actions.js"></script>
    <script src="<?php echo base_url()?>/assets/js/script_load_resume_portfolio_data.js"></script>
    <?php endif;?>
    <script src="<?php echo base_url()?>/assets/js/base_crud_ajax.js"></script>
    <script src="<?php echo base_url()?>/assets/js/jquery.toast.js"></script>
</body>

</html>