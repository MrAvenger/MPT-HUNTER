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
            <?php if(session('role')=='Студент'):?>
            <div class="row mb-2">
                <div class="col-md-12 col-lg-4">
                    <div class="mb-3">
                        <label for="" class="form-label text-light">Поиск</label>
                        <input class="form-control" id="search" type="text" placeholder="Например: Программист 1С"
                            aria-label="">
                    </div>
                </div>
            </div>
            <?php endif;?>
            <div id="list">

            </div>
        </div>
    </main>
    <!--content end-->
    <!-- JavaScript -->
    <script src="<?php echo base_url()?>/assets/js/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
      const page=2;
    </script>
    <?php if(session('role')=='Студент'):?>
    <script src="<?php echo base_url()?>/assets/js/offers_student_actions.js"></script>
    <script src="<?php echo base_url()?>/assets/js/about_offer.js"></script>
    <script src="<?php echo base_url()?>/assets/js/base_crud_ajax.js"></script>
    <?php endif;?>
    <?php if(session('role')=='Работодатель'):?>
    <script type="text/javascript">
    var org_id = '<?php echo session('org_id');?>';
    var role = '<?php echo session('role');?>';
    </script>
    <script src="<?php echo base_url()?>/assets/js/offers_responds_studs_load.js"></script>
    <script src="<?php echo base_url()?>/assets/js/script_load_resume_portfolio_data.js"></script>
    <script src="<?php echo base_url()?>/assets/js/base_crud_ajax.js"></script>
    <?php endif;?>
    <script src="<?php echo base_url()?>/assets/js/jquery.toast.js"></script>
</body>
</html>