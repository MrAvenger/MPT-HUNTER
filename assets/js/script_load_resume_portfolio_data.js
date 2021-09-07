function load_portfolio(id){
    $.ajax({
        url: "/StudentsInfo/get_portfolio",
        type: "POST",
        dataType:"json",
        data:{user_id:id,groups:all_data.groups},
        success: function(data){
            if(data.length>0){
                var html='<ul class="list-group">';
                data.forEach(function(item, i, data) {
                    var icon_archive='<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-zip" viewBox="0 0 16 16"><path d="M5 7.5a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v.938l.4 1.599a1 1 0 0 1-.416 1.074l-.93.62a1 1 0 0 1-1.11 0l-.929-.62a1 1 0 0 1-.415-1.074L5 8.438V7.5zm2 0H6v.938a1 1 0 0 1-.03.243l-.4 1.598.93.62.929-.62-.4-1.598A1 1 0 0 1 7 8.438V7.5z"/>  <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1h-2v1h-1v1h1v1h-1v1h1v1H6V5H5V4h1V3H5V2h1V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/></svg>';
                    html=html+'<li class="list-group-item">'+icon_archive+' <a href="'+item.url+'">'+item.filename+'</a><h5 class="text-muted">'+item.description+'</h5></li>';
                });
                html=html+'</ul>';
                var fullname
                const elem = document.getElementById("dialog_portfolio_modal");
                elem.classList.remove("modal-fullscreen");
                elem.classList.add("modal-lg");
                all_data.students.forEach(element => {
                    if(element.id==id){
                        fullname=element.last_name+' '+element.first_name+' '+element.middle_name;
                    }
                });
                $("#portfolio_modal_title").html('Просмотр портфолио студента "'+fullname+'"');
                $("#portfolio_modal_body").html(html);
                $("#PortfolioModal").modal('show');
            }
            else{
                $.toast({
                    heading: 'Ошибка',
                    text: 'Нет данных о портфолио!',
                    hideAfter: 3000,
                    position: 'top-right',
                    stack: false,
                    showHideTransition: 'slide',
                    icon: 'error'
                });
            }
        },
        error: function(response){
            console.log('Ошибка при выполнении запроса');
        }
    });
   
}

