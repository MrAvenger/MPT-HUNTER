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
            <?php if(!session('specialization_id')): echo session('specialization_id');?>
            <div class="alert alert-danger" role="alert">
                Вы не привязаны ни к какой специальности! Свяжитесь с администратором!
            </div>
            <?php else:?>
            <div class="row mb-2">
                <div class="col-md-12 col-lg-4">
                    <div class="mb-3">
                        <label for="" class="form-label text-light">Поиск</label>
                        <input class="form-control" id="search" type="text"
                            placeholder="Например:Московский приборостроительный техникум" aria-label="">
                    </div>
                </div>
            </div>
            <div id="list">

            </div>
            <?php endif;?>
        </div>
    </main>
    <!--content end-->
    <!-- JavaScript -->
    <script src="<?php echo base_url()?>/assets/js/jquery-3.6.0.min.js"></script>
    <?php if(session('specialization_id')):?>
    <script type="text/javascript">
    const page = 1;
    </script>
    <script src="<?php echo base_url()?>/assets/js/orgs_load.js"></script>
    <script src="<?php echo base_url()?>/assets/js/jquery.toast.js"></script>
    <?php endif;?>
</body>

</html>