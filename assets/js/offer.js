var offer_first=[];
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
function add_open(){
    $("#Add_Edit_Modal_Title").html('Добавление нового предложения (вакансии)');
    $("#Footer_buttons").html(' <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button><button type="button" onclick="addData();" class="btn btn-danger">Сохранить</button>');
    $("#require_list").html('');
}

function delete_open(id){
    $("#Footer_buttons_del").html(' <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button><button type="button" onclick="deleteData('+id+');" class="btn btn-danger">Удалить</button>');
}

function edit_open(id){
    $("#Add_Edit_Modal_Title").html('Изменение данных предложения (вакансии)');
    $("#Footer_buttons").html(' <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button><button type="button" onclick="editData('+id+')" class="btn btn-danger">Изменить</button>');
    $("#require_list").html('');
    $.ajax({
        url: "offerCrud/get",
        type: "POST",
        data:{id:id},
        dataType:"json",
        success: function(data){
            var offer=data.offer;
            var requirements=data.requirements;
            var sel = document.getElementById('employment');
            for(var i = 0, j = sel.options.length; i < j; ++i) {
                if(sel.options[i].innerHTML === offer.employment) {
                   sel.selectedIndex = i;
                   break;
                }
            }
            document.getElementById('offer_name').value = offer.offer_name;
            document.getElementById('salary').value = offer.salary;
            document.getElementById('offer_description').value = offer.offer_description;
            requirements.forEach(function(item, i, requirements) {
                $("#require_list").append('<div class="col-md-8 col-sm-12 mt-1 input-group"><input class="form-control" type="text" name="old_require['+item.id+']" value="'+item.name+'"/><a class="btn btn-secondary">Удалить</a></div>');
                $("#require_list").find("a").unbind("click");
                $("#require_list").find("a").click(function(){
                $(this).parent().remove();
                });
            });

        },
        error: function(response){
            console.log('Ошибка при выполнении запроса');
        }
    });
}