function load_resume(type,id){
    $.ajax({
        url: "/StudentsInfo/get_resume",
        type: "POST",
        dataType:"json",
        data:{switch:type,user_id:id,groups:all_data.groups},
        success: function(data){
            if(data.length>0){
                var fullname='';
                var work_experience='';
                var additionally='';
                var nearest_metro='';
                var average_score='';
                var html='';
                var emploer=data[0].emploer;
                const elem = document.getElementById("dialog_resume_modal");
                elem.classList.remove("modal-fullscreen");
                elem.classList.add("modal-lg");
                all_data.students.forEach(element => {
                    if(element.id==id){
                        fullname=element.last_name+' '+element.first_name+' '+element.middle_name;
                    }
                });
                var skills=data[0].skills;
                var html_skills='';
                //console.log(data);
                if(skills.length>0){
                    html_skills="<ul>";
                    skills.forEach(element => {
                        html_skills=html_skills+'<li>'+element.name+'</li>';
                    });
                    html_skills=html_skills+"</ul>";
                }
                else{
                    html_skills='Нет данных';
                }
                if(data[0].work_experience){
                    work_experience=data[0].work_experience;
                }
                else{
                    work_experience='Нет опыта работы';
                }
                if(data[0].additionally){
                    additionally=data[0].additionally;
                }
                else{
                    additionally='Нет дополнительной информации';
                }
                if(data[0].nearest_metro){
                    nearest_metro=data[0].nearest_metro;
                }
                else{
                    nearest_metro='Не указано';
                }
                if(data[0].average_score){
                    average_score='Средний балл: '+data[0].average_score;
                }
                else{
                    average_score='Нет информации о среднем балле';
                }
                html=html+
                '<div class="container">'+
                    '<div class="row">'+
                        '<div class="col-md-12">'+
                            '<h5>О себе</h5><pre>'+
                            data[0].about_me+
                        '</pre></div">'+
                    '</div>'+
                    '<div class="row">'+
                        '<div class="col-md-12">'+
                            '<h5>Навыки/умения</h5>'+
                            html_skills+
                        '</div>'+
                    '</div>'+
                    '<div class="row">'+
                        '<div class="col-md-12">'+
                            '<h5>Опыт работы</h5><pre>'+
                            work_experience+
                        '</pre></div>'+
                    '</div>'+
                    '<div class="row">'+
                        '<div class="col-md-12">'+
                            '<h5>Образование</h5>'+
                            data[0].education+
                        '</div>'+
                    '</div>'+
                    '<div class="row">'+
                        '<div class="col-md-12">'+
                            '<h5>Дополнительно</h5><pre>'+
                            additionally+
                        '</pre></div>'+
                    '</div>'+
                    '<div class="row">'+
                        '<div class="col-md-12">'+
                            '<h5>Успеваемость</h5>'+
                            average_score+
                        '</div>'+
                    '</div>'+
                    '<div class="row">'+
                        '<div class="col-md-12">'+
                            '<h5>Ближайшая станция метро</h5>'+
                            nearest_metro+
                        '</div>'+
                    '</div>'+
                '</div>'+
                '</div>';
                if(role=='Работодатель'){
                    let favorite_resume=false;
                    let inviter=false;
                    if(data[0].is_favorite){
                        var button_favorite='<button id="btn_favorite_resume" type="button" class="btn btn-danger">В избранном</button>';
                    }
                    else{
                        var button_favorite='<button id="btn_favorite_resume" type="button" class="btn btn-outline-danger">В избранное</button>';
                    }
                    var invite='';
                    if(data[0].emploer){
                        data[0].emploer.forEach(element => {
                            invite=element.status;
                        });
                    }
                    var button_add='<button id="btn_add_resume" type="button" class="btn btn-outline-danger">Пригласить на собеседование</button>';
                    switch(invite){
                        case 'Приглашён':{
                            var button_add='<button id="btn_add_resume" type="button" class="btn btn-danger">Закрепить</button>';
                        }break;
                        case 'Закреплён':{
                            var button_add='<button id="btn_add_resume" type="button" class="btn btn-warning">Открепить от организации</button>';
                        }break;
                    }

                }
                else{
                    button_favorite='';
                    button_add='';
                }
                switch(type){
                    case 1:{
                        var button_close='<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>';
                        var footer=button_favorite+button_add+button_close;
                        $("#resume_modal_footer").html(footer);

                    }break;
                    case 2:{
                        var button_close='<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>';
                        var footer=button_add+button_close;
                        $("#resume_modal_footer").html(footer);
                    }break;
                }
                if(role=='Работодатель'){
                    $("#btn_favorite_resume").click(function(){
                        const btn = document.getElementById('btn_favorite_resume');
                        if(btn.innerHTML=='В избранное'){
                            btn.className='btn btn-danger';
                            btn.innerHTML='В избранном';
                        }
                        else{
                            btn.className='btn btn-outline-danger';
                            btn.innerHTML='В избранное';
                        }
                        ajaxCRUD(2,'/StudentsInfo/add_favorite_resume','ResumeModal',null,data[0].user_id,null,'Успешное выполнение операции!');
                    });
                    $("#btn_add_resume").click(function(){
                        ajaxCRUD(2,'/StudentsInfo/student_org_set_status','ResumeModal',null,data[0].user_id,null,'Успешное выполнение операции!');
                    });
                }
                $("#resume_modal_title").html('Просмотр резюме студента "'+fullname+'"');
                $("#resume_modal_body").html(html);
                $("#ResumeModal").modal('show');
            }
            else{
                $.toast({
                    heading: 'Ошибка',
                    text: 'Нет данных о резюме!',
                    hideAfter: 3000,
                    position: 'top-right',
                    stack: false,
                    showHideTransition: 'slide',
                    icon: 'error'
                });
            }
        },
        error: function(response){
            console.log('Ошибка при выполнении запроса');
        }
    });
   
}