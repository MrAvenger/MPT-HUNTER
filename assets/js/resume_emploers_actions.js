var all_data=[];
var search_first='';
$( document ).ready(function() {
    jQuery.fn.exists = function() {
        return $(this).length;
    };
    loadData();
    setInterval(loadData, 500);
});

function loadData() {
    var row='';
    var search_val;
    $.ajax({
        url: "/StudentsInfo/get_all_info",
        type: "POST",
        data:{search:search_val},
        dataType:"json",
        success: function(data){
            if(data){
                switch(page){
                    case 1:{
                        var specializations=data.specializations;
                        var students=data.students;                    
                        if(JSON.stringify(all_data.specializations)!=JSON.stringify(data.specializations)){
                            var html_specializations='<div class="accordion" style="margin-bottom:60px;" id="accordionExample">';
                            console.log(specializations);
                            specializations.forEach(function(item_specialization, i, specializations) {
                                if(item_specialization.favorite_exists){
                                    html_specializations=html_specializations+
                                    '<div class="accordion-item">'+
                                        '<h2 class="accordion-header" id="headingOne">'+
                                            '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#gr-st-'+item_specialization.id+'" aria-expanded="false" aria-controls="gr-st-'+item_specialization.id+'">'+item_specialization.name+'</button>'+
                                            '<div class="col-md-12"><pre><i><h6 style="color:	#733fcc;">'+item_specialization.description+'</h6></i></pre></div>'+
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
                                }

                            });
                            html_specializations=html_specializations+'</div>';
                            if(html_specializations=='<div class="accordion" style="margin-bottom:60px;" id="accordionExample"></div>'){
                                html_specializations=
                                '<div class="alert alert-primary" role="alert">'+
                                    'У вас нет избранных резюме'+
                                '</div>';  
                            }
                            $("#list").html(html_specializations);
                            all_data.students=null;
                        }
                        if(JSON.stringify(all_data.students)!=JSON.stringify(data.students)){
                            
                            specializations.forEach(function(item_specialization, i, specializations){
                                var html_students='';
                                students.forEach(function(item_student, i, students) {
                                    if(item_student.favorite_resume){

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
                                                if(!in_add_org&&!in_org){
                                                    score=item_student.average_score;
                                                    html_students=html_students+
                                                    '<div class="card-group">'+
                                                        '<div class="card" >'+
                                                            '<img src="'+item_student.photo+'" style="max-height:200px;" class="card-img-top" alt="...">'+
                                                            '<div class="card-body">'+
                                                                '<h6 class="card-title">'+item_student.last_name+' '+item_student.first_name+' '+item_student.middle_name+'</h6>'+
                                                                '<h6 class="text-muted">Средний балл: '+score+'</h6>'+
                                                                '<button type="button" onclick="load_resume(1,'+item_student.id+')" class="btn btn-outline-danger mx-1">Резюме</button><button type="button" onclick="load_portfolio('+item_student.id+')" class="btn btn-outline-danger mx-1">Портфолио</button>'+
                                                            '</div>'+
                                                        '</div>'+
                                                    '</div>';
                                                }
    
                                            }                               
                                            
                                        }
                                    }
                                    
                                });
                                if(html_students==''){
                                    html_students=
                                    '<div class="alert alert-primary" role="alert">'+
                                        'У вас нет избранных резюме'+
                                    '</div>';                        
                                    $("#gr-st-data-"+item_specialization.id).html(html_students);           
                                }
                                else{
                                    specializations.forEach(function(item_specialization, i, specializations){

                                    });
                                    $("#gr-st-data-"+item_specialization.id).html(html_students);
                                }
                                
                                
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
                                if(item_specialization.invitation_exists){
                                    html_specializations=html_specializations+
                                    '<div class="accordion-item">'+
                                        '<h2 class="accordion-header" id="headingOne">'+
                                            '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#gr-st-'+item_specialization.id+'" aria-expanded="false" aria-controls="gr-st-'+item_specialization.id+'">'+item_specialization.name+'</button>'+
                                            '<div class="col-md-12"><pre><i><h6 style="color:	#733fcc;">'+item_specialization.description+'</h6></i></pre></div>'+
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
                                }

                            });
                            html_specializations=html_specializations+'</div>';
                            if(html_specializations=='<div class="accordion" style="margin-bottom:60px;" id="accordionExample"></div>'){
                                html_specializations=
                                '<div class="alert alert-primary" role="alert">'+
                                    'У вас нет приглашённых и прикреплённых к вам студентов!'+
                                '</div>';  
                            }
                            $("#list").html(html_specializations);
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
                                            var status_text='';
                                            emploer.forEach(element => {
                                                if(element.user_id=item_student.id){
                                                    if(element.status=="Приглашён"&&element.organization_id==org_id){
                                                        in_add_org=true;
                                                        status_text=
                                                        '<div class="alert alert-danger" role="alert">'+
                                                            'Студент приглашён на собеседование!'+
                                                        '</div>';
                                                    }
                                                    if(element.status=="Закреплён"&&element.organization_id==org_id){
                                                        in_org=true
                                                        status_text=
                                                        '<div class="alert alert-warning" role="alert">'+
                                                            'Студент закреплён к вам!'+
                                                        '</div>';
                                                    }
                                                }

                                            });
                                            if(in_org||in_add_org){
                                                score=item_student.average_score;
                                                html_students=html_students+
                                                '<div class="card-group">'+
                                                    '<div class="card" >'+
                                                        '<img src="'+item_student.photo+'" style="max-height:200px;" class="card-img-top" alt="...">'+
                                                        '<div class="card-body">'+
                                                            '<h6 class="card-title">'+item_student.last_name+' '+item_student.first_name+' '+item_student.middle_name+'</h6>'+
                                                            '<h6 class="text-muted">Средний балл: '+score+'</h6>'+
                                                            status_text+
                                                            '<button id="btn_load_resume_gr_st-'+item_student.id+'" type="button" class="btn btn-outline-danger mx-1">Резюме</button><button id="btn_load_portfolio_gr_st-'+item_student.id+'" type="button" class="btn btn-outline-danger mx-1">Портфолио</button><button id="btn_delete_gr_st-'+item_student.id+'" type="button" class="btn btn-outline-dark mx-1">Удалить</button>'+
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
                                        'У вас нет избранных резюме'+
                                    '</div>';                        
                                    $("#gr-st-data-"+item_specialization.id).html(html_students);           
                                }
                                else{
                                    $("#gr-st-data-"+item_specialization.id).html(html_students);
                                    students.forEach(item_student => {
                                        if(item_specialization.id==item_student.specialization_id){
                                            $("#btn_load_resume_gr_st-"+item_student.id).click(function(){
                                                load_resume(page,item_student.id)
                                            });
                                            $("#btn_load_portfolio_gr_st-"+item_student.id).click(function(){
                                                load_portfolio(item_student.id)
                                            });
                                            
                                            $("#btn_delete_gr_st-"+item_student.id).click(function(){
                                                ajaxCRUD(2,'/StudentsInfo/delete_invite',null,null,item_student.id,null,'Успешное удаление!');
                                            });
                                        }
                                    });
                                }
                                
                                
                            });
                            
                        }
        
                        all_data=data;
                    }break
                }
            }
            else{
                $("#list").html('');
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