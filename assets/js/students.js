var all_data=[];
$( document ).ready(function() {
    setInterval(function() {
        switch(role){
            case 'Куратор':{
                load_data(1);
            }break;
            case 'Работодатель':{
                load_data(2);
            }break;
        }
    }, 1000);
    if(role=='Куратор'){
        $('#students').select2({
            placeholder: "Выберите студента/ов",
            theme: 'bootstrap-5',
            width: '100%',
            "language": "ru",
            dropdownParent: $('#Modal_Send_Studs')
        });
        $('#students_delete').select2({
            placeholder: "Выберите студента/ов",
            theme: 'bootstrap-5',
            width: '100%',
            "language": "ru",
            dropdownParent: $('#Model_Students_Delete')
        });
        $('#specialization').select2({
            placeholder: "Выберите группу",
            theme: 'bootstrap-5',
            width: '100%',
            "language": "ru",
            dropdownParent: $('#Modal_Send_Studs')
        });
        $('#groups').select2({
            placeholder: "Выберите группу",
            theme: 'bootstrap-5',
            width: '100%',
            "language": "ru",
            dropdownParent: $('#Modal_Send_Studs')
        });
        load_groups(document.getElementById("specialization").value);
    
        $('#transfer_open').on('click', function() {
            const elem = document.getElementById('errors_transfer');
            elem.classList.remove("alert");
            $("#errors_transfer").html('');
            load_specializations();
            loadStuds(1);
            $("#students").val(null).trigger('change');
            load_groups($("#specialization").value);
            
        })
        $('#btn_delete_open').on('click', function() {
            const elem = document.getElementById('errors_delete_students');
            elem.classList.remove("alert");
            $("#errors_delete_students").html('');
            load_specializations();
            loadStuds(2);
            $("#students_delete").val(null).trigger('change');            
        })
        $("#save_transfer").click(function(){
            ajaxCRUD(1,'/StudentsInfo/transfer_students',"Modal_Send_Studs",'send_studs',null,'errors_transfer','Успешный перевод студентов!');
        });
        $("#btn_delete_students").click(function(){
            ajaxCRUD(1,'/StudentsInfo/student_expulsions',"Model_Students_Delete",'delete_form',null,'errors_delete_students','Студенты успешно отчислены!');
        });
    }    
});

