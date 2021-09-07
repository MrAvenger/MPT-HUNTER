var main=[];
// var groups_list=[];
// var specializations_list=[];
// var users_list=[];
var dateTable_Main;
$( document ).ready(function() {
    $("#btn_main_skill").click(function(){
      $("#skills_row").append('<div class="col-md-7 col-sm-12 mt-1 input-group"><input class="form-control" type="text" name="skills[]"/><a class="btn btn-secondary">Удалить</a></div>');
      $("#skills_row").find("a").unbind("click");
      $("#skills_row").find("a").click(function(){
      $(this).parent().remove();      
      });      
    });
    $("#btn_main").click(function(){
        add_open_specialization_main();
    });
    $('#specialization').select2({
        placeholder: "Выберите специальность",
        allowClear: true,
        theme: 'bootstrap-5',
        width: '100%',
        dropdownParent: $('#CRU_Main'),
        "language": "ru"
    });

    $('#curator').select2({
        placeholder: "Нет данных о кураторе",
        theme: 'bootstrap-5',
        width: '100%',
        dropdownParent: $('#CRU_Main'),
        "language": "ru"
    });

    $('#groups').select2({
        placeholder: "Выберите группы",
        theme: 'bootstrap-5',
        width: '100%',
        dropdownParent: $('#CRU_Main'),
        "language": "ru"
    });     
    dateTable_Main=$('#custom_table').DataTable({
        "oLanguage": {
            "sUrl": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Russian.json"
        },
        "ajax": {
            url : "/GroupsSpecializationsCrud/get_all",
            type : 'POST'
        },
    });
    jQuery.fn.exists = function() {
        return $(this).length;
    };

    var spec = document.getElementById("specialization");
    spec.onchange = function () {
        if($("#specialization").val()!=null){
            load_users_list();
        }        
    }
    setInterval( function () {
        load_main();
    }, 1000 );

});

function load_main() {
    $.ajax({
        url: "/GroupsSpecializationsCrud/get_all",
        type: "POST",
        dataType:"json",
        success: function(data){
            if(JSON.stringify(main)!=JSON.stringify(data)){
                main=data;
                dateTable_Main.ajax.reload();
            }
        },
        error: function(response){
            console.log('Ошибка при выполнении запроса');
        }
    });
}

function add_open_specialization_main(){
    const elem = document.getElementById("errors_skills");
    $("#specialization").prop("disabled", false);
    $("#curator").prop("disabled", true);
    load_specializations();
    load_groups_list();
    //load_users_list();
    elem.classList.remove("alert");
    $("#description").val('');
    $("#errors_skills").html('');
    $("#skills_row").html('');
    $("#skills_row").append('<div class="col-md-7 col-sm-12 mt-1 input-group"><input class="form-control" type="text" name="skills[]"/></div>');
    $("#main_footer").html('<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button><button id="btn_main_save" type="button" class="btn btn-primary">Сохранить</button>');
    $("#btn_main_save").click(function(){
        ajaxCRUD(1,'/GroupsSpecializationsCrud/add','CRU_Main','main_form',null,'errors_skills','Успешное добавление!');
    });
    $("#CRU_Main").modal('show');
}

