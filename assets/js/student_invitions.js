var orgs_first=[];
var search_first='';
$( document ).ready(function() {
    $("#add_require").click(function(){
      $("#require_list").append('<div class="col-md-8 col-sm-12 mt-1 input-group"><input class="form-control" type="text" name="require[]"/><a class="btn btn-secondary">Удалить</a></div>');
      $("#require_list").find("a").unbind("click");
      $("#require_list").find("a").click(function(){
      $(this).parent().remove();      
      });      
    });
    jQuery.fn.exists = function() {
        return $(this).length;
    };
    loadData();
    setInterval(loadData, 1000);
});

function loadData() {
    var row='';
    var search_val='';
    if($("#search").exists()) {
        if(search_first!=document.getElementById('search').value){
            offer_first=[];
            search_first=document.getElementById('search').value;
        }
        search_val=document.getElementById('search').value;
    }
    else{
        search_val=null;
    }
    $.ajax({
        url: "StudentsInfo/get_invitions",
        type: "POST",
        data:{search:search_val},
        dataType:"json",
        success: function(data){
            if(data){
                if(JSON.stringify(orgs_first)!=JSON.stringify(data)){
                    var load_data=data;
                    orgs_first=data;
                    load_data.forEach(function(item, i, load_data) {
                        var status='';
                        switch(item.status){
                            case 'Приглашён':{
                                status=
                                '<div class="alert alert-danger" role="alert">'+
                                    'Вы приглашены на собеседование!'+
                                '</div>';
                            }break;
                            case 'Закреплён':{
                                status=
                                '<div class="alert alert-danger" role="alert">'+
                                    'Вы закреплены к данной организации!'+
                                '</div>';
                            }break;
                        }
                        row=row+  
                        '<div class="row">'+
                            '<div class="card mb-3">'+
                                '<div class="row">'+
                                    '<div class="col-md-4 my-auto">'+
                                        '<img src="'+item.org_photo+'" style="max-width:300px" alt="..." class="img-fluid my-2">'+
                                    '</div>'+
                                    '<div class="col-md-8">'+
                                        '<div class="card-body">'+
                                            '<h5 class="card-title">'+item.org_name+'</h5>'+
                                            '<p class="card-text">Описание: '+item.org_description+'</p>'+
                                            status+
                                            '<button type="button" data-bs-toggle="modal" data-bs-target="#Modal_Org_Info" onclick="org_info('+item.id+')" class="btn btn-outline-info mx-1">Подробнее</button>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>';

                    });
                    if(row==''){
                        var alert=
                        '<div class="alert alert-info" role="alert">'+
                            'Пока что данный список пустует!'+
                        '</div>';
                        $("#list").html(alert);
                    }
                    else{
                        $("#list").html(row);
                    }
                    
                }                
            }
            else{
                var alert=
                '<div class="alert alert-info" role="alert">'+
                    'Пока что данный список пустует!'+
                '</div>';
                $("#list").html(alert);
            }
            
        },
        error: function(response){
            console.log('Ошибка при выполнении запроса');
        }
    });
}

function org_info(id){
    $.ajax({
        url: "OrganizationCrud/get",
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
            }
            else{

            }
        },
        error: function(response){
            console.log('Ошибка при выполнении запроса');
        }
    });
}