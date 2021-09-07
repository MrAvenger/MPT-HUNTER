var all_data=[];
$( document ).ready(function() {
    $("#new").append('<div class="row my-2"><div class="col-md-8 mt-1"><div class="mb-3"><label for="formFile" class="form-label">Файл</label><input class="form-control" type="file" name="files[]"></div></div><div class="col-md-12"><div class="mb-3"><label for="exampleFormControlTextarea1" class="form-label">Описание</label><input type="text" class="form-control" name="description[]" rows="2"></div></div></div>');
    if ($("#portfolio_form").length > 0) {
        $("#portfolio_form").validate({
            rules: {
                description: {
                    required: true,
                    minlength: 10,
                    maxlength: 255,
                },
            },
            messages: {
                description: {
                    required: "Описание - обязательное поле.",
                    minlength: "Поле 'Описание' должно содержать не менее 10 символов!",
                    maxlength: "Поле 'Описание' должно содержать не более 255 символов!",
                },
            },
        })
    }
    $("#btn_add_fields").click(function(){
        $("#new").append('<div class="row my-2"><div class="col-md-8 mt-1"><div class="mb-3"><label for="formFile" class="form-label">Файл</label><input class="form-control" type="file" name="files[]"></div></div><div class="col-md-12"><div class="mb-3"><label for="exampleFormControlTextarea1" class="form-label">Описание</label><input type="text" class="form-control" name="description[]" rows="2"></div></div><a class="btn btn-secondary" style="max-width:150px;">Удалить</a></div>');
        $("#new").find("a").unbind("click");
        $("#new").find("a").click(function(){
        $(this).parent().remove();      
        });      
    });

    $("#btn_add_data").click(function (event) {
        var data = new FormData(portfolio_form);
        $("#new").append('<div id="spinner" class="alert alert-primary d-flex align-items-center" role="alert"><svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg><div>Выполнение запроса...<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></div></div>');
        $("#btn_add_data").prop("disabled", true);
 
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: "/PortfolioCrud/add",
            data: data,
            dataType:"json",
            processData: false,
            contentType: false,
            cache: false,
            timeout: 800000,
            success: function (data) {
                const elem = document.getElementById("errors_portfolio");
                $("#mass_status").html('');
                console.log(data);
                if(data!=true){
                    var spin = document.getElementById('spinner');
                    spin.parentNode.removeChild(spin);
        
                    const elem = document.getElementById("errors_portfolio");
                    elem.classList.add("alert");
                    $("#errors_portfolio").html(data.validation);
                }
                else if(data){
                    const elem = document.getElementById("errors_portfolio");
                    elem.classList.remove("alert");
                    $("#errors_portfolio").html('');
                    $.toast({
                        heading: 'Успех!',
                        text: 'Файлы успешно загружены!',
                        hideAfter: 3000,
                        position: 'top-right',
                        stack: false,
                        showHideTransition: 'slide',
                        icon: 'success'
                    });
                    $("#new").html('<div class="row my-2"><div class="col-md-8 mt-1"><div class="mb-3"><label for="formFile" class="form-label">Файл</label><input class="form-control" type="file" name="files[]"></div></div><div class="col-md-12"><div class="mb-3"><label for="exampleFormControlTextarea1" class="form-label">Описание</label><input type="text" class="form-control" name="description[]" rows="2"></div></div></div>');
                }
                else{
                    console.log('Неопознаная ошибка!');
                }
                $("#btn_add_data").prop("disabled", false);
 
            },
            error: function (e) {
 
                //$("#output").text(e.responseText);
                console.log("ERROR : ", e);
                $("#btn_add_data").prop("disabled", false);
 
            }
        });
 
    });
    setInterval(function() {
        load_data();
    }, 1000);
});

function load_data(){
    $.ajax({
        url: "/PortfolioCrud/get_all",
        type: "POST",
        dataType:"json",
        success: function(data){
            if(data.length>0){
                if(JSON.stringify(all_data)!=JSON.stringify(data)){
                    all_data=data;
                    var html='<ul class="list-group">';
                    data.forEach(function(item, i, data) {
                        var icon_archive='<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-zip" viewBox="0 0 16 16"><path d="M5 7.5a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v.938l.4 1.599a1 1 0 0 1-.416 1.074l-.93.62a1 1 0 0 1-1.11 0l-.929-.62a1 1 0 0 1-.415-1.074L5 8.438V7.5zm2 0H6v.938a1 1 0 0 1-.03.243l-.4 1.598.93.62.929-.62-.4-1.598A1 1 0 0 1 7 8.438V7.5z"/>  <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1h-2v1h-1v1h1v1h-1v1h1v1H6V5H5V4h1V3H5V2h1V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/></svg>';
                        html=html+'<li class="list-group-item">'+icon_archive+' <a href="'+item.url+'">'+item.filename+'</a><h5 class="text-muted">'+item.description+'</h5><button type="button" onclick="delete_data('+item.id+')" class="btn btn-outline-dark">Удалить</button></li>';
                    });
                    html=html+'</ul>';
                    $("#old").html(html);
                }
            }
            else{
                $("#old").html('');
            }
        },
        error: function(response){
            console.log('Ошибка при выполнении запроса');
        }
    });
   
}

function delete_data(id){
    $.ajax({
        url: "/PortfolioCrud/delete",
        type: "POST",
        dataType:"json",
        data:{file_id:id},
        success: function(data){
            console.log('Файл удалён');
        },
        error: function(response){
            // console.log(response);
            console.log('Файл удалён');
        }
    });
   
}