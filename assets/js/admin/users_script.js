var select_group=null;
var select_spec=null;
type_select =2;
let user_mode=1;
var first_users=[];
var dateTable_Users;
let delete_id=null;
$(document).ready(function() {
    dateTable_Users=$('#users_table').DataTable({
        "oLanguage": {
            "sUrl": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Russian.json"
        },
        "ajax": {
            url : "/usersCrud/get_all",
            type : 'GET'
        },
    });
    $('#role').on('change', function() {
        switch_fields();
    })
    $("#number_phone").mask("+7 (999) 999-9999");
    user_data.onsubmit = async (e) => {        
        e.preventDefault();
        let response;
        switch(user_mode){
            case 1:{
                response = await fetch('/UsersCrud/add', {
                    method: 'POST',
                    body: new FormData(user_data)
                  }); 
            }break;
            case 2:{
                response = await fetch('/UsersCrud/edit', {
                    method: 'POST',
                    body: new FormData(user_data)
                  }); 
            }break;
        }
   
        let result = await response.json();
        if(result.validation){
            const elem = document.getElementById("errors");
            elem.classList.add("alert");
            $("#errors").html(result.validation);
        }
        else if(result.error){
            $.toast({
                heading: 'Ошибка!',
                text: result.error,
                hideAfter: 5000,
                position: 'top-right',
                stack: false,
                showHideTransition: 'slide',
                icon: 'error'
            });
            $("#Modal_User_CRU").modal('hide');
        }
        else if(result){
            const elem = document.getElementById("errors");
            elem.classList.remove("alert");
            $("#errors").html('');
            $.toast({
                heading: 'Успех!',
                text: 'Успешное выполнение операции!',
                hideAfter: 3000,
                position: 'top-right',
                stack: false,
                showHideTransition: 'slide',
                icon: 'success'
            });
            select_spec=null;
            select_group=null;
            $("#Modal_User_CRU").modal('hide');
        }
        else{
            console.log('Неопознаная ошибка!');
        }
    };

    $("#btn_mass_user").click(function (event) {
        var data = new FormData(user_mass_data);
        $("#mass_status").html('<div id="spinner" class="alert alert-primary d-flex align-items-center" role="alert"><svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg><div>Выполнение запроса...<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></div></div>');
        $("#btn_mass_user").prop("disabled", true);
 
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: "/UsersCrud/multiply_add",
            data: data,
            dataType:"json",
            processData: false,
            contentType: false,
            cache: false,
            timeout: 800000,
            success: function (data) {
                const elem = document.getElementById("errors_mass");
                $("#mass_status").html('');
                console.log(data);
                if(data.add_success){
                    $.toast({
                        heading: 'Успешное добавление данных!',
                        text: data.add_success,
                        hideAfter: 8000,
                        position: 'top-right',
                        stack: false,
                        showHideTransition: 'slide',
                        icon: 'success'
                    });
                    elem.classList.remove("alert");
                    $("#Modal_User_Mass").modal('hide');
                }
                else if(data.validation!=''){
                    elem.classList.add("alert");
                    $("#errors_mass").html(data.validation);
                }
                else if(data.error){
                    $.toast({
                        heading: 'Ошибка!',
                        text: data.error,
                        hideAfter: 5000,
                        position: 'top-right',
                        stack: false,
                        showHideTransition: 'slide',
                        icon: 'error'
                    });
                    $("#Modal_User_Mass").modal('hide');
                }
                else if(data){
                    const elem = document.getElementById("errors_mass");
                    elem.classList.remove("alert");
                    $("#errors_mass").html('');
                    $.toast({
                        heading: 'Успех!',
                        text: 'Успешное выполнение операции!',
                        hideAfter: 3000,
                        position: 'top-right',
                        stack: false,
                        showHideTransition: 'slide',
                        icon: 'success'
                    });
                    select_spec=null;
                    select_group=null;
                    $("#Modal_User_Mass").modal('hide');
                }
                else{
                    console.log('Неопознаная ошибка!');
                }
                $("#btn_mass_user").prop("disabled", false);
 
            },
            error: function (e) {
 
                //$("#output").text(e.responseText);
                console.log("ERROR : ", e);
                $("#btn_mass_user").prop("disabled", false);
 
            }
        });
 
    });
    setInterval(loadData, 1500);
});

function loadData() {
    $.ajax({
        url: "/UsersCrud/get_all",
        type: "POST",
        dataType:"json",
        success: function(data){
            if(data){
                if(JSON.stringify(first_users)!=JSON.stringify(data)){
                    first_users=data;
                    dateTable_Users.ajax.reload();
                }
            }

        },
        error: function(response){
            console.log('Ошибка при выполнении запроса');
        }
    });
}