function load_data(type){
    $.ajax({
        url: "/StudentsInfo/get_all_info",
        type: "POST",
        dataType:"json",
        success: function(data){
            if(data){
                switch(type){
                    case 1:{
                        var groups=data.groups;
                        var students=data.students;                    
                        if(JSON.stringify(all_data.groups)!=JSON.stringify(data.groups)){
                            var html_groups='<div class="accordion" style="margin-bottom:60px;" id="accordionExample">';
                            groups.forEach(function(item_group, i, groups) {
                                //var onclick='<button type="button" class="btn btn-outline-danger mt-3" onclick="'+"ajaxCRUD(1,'/StudentsInfo/update_score',null,'form-gr-st-"+item_group.id+"',null,'errors_gr_st_"+item_group.id+"','Успешное сохранение данных о средних баллах!');"+'">Сохранить средние баллы</button>';
                                var onclick='<button id="btn_save_score_gr_st-'+item_group.id+'" type="button" class="btn btn-outline-danger mt-3"">Сохранить средние баллы</button>';
                                
                                html_groups=html_groups+
                                    '<div class="accordion-item">'+
                                        '<h2 class="accordion-header" id="headingOne">'+
                                            '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#gr-st-'+item_group.id+'" aria-expanded="false" aria-controls="gr-st-'+item_group.id+'">'+item_group.name+'</button>'+
                                        '</h2>'+
                                        '<div id="gr-st-'+item_group.id+'" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">'+
                                            '<div class="accordion-body">'+
                                                '<form id="form-gr-st-'+item_group.id+'">'+
                                                    '<div id="gr-st-data-'+item_group.id+'" class="row row-cols-1 row-cols-md-4 g-4">'+
                                                '</form>'+
                                            '</div>'+
                                            '<div id="div_btn_'+item_group.id+'" class="d-flex justify-content-end">'+onclick+'</div>'+
                                            '<div id="errors_gr_st_'+item_group.id+'" class="row alert-danger mt-1"></div>'+
                                        '</div>'+
                                    '</div>';
                            });
                            html_groups=html_groups+'</div>';
        
                            $("#data_list").html(html_groups);
                            groups.forEach(item_group => {
                                $("#btn_save_score_gr_st-"+item_group.id).click(function(){
                                    ajaxCRUD(1,'/StudentsInfo/update_score',null,'form-gr-st-'+item_group.id,null,'errors_gr_st_'+item_group.id,'Успешное сохранение данных о средних баллах!');
                                });
                            });

                            all_data.students=null;
                        }
                        if(JSON.stringify(all_data.students)!=JSON.stringify(data.students)){
                            let not_studs=false;
                            groups.forEach(function(item_group, i, groups){
                                var html_students='';
                                students.forEach(function(item_student, i, students) {
                                    if(item_group.id==item_student.group_id){
                                        let score='';   
                                        if(item_student.average_score){
                                            score=item_student.average_score;
                                        }                               
                                        html_students=html_students+
                                        '<div class="card-group">'+
                                            '<div class="card">'+
                                                '<img src="'+item_student.photo+'" class="card-img-top img-fluid" alt="...">'+
                                                '<div class="card-body">'+
                                                    '<h6 class="card-title">'+item_student.last_name+' '+item_student.first_name+' '+item_student.middle_name+'('+item_student.email+')</h6>'+
                                                    '<h6>Средний балл:</h6>'+
                                                    '<input name="score['+item_student.id+']" type="number" class="mb-1" style="max-width:50px;" min="3" max="5" value="'+score+'"><br>'+
                                                    '<button id="btn_load_resume_gr_st-'+item_student.id+'" type="button" class="btn btn-outline-danger mx-1">Резюме</button><button id="btn_load_portfolio_gr_st-'+item_student.id+'" type="button" class="btn btn-outline-danger mx-1">Портфолио</button>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>';
                                        
                                    }
                                });
                                if(html_students==''){
                                    html_students=
                                    '<div class="alert alert-primary" role="alert">'+
                                        'Пока что здесь нет информации о студентах!'+
                                    '</div>';                        
                                    $("#gr-st-data-"+item_group.id).html(html_students);           
                                }
                                $("#gr-st-data-"+item_group.id).html(html_students);
                                students.forEach(item_student => {
                                    if(item_group.id==item_student.group_id){
                                        $("#btn_load_resume_gr_st-"+item_student.id).click(function(){
                                            load_resume(1,item_student.id)
                                        });
                                        $("#btn_load_portfolio_gr_st-"+item_student.id).click(function(){
                                            load_portfolio(item_student.id)
                                        });
                                    }
                                });
                            });
                            
                        }
        
                        all_data=data;
                    }break;
                    case 2:{
                        var specializations=data.specializations;
                        var students=data.students;                    
                        if(JSON.stringify(all_data.specializations)!=JSON.stringify(data.specializations)){
                            var html_specializations='<div class="accordion" style="margin-bottom:60px;" id="accordionExample">';
                            specializations.forEach(function(item_specialization, i, specializations) {
                                var desc='';
                                if(item_specialization.description){
                                    desc=item_specialization.description;
                                }
                                else{
                                    desc='Нет описания';
                                }
                                //var onclick='<button type="button" class="btn btn-outline-danger" onclick="'+"ajaxCRUD(1,'/StudentsInfo/update_score',null,'form-gr-st-"+item_group.id+"',null,'errors_gr_st_"+item_group.id+"','Успешное сохранение данных о средних баллах!');"+'">Сохранить средние баллы</button>';
                                
                                html_specializations=html_specializations+
                                    '<div class="accordion-item">'+
                                        '<h2 class="accordion-header" id="headingOne">'+
                                            '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#gr-st-'+item_specialization.id+'" aria-expanded="false" aria-controls="gr-st-'+item_specialization.id+'">'+item_specialization.name+'</button>'+
                                            '<div class="col-md-12"><pre><i><h6 style="color:	#733fcc;">'+desc+'</h6></i></pre></div>'+
                                            '</h2>'+
                                        '<div id="gr-st-'+item_specialization.id+'" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">'+
                                            '<div class="accordion-body">'+
                                                '<form id="form-gr-st-'+item_specialization.id+'">'+
                                                    '<div id="gr-st-data-'+item_specialization.id+'" class="row row-cols-1 row-cols-md-4 g-4">'+
                                                '</form>'+
                                            '</div>'+
                                            '<div id="errors_gr_st_'+item_specialization.id+'" class="row alert-danger mt-1"></div>'+
                                        '</div>'+
                                    '</div>';
                            });
                            html_specializations=html_specializations+'</div>';
        
                            $("#data_list").html(html_specializations);
                            all_data.students=null;
                        }
                        if(JSON.stringify(all_data.students)!=JSON.stringify(data.students)){
                            
                            specializations.forEach(function(item_specialization, i, specializations){
                                var html_students='';
                                students.forEach(function(item_student, i, students) {
                                    if(item_specialization.id==item_student.specialization_id){
                                        let score='';   
                                        if(item_student.average_score){
                                            let in_org=false;
                                            let in_add_org=false;
                                            var emploer=item_student.emploer;
                                            emploer.forEach(element => {
                                                if(element.user_id=item_student.id){
                                                    if(element.status=="Приглашён"&&element.organization_id==org_id){
                                                        in_add_org=true;
                                                    }
                                                    if(element.status=="Закреплён"){
                                                        in_org=true
                                                    }
                                                }

                                            });
                                            if(!in_org&&!in_add_org){
                                                score=item_student.average_score;
                                                html_students=html_students+
                                                '<div class="card-group">'+
                                                    '<div class="card">'+
                                                        '<img src="'+item_student.photo+'" style="max-height:200px" class="card-img-top" alt="...">'+
                                                        '<div class="card-body">'+
                                                            '<h6 class="card-title">'+item_student.last_name+' '+item_student.first_name+' '+item_student.middle_name+'</h6>'+
                                                            '<h6 class="text-muted">Средний балл: '+score+'</h6>'+
                                                            '<button id="btn_load_resume_gr_st-'+item_student.id+'" type="button" class="btn btn-outline-danger mx-1">Резюме</button><button id="btn_load_portfolio_gr_st-'+item_student.id+'" type="button" class="btn btn-outline-danger mx-1">Портфолио</button>'+
                                                        '</div>'+
                                                    '</div>'+
                                                '</div>';
                                            }

                                        }                               
                                        
                                    }
                                });
                                if(html_students==''){
                                    html_students=
                                    '<div class="alert alert-primary" role="alert">'+
                                        'Пока что здесь нет информации о студентах, которые ищут работу! Посмотрите позже'+
                                    '</div>';                        
                                    $("#gr-st-data-"+item_specialization.id).html(html_students);           
                                }
                                else{
                                    $("#gr-st-data-"+item_specialization.id).html(html_students);
                                    students.forEach(item_student => {
                                        if(item_specialization.id==item_student.specialization_id){
                                            $("#btn_load_resume_gr_st-"+item_student.id).click(function(){
                                                load_resume(1,item_student.id)
                                            });
                                            $("#btn_load_portfolio_gr_st-"+item_student.id).click(function(){
                                                load_portfolio(item_student.id)
                                            });
                                        }
                                    });
                                }
                                
                                
                            });
                            
                        }
        
                        all_data=data;
                    }break;
                }
            }
            else{
                $("#data_list").html('');
            }
        },
        error: function(response){
            console.log('Ошибка при выполнении запроса');
        }
    });
   
}

