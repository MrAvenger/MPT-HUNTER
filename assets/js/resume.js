$( document ).ready(function() {
    specialization=$('#skills').select2({
        placeholder: "Выберите навыки/умения",
        theme: 'bootstrap-5',
        width: '100%',
        "language": "ru"
    });
    if ($("#resume_form").length > 0) {
        $("#resume_form").validate({
            rules: {
                about_me: {
                    required: true,
                    minlength: 50,
                    maxlength: 500,
                },
                skills: {
                    required: true,
                },
                work_experience: {
                    maxlength: 255,
                },
                education: {
                    required: true,
                },
                nearest_metro: {
                    maxlength: 100,
                },
                additionally: {
                    maxlength: 500,
                },
            },
            messages: {
                about_me: {
                    required: "О себе - обязательное поле.",
                    minlength: "Поле 'О себе' должно содержать не менее 50 символов!",
                    maxlength: "Поле 'О себе' должно содержать не более 500 символов!",
                },
                skills: {
                    required: "Обязательно нужно указать хотя бы один навык!",
                },
                work_experience: {
                    maxlength: "Поле 'Опыт работы' должно содержать не более 255 символов!",
                },
                education: {
                    required: "Образование - обязательное поле!",
                },
                nearest_metro:{
                    maxlength: "Поле 'Ближайшее метро' должно содержать не более 100 символов!",
                },
                additionally:{
                    maxlength: "Поле 'Дополнительно' должно содержать не более 500 символов!",
                },
            },
        })
    }

    if(!resume_id){
        $("#btn_save_resume").click(function(){
            ajaxCRUD(1,'/ResumeCrud/add',null,'resume_form',null,'errors_resume','Успешное создание резюме!');
            setTimeout('window.location.reload()', 2000);
        });
        $("#btn_delete_resume").click(function(){
            $.toast({
                heading: 'Ошибка',
                text: 'Вы ещё не создали резюме!',
                hideAfter: 10000,
                position: 'top-right',
                stack: false,
                showHideTransition: 'slide',
                icon: 'error'
            }); 
        });
    }
    else{
        $("#btn_save_resume").click(function(){
            ajaxCRUD(1,'/ResumeCrud/edit',null,'resume_form',null,'errors_resume','Успешное изменение резюме!');
            setTimeout('window.location.reload()', 2000);
        });
        $("#btn_delete_resume").click(function(){
            ajaxCRUD(2,'/ResumeCrud/delete',null,null,resume_id,null,'Успешное удаление резюме!');
            setTimeout('window.location.reload()', 2000);
        });
    }
    load_skills();
});

function load_skills(){
    $.ajax({
        url: "/ResumeCrud/get_skill_list",
        type: "POST",
        dataType:"json",
        success: function(data){
            if(data.length>0){
                var options='';
                const list=[];
                $("#skills").html("");
                data.forEach(function(item, i, data) {
                    options=options+'<option value="'+item.id+'">'+item.name+'</option>';
                });
                $("#skills").append(options);
                if(skills){
                    console.log('скилы есть');
                    skills.forEach(function(item, i, skills) {                  
                        list.push(item.skill_id);
                    });
                    $("#skills").val(list).trigger('change');
                }
            }
            else{
                var options='';
                $("#skills").html("<option value=''>Нет данных</option>");
            }
        },
        error: function(response){
            console.log('Ошибка при выполнении запроса');
        }
    });
   
}