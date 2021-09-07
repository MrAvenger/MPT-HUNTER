<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php echo base_url()?>/assets/css/select2.min.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.1.1/dist/select2-bootstrap-5-theme.min.css" />
    <!-- Bootstrap CSS -->
    <link href="<?php echo base_url().'/assets/css/bootstrap.min.css'?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo base_url()?>/assets/css/jquery.toast.css">
    <link rel="stylesheet" href="<?php echo base_url()?>/assets/css/datatable/jquery.dataTables.min.css">
    <title><?php if (isset($title)): echo $title; else: echo 'МПТ HUNTER'; endif;?></title>
    <style>
    .round {
        border-radius: 100px;
        /* Радиус скругления */
        border: 3px solid red;
        /* Параметры рамки */
        box-shadow: 0 0 7px #666;

        /* Параметры тени */
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

    footer {
        position: absolute;
        bottom: 0;
        width: 100%;
        /* Set the fixed height of the footer here */
        height: 50px;
        background-color: #f5f5f5;
    }

    html {
        position: relative;
        min-height: 100%;
    }

    input.error {
        border: 1px solid #ff0000;
    }

    label.error {
        color: #ff0000;
        font-weight: normal;
    }

    pre {
        width: 100%;
        white-space: pre-wrap;
        padding: 0;
        margin: 0;
        overflow: auto;
        overflow-y: hidden;
        font-size: 14px;
        line-height: 20px;
        /*background: #efefef;*/
        border: none;
        /* background: url(lines.png) repeat 0 0; */
    }

    pre code {
        padding: 10px;
        color: #333;
    }

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

    @media only screen and (max-width: 480px) {
        #footer {
            display: none;
        }
    }
    </style>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light  fixed-top bg-danger">
            <div class="container-fluid">
                <a class="navbar-brand text-light" href="<?php echo site_url('main'); ?>">MPT HUNTER</a>
                <button class="navbar-toggler text-light" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse menu" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <?php switch(session('role')):
                            case 'Студент':?>
                        <li class="nav-item simple-hover mx-3">
                            <a class="nav-link text-light" aria-current="page"
                                href="<?php echo site_url('main'); ?>">Главная</a>
                        </li>
                        <li class="nav-item simple-hover mx-3">
                            <a class="nav-link text-light" href="<?php echo site_url('favorite')?>">Избранное</a>
                        </li>
                        <li class="nav-item simple-hover mx-3">
                            <a class="nav-link text-light" href="<?php echo site_url('responds')?>">Отклики</a>
                        </li>
                        <li class="nav-item simple-hover mx-3">
                            <a class="nav-link text-light" href="<?php echo site_url('invitations');?>">Приглашения</a>
                        </li>
                        <li class="nav-item simple-hover mx-3">
                            <a class="nav-link text-light" href="<?php echo site_url('my_org');?>">Закреплённая
                                организация</a>
                        </li>
                        <li class="nav-item simple-hover mx-3">
                            <a class="nav-link text-light" href="<?php echo site_url('resume');?>">Моё резюме</a>
                        </li>
                        <li class="nav-item dropdown mx-3">
                            <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                    class="bi bi-person-circle me-2" viewBox="0 0 16 16">
                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                    <path fill-rule="evenodd"
                                        d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                                </svg><?php echo session('first_name').' '.session('last_name') ?>
                            </a>
                            <ul class="dropdown-menu bg-danger" aria-labelledby="navbarDropdown">
                                <li class="simple-hover"><a class="dropdown-item text-light"
                                        href="<?php echo base_url();?>/profile">Мой
                                        профиль</a></li>
                                <li class="simple-hover"><a class="dropdown-item text-light"
                                        href="<?php echo site_url('portfolio');?>">Моё портфолио</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li class="simple-hover"><a class="dropdown-item text-light"
                                        href="<?php echo base_url()?>/logout">Выход</a>
                                </li>
                            </ul>
                        </li>
                        <?php break;
                        case 'Работодатель':?>
                        <li class="nav-item simple-hover mx-3">
                            <a class="nav-link text-light" aria-current="page"
                                href="<?php echo site_url('main'); ?>">Главная</a>
                        </li>
                        <li class="nav-item simple-hover mx-3">
                            <a class="nav-link text-light" href="<?php echo site_url('favorite')?>">Избранное</a>
                        </li>
                        <li class="nav-item simple-hover mx-3">
                            <a class="nav-link text-light" href="<?php echo site_url('responds')?>">Отклики</a>
                        </li>
                        <li class="nav-item simple-hover mx-3">
                            <a class="nav-link text-light" href="<?php echo site_url('students');?>">Соискатели</a>
                        </li>
                        <li class="nav-item simple-hover mx-3">
                            <a class="nav-link text-light" href="<?php echo site_url('invitations');?>">Приглашения и
                                прикреплённые студенты</a>
                        </li>
                        <li class="nav-item dropdown mx-3">
                            <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                    class="bi bi-person-circle me-2" viewBox="0 0 16 16">
                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                    <path fill-rule="evenodd"
                                        d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                                </svg><?php echo session('first_name').' '.session('last_name') ?>
                            </a>
                            <ul class="dropdown-menu bg-danger" aria-labelledby="navbarDropdown">
                                <li class="simple-hover"><a class="dropdown-item text-light"
                                        href="<?php echo base_url();?>/profile">Мой
                                        профиль</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li class="simple-hover"><a class="dropdown-item text-light"
                                        href="<?php echo base_url()?>/logout">Выход</a>
                                </li>
                            </ul>
                        </li>
                        <?php break;
                        case 'Куратор':?>
                        <li class="nav-item simple-hover mx-3">
                            <a class="nav-link text-light" href="<?php echo site_url('students');?>">Мои студенты</a>
                        </li>
                        <li class="nav-item simple-hover mx-3">
                            <a class="nav-link text-light" href="<?php echo site_url('specialization');?>">Курируемая
                                специальность</a>
                        </li>
                        <li class="nav-item simple-hover mx-3">
                            <a class="nav-link text-light"
                                href="<?php echo site_url('organizations');?>">Организации</a>
                        </li>
                        <li class="nav-item dropdown mx-3">
                            <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                    class="bi bi-person-circle me-2" viewBox="0 0 16 16">
                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                    <path fill-rule="evenodd"
                                        d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                                </svg><?php echo session('first_name').' '.session('last_name') ?>
                            </a>
                            <ul class="dropdown-menu bg-danger" aria-labelledby="navbarDropdown">
                                <li class="simple-hover"><a class="dropdown-item text-light"
                                        href="<?php echo base_url();?>/profile">Мой
                                        профиль</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li class="simple-hover"><a class="dropdown-item text-light"
                                        href="<?php echo base_url()?>/logout">Выход</a>
                                </li>
                            </ul>
                        </li>
                        <?php break;
                        case 'Администратор':?>
                        <li class="nav-item simple-hover mx-3">
                            <a class="nav-link text-light" href="<?php echo site_url('admin/users');?>">Пользователи</a>
                        </li>
                        <li class="nav-item simple-hover mx-3">
                            <a class="nav-link text-light"
                                href="<? echo site_url('admin/specs_groups');?>">Специальности и группы</a>
                        </li>
                        <!-- <li class="nav-item simple-hover mx-3">
                            <a class="nav-link text-light" href="<? echo site_url('admin/resume_portfolio');?>">Резюме и
                                портфолио студентов</a>
                        </li> -->
                        <li class="nav-item simple-hover mx-3">
                            <a class="nav-link text-light"
                                href="<?php echo site_url('admin/organizations')?>">Организации</a>
                        </li>
                        <!-- <li class="nav-item simple-hover mx-3">
                            <a class="nav-link text-light" href="#">Студенты и места практик</a>
                        </li> -->
                        <li class="nav-item dropdown mx-3">
                            <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                    class="bi bi-person-circle me-2" viewBox="0 0 16 16">
                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                    <path fill-rule="evenodd"
                                        d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                                </svg><?php echo session('first_name').' '.session('last_name') ?>
                            </a>
                            <ul class="dropdown-menu bg-danger" aria-labelledby="navbarDropdown">
                                <li class="simple-hover"><a class="dropdown-item text-light"
                                        href="<?php echo base_url();?>/profile">Мой
                                        профиль</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li class="simple-hover"><a class="dropdown-item text-light"
                                        href="<?php echo base_url()?>/logout">Выход</a>
                                </li>
                            </ul>
                        </li>
                        <?php break; endswitch;?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
</body>

</html>