function loadStuds(type) {
    $.ajax({
        url: "/StudentsInfo/get_students",
        type: "POST",
        dataType:"json",
        success: function(data){
            if(data.length>0){
                switch(type){
                    case 1:{
                        var options='';
                        $("#students").html("");
                        data.forEach(function(item, i, data) {
                            var group='';
                            if(item.group_id&&item.specialization_id){
                                group=item.group_name;
                            }
                            else if(!item.group_id&&item.specialization_id){
                                group='Без группы';
                            }
                            else if(!item.group_id&&!item.specialization_id){
                                group='Не прикреплён';
                            }
                            else if(item.group_id&&!item.specialization_id){
                                group=item.group_name+' без сп';
                            }
                            options=options+'<option value="'+item.id+'">'+item.last_name+' '+item.first_name+' '+item.middle_name+' ('+group+')</option>';
                        });
                        $("#students").append(options);
                    }break;
                    case 2:{
                        var options='';
                        $("#students").html("");
                        data.forEach(function(item, i, data) {
                            var group='';
                            if(item.group_id&&item.specialization_id){
                                group=item.group_name;
                            }
                            else if(!item.group_id&&item.specialization_id){
                                group='Без группы';
                            }
                            else if(!item.group_id&&!item.specialization_id){
                                group='Не прикреплён';
                            }
                            else if(item.group_id&&!item.specialization_id){
                                group=item.group_name+' без сп';
                            }

                            if(item.group_id||item.specialization_id){
                                options=options+'<option value="'+item.id+'">'+item.last_name+' '+item.first_name+' '+item.middle_name+' ('+group+')</option>';
                            }
                        });
                        $("#students_delete").append(options);
                    }break;
                }
            }
            else{
                var options='';
                $("#students").html("<option value=''>Доступных студентов нет</option>");
            }
        },
        error: function(response){
            console.log('Ошибка при выполнении запроса');
        }
    });
}