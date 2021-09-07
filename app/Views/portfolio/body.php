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
                            <h5 class="card-title">Портфолио:</h5>
                            <div class="container">
                                <div id="old" class="row my-2">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <form id="portfolio_form" enctype="multipart/form-data" name="portfolio_form">
                                <div class="container">
                                    <button id="btn_add_fields" type="button"
                                        class="btn btn-outline-danger">Добавить</button>
                                    <div id="new" class="row my-2">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div id="errors_portfolio" class="col-md-12 alert-danger">

                                        </div>
                                    </div>
                                    <div class="row my-2">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <button id="btn_add_data" class="btn btn-outline-danger">Загрузить</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php elseif(!session('specialization_id')):?>
            <div class="alert alert-danger" role="alert">
                У вас не выбрана специальность!
            </div>
        <?php endif;?>
    </main>
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
  <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
  </symbol>
  <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
  </symbol>
  <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
  </symbol>
</svg>
    <!--content end-->
    <!-- JavaScript -->
    <script src="<?php echo base_url()?>/assets/js/jquery-3.6.0.min.js"></script>
    <script src="<?php echo base_url()?>/assets/js/jquery.toast.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/additional-methods.min.js"></script>
    <script src="<?php echo base_url()?>/assets/js/portfolio.js"></script>
    <script src="<?php echo base_url()?>/assets/js/base_crud_ajax.js"></script>
</body>

</html>