$( document ).ready(function() {
        //готовим настройки модуля
        var options = {
            id: 'org_adress'
        };
    
        //запускаем модуль подсказок
        AhunterSuggest.Address.Solid(options);
    if ($("#org_data").length > 0) {
        $("#org_data").validate({
            rules: {
                org_name: {
                    required: true,
                    minlength: 5,
                    maxlength: 200,
                },
                org_adress: {
                    required: true,
                    minlength: 5,
                    maxlength: 255,
                },
                post: {
                    required: true,
                    minlength: 5,
                    maxlength: 100,
                },
                org_description: {
                    maxlength: 500,
                },
            },
            messages: {
                org_name: {
                    required: "Название организации - обязательное поле.",
                    minlength: "Название организации должно содержать не менее 5 символов!",
                    maxlength: "Название организации должно содержать не более 200 символов!",
                },
                org_adress: {
                    required: "Адрес организации - обязательное поле.",
                    minlength: "Адрес организации должен содержать не менее 5 символов!",
                    maxlength: "Адрес организации долженсодержать не более 255 символов!",
                },
                post: {
                    required: "Должность - обязательное поле.",
                    minlength: "Должность должна содержать не менее 5 символов!",
                    maxlength: "Должность должна содержать не более 100 символов!",
                },
                org_description: {
                    minlength: "Описание должно содержать не менее 5 символов!",
                    maxlength: "Описание должно содержать не более 500 символов!",
                },
            },
        })
    }
});