function loadData() {
    var row;
    var search_val;
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
        url: "offerCrud/get_data",
        type: "POST",
        data:{search:search_val},
        dataType:"json",
        success: function(data){
            if(data){
                if(data.role=='Работодатель'){
                    if(!arraysEqual(offer_first,data.offers)){
                        console.log(JSON.stringify(offer_first)==JSON.stringify(data.offers));
                        offer_first=data.offers;
                        var data_org=data.org;
                        var offers=data.offers;
                        var salary='';
                        if(!data_org.org_photo){
                            data_org.photo='assets/img/design/org_photo.jpg';
                        }
                        offers.forEach(function(item, i, offers) {
                        if(!item.salary||item.salary<=0){
                            salary="Не предусмотрено";
                        }
                        else{
                            item.salary;
                            salary=item.salary+" руб";
                        }
                         row=row+  '<div class="row">'+
                            '<div class="card mb-3">'+
                                '<div class="row">'+
                                    '<div class="col-md-4 my-auto">'+
                                        '<img src="'+data_org.photo+'" style="max-width:300px" alt="..." class="img-fluid">'+
                                    '</div>'+
                                    '<div class="col-md-8">'+
                                        '<div class="card-body">'+
                                            '<h5 class="card-title">'+item.offer_name+' <small class="text-muted">('+data_org.org_name+')</small></h5>'+
                                            '<p class="card-text">Тип занятости: '+item.employment+'</p>'+
                                            '<p class="card-text">Описание вакансии: '+item.offer_description+'</p>'+
                                            '<p class="card-text">Адрес организации: '+data_org.org_adress+'</p>'+
                                            '<p class="card-text"><small class="text-muted">Заработная плата: '+salary+'</small></p>'+
                                            '<button type="button" data-bs-toggle="modal" data-bs-target="#Add_Edit_Modal" onclick="edit_open('+item.id+')" class="btn btn-danger mx-1">Изменить</button>'+
                                            '<button type="button" data-bs-toggle="modal" data-bs-target="#Delete_Modal" class="btn btn-secondary mx-1" onclick="delete_open('+item.id+')">Удалить</button>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'
                    });
                        $("#offers_list").html(row);
                    }
                    
                }
                else{
                    if(!arraysEqual(offer_first,data.all_info)){
                        var all_offers=data.all_info;
                        offer_first=data.all_info;
                        var salary='';
                        all_offers.forEach(function(item, i, all_offers) {
                            if(!item.salary||item.salary<=0){
                                salary="Не предусмотрено";
                            }
                            else{
                                salary=item.salary+' руб';
                            }
                            if(item.is_favorite){
                                var favorite_btn='<button id="btn_fv_off-'+item.id+'" type="button" class="btn btn-danger mx-1"><i class="bi bi-heart"></i> Удалить из избранного</button>';                                
                            }
                            else{
                                var favorite_btn='<button id="btn_fv_off-'+item.id+'" type="button" class="btn btn-outline-danger mx-1"><i class="bi bi-heart"></i> Добавить в избранное</button>'; 
                            }
                            if(item.is_respond){
                                var respond_btn='<button id="btn_rs_off-'+item.id+'" type="button" class="btn btn-warning mx-1">В откликах</button>';                                                               
                            }
                            else{
                                var respond_btn='<button id="btn_rs_off-'+item.id+'" type="button" class="btn btn-outline-warning mx-1">Откликнуться</button>'; 
                            }
                             row=row+  '<div class="row">'+
                                '<div class="card mb-3">'+
                                    '<div class="row">'+
                                        '<div class="col-md-4 my-auto">'+
                                            '<img src="'+item.org_photo+'" style="max-width:300px" alt="..." class="img-fluid">'+
                                        '</div>'+
                                        '<div class="col-md-8">'+
                                            '<div class="card-body">'+
                                                '<h5 class="card-title">'+item.offer_name+' <small class="text-muted">('+item.org_name+')</small></h5>'+
                                                '<p class="card-text">Тип занятости: '+item.employment+'</p>'+
                                                '<p class="card-text">Описание вакансии: '+item.offer_description+'</p>'+
                                                '<p class="card-text">Адрес организации: '+item.org_adress+'</p>'+
                                                '<p class="card-text"><small class="text-muted">Заработная плата: '+salary+'</small></p>'+
                                                '<button id="btn_info_off-'+item.id+'" type="button" data-bs-toggle="modal" data-bs-target="#Modal_Offer_Info" class="btn btn-outline-info mx-1">Подробнее</button>'+
                                                favorite_btn+
                                                respond_btn+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'
                        });
                        $("#offers_list").html(row);
                        all_offers.forEach(element => {
                            $("#btn_fv_off-"+element.id).click(function(){
                                ajaxCRUD(2,'/offerCrud/add_favorite',null,null,element.id,null,'Успешное действие!');
                            });
                            $("#btn_rs_off-"+element.id).click(function(){
                                ajaxCRUD(2,'/offerCrud/add_respond',null,null,element.id,null,'Успешное действие!');
                            });
                            $("#btn_info_off-"+element.id).click(function(){
                                offer_info(element.id)
                            });
                        });
                    }
                }
            }
            else{
                var alert=
                '<div class="alert alert-info" role="alert">'+
                    'Пока что данный список пустует!'+
                '</div>';
                $("#offers_list").html(alert);
                //$("#offers_list").html('');
            }
            
        },
        error: function(response){
            console.log('Ошибка при выполнении запроса');
        }
    });
}

