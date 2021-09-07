var offer_first=[];
var search_first='';
$( document ).ready(function() {
    jQuery.fn.exists = function() {
        return $(this).length;
    };
    loadData();
    setInterval(loadData, 1000);
});

function loadData() {
    var row='';
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
            if(data&&data.all_info.length>0){
                if(!arraysEqual(offer_first,data.all_info)){
                    var all_offers=data.all_info;
                    offer_first=data.all_info;
                    var salary='';
                    all_offers.forEach(function(item, i, all_offers) {
                        if(!item.salary||item.salary<=0){
                            salary="Не предусмотрено";
                        }
                        else{
                            salary=item.salary+" руб";
                        }
                        if(item.is_favorite){
                            var favorite_btn='<button id="btn_fv_off-'+item.id+'" type="button" class="btn btn-danger mx-1"><i class="bi bi-heart"></i> Удалить из избранного</button>';
                        }
                        else{
                            var favorite_btn='<button id="btn_fv_off-'+item.id+'" type="button" class="btn btn-outline-danger mx-1"><i class="bi bi-heart"></i> Добавить в избранное</button>';                            
                        }
                        if(!item.is_respond){
                            var respond_btn='<button id="btn_rs_off-'+item.id+'" type="button" class="btn btn-outline-warning mx-1">Откликнуться</button>';
                            
                        }
                        else{
                            var respond_btn='<button id="btn_rs_off-'+item.id+'" type="button" class="btn btn-warning mx-1">В откликах</button>';                                
                        }
                        switch(page){
                            case 1:{
                                if(item.is_favorite){
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
                                                    '<button type="button" data-bs-toggle="modal" data-bs-target="#Modal_Offer_Info" onclick="offer_info('+item.id+')" class="btn btn-outline-info mx-1">Подробнее</button>'+
                                                    favorite_btn+
                                                    respond_btn+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>';
                                }
                            }break;
                            case 2:{
                                if(item.is_respond){
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
                                '</div>';
                                }
                            }break
                        }

                        
                    });
                    if(row!=''){
                        $("#list").html(row);
                        console.log(row);
                        switch(page){
                            case 1:{
                                all_offers.forEach(element => {
                                    if(element.is_favorite){
                                        $("#btn_fv_off-"+element.id).click(function(){
                                            ajaxCRUD(2,'/offerCrud/add_favorite',null,null,element.id,null,'Успешное действие!');
                                        });
                                        $("#btn_rs_off-"+element.id).click(function(){
                                            ajaxCRUD(2,'/offerCrud/add_respond',null,null,element.id,null,'Успешное действие!');
                                        });
                                        $("#btn_info_off-"+element.id).click(function(){
                                            offer_info(element.id)
                                        });
                                        
                                    }
                                });
                            }break;
                            case 2:{
                                all_offers.forEach(element => {
                                    if(element.is_respond){
                                        $("#btn_fv_off-"+element.id).click(function(){
                                            ajaxCRUD(2,'/offerCrud/add_favorite',null,null,element.id,null,'Успешное действие!');
                                        });
                                        $("#btn_rs_off-"+element.id).click(function(){
                                            ajaxCRUD(2,'/offerCrud/add_respond',null,null,element.id,null,'Успешное действие!');
                                        });
                                        $("#btn_info_off-"+element.id).click(function(){
                                            offer_info(element.id)
                                        });
                                    }
                                });
                            }break;
                        }
                    }
                    else{
                        var alert=
                        '<div class="alert alert-info" role="alert">'+
                            'Пока что данный список пустует!'+
                        '</div>';
                        $("#list").html(alert);
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

function arraysEqual(arr1, arr2) {
    return (JSON.stringify(arr1) === JSON.stringify(arr2) ? true : false);
}