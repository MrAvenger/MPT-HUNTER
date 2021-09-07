<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>

<body>
    <div class="card"
        style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);max-width: 300px;margin: auto;text-align: center;font-family: arial;">
        <img src="<?php echo base_url()?>/assets/img/design/logo.png" alt="MPT HUNTER" style="width:100%">
        <h1>Верификация</h1>
        <p>Здравствуйте, уважаемый (ая) <?php echo $last_name?> <?php echo $first_name?> <?php echo $middle_name?>. Вы
            получили данное письмо, поскольку были зарегистрированы в нашей системе. Пожалуйста,
            нажмите
            на кнопку ниже для верификации email!</p>
        <p><a href="<?php echo base_url()?>/email/verification/<?php echo $activate_code?>"
                style="border: none;outline: 0;padding: 12px;color: white;background-color: red;text-align: center;cursor: pointer;width: 100%;font-size: 18px;text-decoration: none;">Активировать
                аккаунт</a></p>
    </div>
</body>
</html>