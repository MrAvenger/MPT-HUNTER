var orgs_first=[];
var dateTable_Orgs;
let org_mode=1;
$( document ).ready(function() {
    jQuery.fn.exists = function() {
        return $(this).length;
    };
    var options = {
        id: 'org_adress'
    };

    //запускаем модуль подсказок
    AhunterSuggest.Address.Solid(options);
    dateTable_Orgs=$('#organizations_table').DataTable({
        "oLanguage": {
            "sUrl": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Russian.json"
        },
        "ajax": {
            url : "/OrganizationCrud/get_all",
            type : 'POST'
        },
    });    
    user=$('#user').select2({
        placeholder: "Выберите работодателя",
        theme: 'bootstrap-5',
        width: '100%',
        "language": "ru",
        dropdownParent: $('#Modal_Org_CRU')
    });
    org_data.onsubmit = async (e) => {        
        e.preventDefault();
        let response
        switch(org_mode){
            case 1:{
                response = await fetch('/OrganizationCrud/add', {
                    method: 'POST',
                    body: new FormData(org_data)
                  }); 
            }break;
            case 2:{
                response = await fetch('/OrganizationCrud/edit', {
                    method: 'POST',
                    body: new FormData(org_data)
                  }); 
            }break;
        }
   
        let result = await response.json();
        if(result.validation){
            const elem = document.getElementById("errors");
            elem.classList.add("alert");
            $("#errors").html(result.validation);
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
            $("#Modal_Org_CRU").modal('hide');
        }
        else{
            console.log('Неопознаная ошибка!');
        }
    };
    setInterval(loadData, 1500);
});

function loadData() {
    $.ajax({
        url: "/OrganizationCrud/get_all",
        type: "POST",
        dataType:"json",
        success: function(data){
            if(data){
                if(JSON.stringify(orgs_first)!=JSON.stringify(data)){
                    orgs_first=data;
                    dateTable_Orgs.ajax.reload();
                }
            }

        },
        error: function(response){
            console.log('Ошибка при выполнении запроса');
        }
    });
}

function add_open_org(){
    const elem = document.getElementById("errors");
    elem.classList.remove("alert");
    $("#errors").html('');
    $("#org_data").trigger('reset')
    load_users_list();
    org_mode=1;
}

function open_org_modal(id,type){
    $.ajax({
        url: "/OrganizationCrud/get",
        type: "POST",
        dataType:"json",
        data:{id:id},
        success: function(data){
            if(data){
                switch(type){
                    case 1:{
                        $("#org_name").val(data.org_name);
                        $("#id_org").val(data.id);
                        load_users_list(data.user_id);
                        $("#post").val(data.post);
                        $("#org_adress").val(data.org_adress);
                        $("#org_description").html(data.org_description);
                        org_mode=2;
                        $("#Modal_Org_CRU").modal('show');
                    }break;
                    case 2:{
                        $("#Modal_Org_Delete").modal('show');
                        $("#btn_delete_org").click(function(){
                            console.log(data.id);
                            ajaxCRUD(2,'/OrganizationCrud/delete','Modal_Org_Delete',null,data.id,null,'Успешное удаление организации');
                            //data.id
                        });
                    }break;
                }
            }

        },
        error: function(response){
            console.log('Ошибка при выполнении запроса');
        }
    });
}

function delete_open_org(id){
    $.ajax({
        url: "/OrganizationCrud/de",
        type: "POST",
        dataType:"json",
        data:{id:id},
        success: function(data){
            if(data){
                $("#org_name").val(data.org_name);
                $("#id_org").val(data.id);
                load_users_list(data.user_id);
                $("#post").val(data.post);
                $("#org_adress").val(data.org_adress);
                $("#org_description").html(data.org_description);
                org_mode=2;
                $("#Modal_Org_CRU").modal('show');
            }

        },
        error: function(response){
            console.log('Ошибка при выполнении запроса');
        }
    });
}

function org_info(id){
    $.ajax({
        url: "/OrganizationCrud/get",
        type: "POST",
        data:{id:id},
        dataType:"json",
        success: function(data){
            if(data){
                console.log(data);
                var html='';
                var post='';
                var number_phone='';
                var org_description='';
                if(data.post){
                    post=data.post;
                }
                else{
                    post='Не указано';
                }
                if(data.number_phone){
                    number_phone=data.number_phone;
                }
                else{
                    number_phone='Не указано';
                }
                if(data.org_description){
                    org_description=data.org_description;
                }
                else{
                    org_description='Не указано';
                }
                html=html+
                '<div class="row">'+
                    '<div class="col-md-6 col-sm-12">'+
                        '<label for="org_name" class="form-label">Наименование организации</label>'+
                        '<h6 id="org_name">'+data.org_name+'</h6>'+
                    '</div>'+
                '</div>'+
                '<div class="row">'+
                    '<div class="col-md-12 col-sm-12">'+
                        '<label for="org_description" class="form-label">Описание организации</label>'+
                        '<pre><h6 id="org_description">'+org_description+'</h6></pre>'+
                    '</div>'+
                '</div>'+
                '<div class="row">'+
                    '<div class="col-md-6 col-sm-12">'+
                        '<label for="fio" class="form-label">Представитель организации</label>'+
                        '<h6 id="fio">'+data.last_name+' '+data.first_name+' '+data.middle_name+'</h6>'+
                    '</div>'+
                    '<div class="col-md-6 col-sm-12">'+
                        '<label for="post" class="form-label">Должность</label>'+
                        '<h6 id="post">'+post+'</h6>'+
                    '</div>'+
                '</div>'+
                '<div class="row">'+
                    '<div class="col-md-6 col-sm-12">'+
                        '<label for="fio" class="form-label">Номер телефона</label>'+
                        '<h6 id="fio">'+number_phone+'</h6>'+
                    '</div>'+
                    '<div class="col-md-6 col-sm-12">'+
                        '<label for="email" class="form-label">Email</label>'+
                        '<h6 id="email">'+data.email+'</h6>'+
                    '</div>'+
                '</div>'+
                '<div class="row">'+
                    '<div class="col-md-6 col-sm-12">'+
                        '<label for="org_adress" class="form-label">Адрес организации</label>'+
                        '<h6 id="org_adress">'+data.org_adress+'</h6>'+
                    '</div>'+
                '</div>';
                $("#content").html(html);
                $("#Modal_Org_Info").modal('show');
            }
            else{

            }
        },
        error: function(response){
            console.log('Ошибка при выполнении запроса');
        }
    });
}

function load_users_list(id) {
    $.ajax({
        url: "/OrganizationCrud/get_employers",
        type: "POST",
        dataType:"json",
        success: function(data){
            $('#user').val(null).trigger('change');
            if(data.length>0){
                var options='';
                $("#user").html("");
                data.forEach(function(item, i, data) {
                    options=options+'<option value="'+item.id+'">'+item.last_name+' '+item.first_name+' '+item.middle_name+' ('+item.email+')</option>';
                });
                $("#user").html(options);
                if(id){
                    $("#user").val(id);
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