function open_user_modal(id,type){
    switch(type){
        case 1:{
            const elem = document.getElementById("errors");
            elem.classList.remove("alert");
            $("#errors").html('');
            $("#user_data").trigger('reset')
            switch_fields();
            $("#Modal_User_CRU").modal('show');
            user_mode=1;
        }break;
        case 2:{
            $.ajax({
                url: "/UsersCrud/get",
                type: "POST",
                dataType:"json",
                data:{user_id:id},
                success: function(data){
                    if(data){
                        const elem = document.getElementById("errors");
                        elem.classList.remove("alert");
                        $("#errors").html('');
                        $("#user_data").trigger('reset')
                        $("#user_id").val(data.id);                        
                        $("#first_name").val(data.first_name);
                        $("#last_name").val(data.last_name);
                        $("#middle_name").val(data.middle_name);
                        $("#number_phone").val(data.number_phone);
                        $('#number_phone').trigger('change');
                        $("#sex").val(data.sex);
                        $("#date_birth").val(data.date_birth);
                        $("#email").val(data.email);
                        $("#role").val(data.role);
                        switch (data.role) {
                            case 'Студент':{
                                select_spec=data.specialization_id;
                                select_group=data.group_id;
                                switch_fields();
                            }break;
                            case 'Куратор':{
                                select_spec=data.specialization_id;
                                switch_fields();
                            }break;
                        }
                        $("#Modal_User_CRU").modal('show');
                        user_mode=2;
                    }
        
                },
                error: function(response){
                    console.log('Ошибка при выполнении запроса');
                }
            });

        }break;
        case 3:{
            $("#modal_del_title").html('Удаление пользователя');
            $("#delete_body").html('<p>Вы действительно хотите удалить пользователя и все связанные с ним данные?</p>');
            delete_id=null;
            delete_id=id;
            $("#btn_delete_user").prop("onclick", null).off("click");
            $("#btn_delete_user").click(function(){    
                ajaxCRUD(2,'/UsersCrud/delete','Modal_User_Delete',null,delete_id,null,'Успешное удаление пользователня!');
            });
            $("#Modal_User_Delete").modal('show');

        }break;
        case 4:{
            $("#modal_del_title").html('Удаление пользователей');
            $("#delete_body").html('<form id="delete_users_form" name="delete_users_form"><label for="users" class="form-label">Пользователи</label>'+
            '<select id="users" name="users[]" multiple="multiple" class="form-select"></select></form>'
            );
            $('#users').select2({
                placeholder: "Выберите пользователей",
                theme: 'bootstrap-5',
                width: '100%',
                "language": "ru",
                dropdownParent: $('#Modal_User_Delete')
            });
            load_users();
            $("#btn_delete_user").prop("onclick", null).off("click");
            $("#btn_delete_user").click(function(){            
                ajaxCRUD(1,'/UsersCrud/delete','Modal_User_Delete','delete_users_form',null,null,'Успешное удаление пользователей!');
            });
            $("#Modal_User_Delete").modal('show');

        }break;
        case 5:{
            $("#user_mass_data")[0].reset();
            $("#errors_mass").html('');
            $('#specialization_mass').select2({
                placeholder: "Выберите специальность",
                theme: 'bootstrap-5',
                width: '100%',
                "language": "ru",
                dropdownParent: $('#Modal_User_Mass')
            });
            $('#group_mass').select2({
                placeholder: "Выберите группу",
                theme: 'bootstrap-5',
                width: '100%',
                "language": "ru",
                dropdownParent: $('#Modal_User_Mass')
            });
            load_specializations(2);
            $("#Modal_User_Mass").modal('show');
        }break;
    }

}

