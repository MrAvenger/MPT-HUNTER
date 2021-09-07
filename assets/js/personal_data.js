$( document ).ready(function() {
    $('#datepicker').datepicker({
        locale: 'ru-ru',
        format: 'dd.mm.yyyy',
        weekStartDay: 1,
        maxDate: function() {
            var date = new Date();
            date.setFullYear(date.getFullYear() - 17);
            return new Date(date.getFullYear(), date.getMonth(), date.getDate());
        }
    });
    $("#number_phone").mask("+7 (999) 999-9999");
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
            if(result!="mpt"){
                return false;
            }
            else{
                return true;
            }
        },
        "Почта не привязана к домену мпт!"
    );
    if ($("#pers_data").length > 0) {
        $("#pers_data").validate({
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
                password: {
                    required: false,
                    minlength: 8,
                    maxlength: 20,
                    regex_password: "(?=^.{8,}$)(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z])(?=.*[^A-Za-z0-9]).*",
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
            },
        })
    }
});
