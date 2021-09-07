<!doctype html>
<html lang="en">

<head class="h-100">
    <!-- Required meta tags -->
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="<?php echo base_url().'/assets/css/bootstrap.min.css'?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url()?>/assets/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.1.1/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo base_url()?>/assets/css/jquery.toast.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link rel="stylesheet" href="<?php echo base_url()?>/assets/css/datatable/jquery.dataTables.min.css">
    <title><?php if (isset($title)): echo $title; else: echo 'Панель администратора'; endif;?></title>
    <style>
    html {
        position: relative;
        min-height: 100%;
    }

    footer {
        position: absolute;
        bottom: 0;
        width: 100%;
        /* Set the fixed height of the footer here */
        height: 60px;
        background-color: #f5f5f5;
    }

    .dropdown-menu .dropdown-item:hover {
        color: none;
        background: none;
    }

    .simple-hover a {
        display: inline-block;
        /*делаем чтобы наша ссылка из строчного элемента превратилась в строчно-блочный*/
        line-height: 1.5;
        /*Задаём высоту строки (можно в пикселях)*/
        color: #2F73B6;
        /*Задаём цвет ссылки*/

    }

    .simple-hover a:after {
        display: block;
        /*превращаем его в блочный элемент*/
        content: "";
        /*контента в данном блоке не будет поэтому в кавычках ничего не ставим*/
        height: 3px;
        /*задаём высоту линии*/
        width: 0%;
        /*задаём начальную ширину элемента (линии)*/
        background-color: white;
        /*цвет фона элемента*/
        transition: width 0.4s ease-in-out;
        /*данное свойство отвечает за плавное изменение ширины. Здесь можно задать время анимации в секундах (в данном случае задано 0.4 секунды)*/
    }

    .simple-hover a:hover:after,
    .simple-hover a:focus:after {
        width: 100%;
    }

    /* @media screen and (max-width: 600px) {
        table {
            border: 0;
        }

        table thead {
            display: none;
        }

        table tr {
            margin-bottom: 10px;
            display: block;
            border-bottom: 2px solid #ddd;
        }

        table td {
            display: block;
            text-align: right;
            font-size: 13px;
            border-bottom: 1px dotted #ccc;
            border-right: 1px solid transparent;
        }

        table td:last-child {
            border-bottom: 0;
        }

        table td:before {
            content: attr(data-label);
            float: left;
            text-transform: uppercase;
            font-weight: bold;
        }
    } */
    </style>
</head>

<body>
    <!--content start-->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?php echo site_url('admin')?>">Панель администратора</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item simple-hover">
                            <a class="nav-link" href="<?php echo site_url('admin/users');?>">Пользователи</a>
                        </li>
                        <li class="nav-item simple-hover">
                            <a class="nav-link" href="<?echo site_url('admin/specs_groups');?>">Специальности и группы</a>
                        </li>
                        <li class="nav-item simple-hover">
                            <a class="nav-link" href="#">Резюме студентов</a>
                        </li>
                        <li class="nav-item simple-hover">
                            <a class="nav-link" href="#">Организации</a>
                        </li>
                        <li class="nav-item simple-hover">
                            <a class="nav-link" href="#">Студенты и места практик</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdownMenuLink"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Здравствуй, <?php echo session('first_name');?>
                            </a>
                            <ul class="dropdown-menu bg-danger simple-hover" aria-labelledby="navbarDropdownMenuLink">
                                <li><a class="dropdown-item text-light" href="#">Действие (нет)</a></li>
                                <li><a class="dropdown-item text-light" href="#">Ещё действие (нет)</a></li>
                                <li><a class="dropdown-item text-light" href="#">Выход</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

</body>

</html>