function switch_fields(){
    switch(document.getElementById("role").value){
        case 'Студент':{
            //
            var row=
            '<div class="col-md-6 col-sm-12">'+
            '<div class="mb-3">'+
                '<label for="specialization" class="form-label">Специальность</label>'+
                '<select id="specialization" name="specialization" class="form-select"></select>'+
            '</div>'+
            '</div>'+
            '<div class="col-md-6 col-sm-12">'+
            '<div class="mb-3">'+
                '<label for="groups" class="form-label">Группа</label>'+
                '<select id="groups" name="groups" class="form-select"></select>'+
            '</div>'+
            '</div>';
            $("#dinam_row").html(row);
            $('#specialization').select2({
                placeholder: "Выберите специальность",
                theme: 'bootstrap-5',
                width: '100%',
                "language": "ru",
                dropdownParent: $('#Modal_User_CRU .modal-body')
            });
            $('#groups').select2({
                placeholder: "Выберите группу",
                theme: 'bootstrap-5',
                width: '100%',
                "language": "ru",
                dropdownParent: $('#Modal_User_CRU .modal-body')
            });
            load_specializations(1);
            load_groups(1,document.getElementById('specialization').value);
            $('#specialization').on('change', function(){
                load_groups(1,document.getElementById('specialization').value);
            });
        }break;
        case 'Куратор':{
            var row=
            '<div class="col-md-6 col-sm-12">'+
            '<div class="mb-3">'+
                '<label for="specialization" class="form-label">Специальность</label>'+
                '<select id="specialization" name="specialization" class="form-select" ></select>'+
            '</div>'+
            '</div>';
            $("#dinam_row").html(row);
            $('#specialization').select2({
                placeholder: "Выберите специальность",
                theme: 'bootstrap-5',
                width: '100%',
                "language": "ru",
                dropdownParent: $('#Modal_User_CRU .modal-body')
            });
            load_specializations(1);
        }break;
        case 'Работодатель':{
            $("#dinam_row").html('');
        }break;
        case 'Администратор':{
            $("#dinam_row").html('');
        }break;

    }
}

function load_specializations(type){
    $.ajax({
        url: "/GroupsSpecializationsCrud/get_specialization_list",
        type: "POST",
        dataType:"json",
        success: function(data){
            switch(type){
                case 1:{
                    if(data.length>0){
                        var options='';
                        $("#specialization").html("");
                        data.forEach(function(item, i, data) {
                            options=options+'<option value="'+item.id+'">'+item.name+'</option>';
                        });
                        $("#specialization").append(options);
                        $("#specialization").val(data[0].id).trigger("change");
                        if(select_spec){
                            $("#specialization").val(select_spec);
                            $('#specialization').trigger('change'); // Notify any JS components that the value changed
                        }
                    }
                    else{
                        var options='';
                        $("#specialization").html("<option value=''>Специальностей нет</option>");
                    }
                }break;
                case 2:{
                    if(data.length>0){
                        var options='';
                        $("#specialization_mass").html("");
                        data.forEach(function(item, i, data) {
                            options=options+'<option value="'+item.id+'">'+item.name+'</option>';
                        });
                        $("#specialization_mass").append(options);
                        $("#specialization_mass").val(data[0].id).trigger("change");
                        load_groups(2,data[0].id);
                    }
                    else{
                        var options='';
                        $("#specialization_mass").html("<option value=''>Специальностей нет</option>");
                    }
                }break;
            }
        },
        error: function(response){
            console.log('Ошибка при выполнении запроса');
        }
    });
   
}

function load_groups(type,id) {
    $.ajax({
        url: "/GroupsSpecializationsCrud/get_groups_list",
        type: "POST",
        dataType:"json",
        data:{specialization_id:id,type:type_select},
        success: function(data){
            switch (type) {
                case 1:{
                    $('#groups').html('');
                    const list=[];
                    if(data.length>0){
                        var options='';                
                        data.forEach(function(item, i, data) {
                            options=options+'<option value="'+item.id+'">'+item.name+'</option>';
                        });
                        $("#groups").html(options);
                        
                        if(select_group){
                            $("#groups").val(select_group);
                            $('#groups').trigger('change'); // Notify any JS components that the value changed
                        }
                    }
                    else{
                        var options='';
                        $("#groups").html(options);
                    }
                }break;
                case 2:{
                    $('#group_mass').html('');
                    const list=[];
                    if(data.length>0){
                        var options='';                
                        data.forEach(function(item, i, data) {
                            options=options+'<option value="'+item.id+'">'+item.name+'</option>';
                        });
                        $("#group_mass").html(options);
                    }
                    else{
                        var options='';
                        $("#group_mass").html(options);
                    }
                }break;
            }
        },
        error: function(response){
            console.log('Ошибка при выполнении запроса');
        }
    });
}

function load_users() {
    $.ajax({
        url: "/usersCrud/get_users",
        type: "POST",
        dataType:"json",
        success: function(data){
            $('#users').html('');
            const list=[];
            if(data.length>0){
                var options='';                
                data.forEach(function(item, i, data) {
                    options=options+'<option value="'+item.id+'">'+item.first_name+' '+item.last_name+' '+item.middle_name+' ('+item.email+')</option>';
                });
                $("#users").html(options);
            }
            else{
                var options='';
                $("#users").html(options);
            }
        },
        error: function(response){
            console.log('Ошибка при выполнении запроса');
        }
    });
}