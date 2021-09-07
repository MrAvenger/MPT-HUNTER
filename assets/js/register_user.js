$( document ).ready(function() {
    $.validator.addMethod(
        "regex_password",
        function(value, element, regexp) {
            var re = new RegExp(regexp);
            return this.optional(element) || re.test(value);
        },
        "Пароль должен содержать латинские символы верхнего и нижнего регистра, цифры и специальные знаки!"
    );
   $.validator.addMethod(
        "regex_email_domain",
        function(value, element, regexp) {
            var re = new RegExp(regexp);
            var result = value.match(re)
            if(result!="mpt.ru"){
                console.log(result);
                return false;
            }
            else{
                return true;
            }
        },
        "Почта не привязана к домену мпт!"
    );
    if ($("#user_registration_form").length > 0) {
        $("#user_registration_form").validate({
            rules: {
                first_name: {
                    required: true,
                    minlength: 1,
                    maxlength: 50,
                },
                last_name: {
                    required: true,
                    minlength: 1,
                    maxlength: 50,
                },
                middle_name: {
                    maxlength: 50,
                },
                email: {
                    required: true,
                    maxlength: 200,
                    email: true,
                    regex_email_domain: "(?<=@)[^.]+(?=\.).*",
                },
                password: {
                    required: true,
                    minlength: 8,
                    maxlength: 20,
                    regex_password: "(?=^.{8,}$)(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z])(?=.*[^A-Za-z0-9]).*",
                },
                specialization: {
                    required: true,
                },
                groups: {
                    required: true,
                },
            },
            messages: {
                first_name: {
                    required: "Имя - обязательное поле.",
                    minlength: "Имя должно содержать не менее 1 символов!",
                    maxlength: "Имя должно содержать не более 50 символов!",
                },
                last_name: {
                    required: "Фамилия - обязательное поле.",
                    minlength: "Фамилия должна содержать не менее 1 символов!",
                    maxlength: "Фамилия должна содержать не более 50 символов!",
                },
                middle_name: {
                    maxlength: "Отчество должно содержать не более 50 символов!",
                },
                email: {
                    required: "Email - обязательное поле!",
                    email: "Введён не валидный адрес эл.почты!",
                    maxlength: "Email адрес должен быть не более 200 символов!",
                },
                password:{
                    required: "Пароль - обязательное поле.",
                    minlength: "Пароль должен содержать не менее 8 символов!",
                    maxlength: "Пароль должен содержать не более 20 символов!",
                },
                specialization:{
                    required: "Специальность - обязательное поле.",
                },
                groups:{
                    required: "Группа - обязательное поле.",
                },
            },
        })
    }
});