function addData() {
    $.ajax({
        url: "offerCrud/add",
        type: "POST",
        data:$('#offer_form').serialize(),
        dataType:"json",
        success: function(data){
            if(data==true){
                $.toast({
                    heading: 'Успех',
                    text: 'Успешное добавление предложения.',
                    position: 'top-right',
                    stack: false,
                    showHideTransition: 'slide',
                    icon: 'success'
                });
                $('#Add_Edit_Modal').modal('hide');
                $("#require_list").html('');
                $('#offer_form').trigger("reset");
                //getElementById("offer_form").reset();
            }
            else{
                $.toast({
                    heading: 'Ошибка',
                    text: data.validation,
                    hideAfter: 10000,
                    position: 'top-right',
                    stack: false,
                    showHideTransition: 'slide',
                    icon: 'error'
                })
            }
        },
        error: function(response){
            console.log('Ошибка при выполнении запроса');
        }
    });
}

function editData(id){
    $.ajax({
        url: "offerCrud/edit/"+id,
        type: "POST",
        data:$('#offer_form').serialize(),
        dataType:"json",
        success: function(data){
            if(data==true){
                $.toast({
                    heading: 'Успех',
                    text: 'Успешное изменение данных предложения (вакансии).',
                    position: 'top-right',
                    stack: false,
                    showHideTransition: 'slide',
                    icon: 'success'
                });
                $('#Add_Edit_Modal').modal('hide');
                $("#require_list").html('');
                loadData();
                $('#offer_form').trigger("reset");
                //getElementById("offer_form").reset();
            }
            else{
                $.toast({
                    heading: 'Ошибка',
                    text: data.validation,
                    hideAfter: 10000,
                    position: 'top-right',
                    stack: false,
                    showHideTransition: 'slide',
                    icon: 'error'
                })
            }
        },
        error: function(response){
            console.log('Ошибка при выполнении запроса');
        }
    });
}

function deleteData(id){
    $.ajax({
        url: "offerCrud/delete",
        type: "POST",
        data:{id:id},
        dataType:"json",
        success: function(data){
            if(data==true){
                $.toast({
                    heading: 'Успех',
                    text: 'Успешное удаление данных предложения (вакансии).',
                    position: 'top-right',
                    stack: false,
                    showHideTransition: 'slide',
                    icon: 'success'
                });
                $('#Delete_Modal').modal('hide');
                $("#Footer_buttons_del").html('');
                loadData();
            }
            else{
                $.toast({
                    heading: 'Ошибка',
                    text: 'Ошибка при удалении',
                    hideAfter: 10000,
                    position: 'top-right',
                    stack: false,
                    showHideTransition: 'slide',
                    icon: 'error'
                })
            }
        },
        error: function(response){
            console.log('Ошибка при выполнении запроса');
        }
    });
}

function add_favorite(id){
    $.ajax({
        url: 'offerCrud/add_favorite',         /* Куда пойдет запрос */
        method: 'POST',             /* Метод передачи (post или get) */
        dataType: 'json',          /* Тип данных в ответе (xml, json, script, html). */
        data: {id: id},     /* Параметры передаваемые в запросе. */
        success: function(data){   /* функция которая будет выполнена после успешного запроса.  */
            if(data==true){
                console.log('Добавлено в избранное');
            }
            else{
                console.log('Удалено из избранного');
            }
        },
        error: function(respond){
            console.log('Ошибка функции добавления в избранное');
        }
    });   
}

function respond(id){
    $.ajax({
        url: 'offerCrud/add_respond',         /* Куда пойдет запрос */
        method: 'POST',             /* Метод передачи (post или get) */
        dataType: 'json',          /* Тип данных в ответе (xml, json, script, html). */
        data: {id: id},     /* Параметры передаваемые в запросе. */
        success: function(data){   /* функция которая будет выполнена после успешного запроса.  */
            if(data==true){
                console.log('Добавлено в отклики');
            }
            else{
                console.log('Удалено из раздела отклики');
            }
        },
        error: function(respond){
            console.log('Ошибка функции добавления в отклики');
        }
    });   
}

function arraysEqual(arr1, arr2) {
    return (JSON.stringify(arr1) === JSON.stringify(arr2) ? true : false);
}