function edit_open_specialization_main(id){
    $("#curator").attr('disabled','disabled');
    $.ajax({
        url: "/GroupsSpecializationsCrud/get",
        type: "POST",
        dataType:"json",
        data:{id:id},
        success: function(data){
            if(data){
                const elem = document.getElementById("errors_skills");
                var main=data.main;
                var groups=data.groups;
                var skills=data.skills;
                load_specializations(main.id);
                load_groups_list(groups);
                load_users_list(main.user_id);
                console.log(main.description);
                $("#description").val(main.description);
                elem.classList.remove("alert");
                $("#errors_skills").html('');
                $("#skills_row").html('');
                $("#specialization").prop("disabled", true);
                skills.forEach(function(item, i, skills) {
                    $("#skills_row").append('<div class="col-md-7 col-sm-12 mt-1 input-group"><input class="form-control" type="text" name="skills[]" value="'+escapeHtml(item.name)+'"/><a class="btn btn-secondary">Удалить</a></div>');
                    $("#skills_row").find("a").unbind("click");
                    $("#skills_row").find("a").click(function(){
                    $(this).parent().remove();      
                    }); 
                });
                $("#main_footer").html('<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button><button id="btn_main_update" type="button" class="btn btn-primary">Изменить</button>');
                $("#btn_main_update").click(function(){
                    ajaxCRUD(1,'/GroupsSpecializationsCrud/edit/'+main.id,'CRU_Main','main_form',null,'errors_skills','Успешное изменение данных!');
                });
                $("#CRU_Main").modal('show');
            }
            else{
                console.log(0);
            }
        },
        error: function(response){
            console.log('Ошибка при выполнении запроса');
        }
    });
}

function delete_open_specialization_main(id){
    $("#delete_main_footer").html('<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button><button id="btn_main_delete" type="button" class="btn btn-primary">Удалить</button>');
    $("#btn_main_delete").click(function(){
        ajaxCRUD(2,'/GroupsSpecializationsCrud/delete','Delete_Main',null,id,null,'Успешное удаление данных!');
    });
    $("#Delete_Main").modal('show');
}

function load_specializations(id){
    $.ajax({
        url: "/GroupsSpecializationsCrud/get_specialization_list",
        type: "POST",
        dataType:"json",       
        success: function(data){
            $('#specialization').val(null).trigger('change');
            if(data.length>0){
                var options='';
                $("#specialization").html("");
                data.forEach(function(item, i, data) {
                    options=options+'<option value="'+item.id+'">'+item.name+'</option>';
                });
                $("#specialization").append(options);
                if(id){
                    $("#specialization").val(id);
                    $('#specialization').trigger('change'); // Notify any JS components that the value changed
                }
                load_users_list();
            }
            else{
                var options='';
                $("#specialization").html("<option value=''>Специальностей нет</option>");
            }
        },
        error: function(response){
            console.log('Ошибка при выполнении запроса');
        }
    });
}


function load_users_list(id) {
    $.ajax({
        url: "/GroupsSpecializationsCrud/get_users_list",
        type: "POST",
        dataType:"json",
        data:{curret:$("#specialization").val()},
        success: function(data){
            $('#curator').val(null).trigger('change');
            if(data.length>0){
                var options='';
                $("#curator").html("");
                data.forEach(function(item, i, data) {
                    options=options+'<option value="'+item.id+'">'+item.full_item+'</option>';
                });
                $("#curator").html(options);
                if(id){
                    $("#curator").val(id);
                }
            }
            else{
                //alert('пусто');
            }
        },
        error: function(response){
            console.log('Ошибка при выполнении запроса');
        }
    });
}

function load_groups_list(groups) {
    $.ajax({
        url: "/GroupsSpecializationsCrud/get_groups_list",
        type: "POST",
        dataType:"json",
        data:{type:type_select},
        success: function(data){
            const list=[];
            $('#groups').val(null).trigger('change');
            if(data.length>0){
                var options='';
                $("#groups").html("");
                data.forEach(function(item, i, data) {
                    options=options+'<option value="'+item.id+'">'+item.name+'</option>';
                });
                $("#groups").html(options);
                
                if(groups){
                    groups.forEach(function(item, i, groups) {                  
                        list.push(item.group_id);
                    });
                    $("#groups").val(list).trigger('change');
                }
            }
            else{
                //alert('пусто');
            }
        },
        error: function(response){
            console.log('Ошибка при выполнении запроса');
        }
    });
}
// function escapeHtml(text) {
//     var map = {
//       '&': '&amp;',
//       '<': '&lt;',
//       '>': '&gt;',
//       '"': '&quot;',
//       "'": '&#039;'
//     };
    
//     return text.replace(/[&<>"']/g, function(m) { return map[m]; });
//   }