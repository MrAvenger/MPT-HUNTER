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
            <?php if(session('role')=='Студент'&&session('in_org')):?>
            <div class="row justify-content-center">
                <div class="col-md-12 col-xl-11 col-xxl-10">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Данные по организации</h5>
                            <div class="container">
                                <div class="row my-2">
                                    <div class="col-md-6 col-sm-12 my-1 bg-light">
                                        <label>Наименование организации:
                                            <?php if($org_name): echo $org_name; else: echo 'Не указано'; endif;?></label>
                                    </div>                                    
                                    <div class="col-md-6 col-sm-12 my-1 bg-light">
                                        <label>Адрес:
                                            <?php if($org_adress): echo $org_adress; else: echo 'Не указано'; endif;?></label>
                                    </div>
                                    <div class="col-md-6 col-sm-12 my-1 bg-light">
                                        <label>Описание:
                                            <?php if($org_description) echo $org_description;else echo 'Не указано'?></label>
                                    </div>
                                    <div class="col-md-12 col-sm-12 my-1 bg-light">
                                        <label>Фото:
                                        <?php if($org_photo)  echo '<img src="'.$org_photo.'" class="img-fluid" style="max-width:350px;" alt="">' ;else echo '<img src="'.base_url().'/assets/img/design/org_photo.jpg" class="img-fluid" style="max-width:200px;" alt="">'?></label>
                                    </div>
                                    <div class="col-md-6 col-sm-12 my-1 bg-light">
                                        <label>ФИО работодателя:
                                        <?php echo $last_name.' '.$first_name.' '.$middle_name?></label>
                                    </div>
                                    <div class="col-md-6 col-sm-12 my-1 bg-light">
                                        <label>Должность:
                                            <?php if($post): echo $post; else: echo 'Не указано'; endif;?></label>
                                    </div>
                                    <div class="col-md-6 col-sm-12 my-1 bg-light">
                                        <label>Телефон:
                                            <?php if($number_phone): echo $number_phone; else: echo 'Не указано'; endif;?></label>
                                    </div>
                                    <div class="col-md-6 col-sm-12 my-1 bg-light">
                                        <label>Email:
                                            <?php if($email): echo $email; else: echo 'Не указано'; endif;?></label>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <?php elseif(session('role')=='Студент'&&!session('in_org')):?>
            <div class="alert alert-info" role="alert">
                На текущий момент вы не привязаны к какой-либо организации!
            </div>
            <?php endif;?>
        </div>
        </div>
    </main>
    <!--content end-->
    <!-- JavaScript -->
    <script src="<?php echo base_url()?>/assets/js/jquery-3.6.0.min.js"></script>
</body>

</html>