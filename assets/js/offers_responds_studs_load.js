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
    $.ajax({
        url: "/StudentsInfo/get_offers_responds",
        type: "POST",
        dataType:"json",
        success: function(data){
            if(data){
                var offers=data.offers;
                var students=data.students;                   
                if(JSON.stringify(all_data.offers)!=JSON.stringify(data.offers)){
                    var html_offers='<div class="accordion" style="margin-bottom:60px;" id="accordionExample">';
                    offers.forEach(function(item_offer, i, offers) {
                        html_offers=html_offers+
                        '<div class="accordion-item">'+
                            '<h2 class="accordion-header" id="headingOne">'+
                                '<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#of-rs-st-'+item_offer.id+'" aria-expanded="false" aria-controls="of-rs-st-'+item_offer.id+'">'+item_offer.offer_name+'</button>'+
                                //'<div class="col-md-12"><pre><i><h6 style="color:	#733fcc;">'+item_offer.description+'</h6></i></pre></div>'+
                                '</h2>'+
                            '<div id="of-rs-st-'+item_offer.id+'" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">'+
                                '<div class="accordion-body">'+
                                    '<form id="form-of-rs-st-'+item_offer.id+'">'+
                                        '<div id="of-rs-st-data-'+item_offer.id+'" class="row row-cols-1 row-cols-md-4 g-4">'+
                                    '</form>'+
                                '</div>'+
                                '<div id="errors_gr_st_'+item_offer.id+'" class="row alert-danger mt-1"></div>'+
                            '</div>'+
                        '</div>';

                    });
                    html_offers=html_offers+'</div>';
                    if(html_offers=='<div class="accordion" style="margin-bottom:60px;" id="accordionExample"></div>'){
                        html_offers=
                        '<div class="alert alert-primary" role="alert">'+
                            'У вас нет созданных предложений!'+
                        '</div>';  
                    }
                    $("#list").html(html_offers);
                    all_data.students=null;
                }
                if(JSON.stringify(all_data.students)!=JSON.stringify(data.students)){
                    console.log(1);
                    offers.forEach(function(item_offer, i, offers){
                        var html_students='';
                        students.forEach(function(item_student, i, students) {
                            console.log(item_student);
                            if(item_offer.id==item_student.offer_id){
                                console.log('Есть');
                                score=item_student.average_score;
                                html_students=html_students+
                                '<div class="card-group">'+
                                    '<div class="card" >'+
                                        '<img src="'+item_student.photo+'" style="max-height:200px;" class="card-img-top" alt="...">'+
                                        '<div class="card-body">'+
                                            '<h6 class="card-title">'+item_student.last_name+' '+item_student.first_name+' '+item_student.middle_name+'</h6>'+
                                            '<h6 class="text-muted">Средний балл: '+score+'</h6>'+
                                            //status_text+
                                            '<button id="btn_load_resume_gr_st-'+item_student.id+'" type="button" class="btn btn-outline-danger mx-1">Резюме</button><button id="btn_load_portfolio_gr_st-'+item_student.id+'" type="button" class="btn btn-outline-danger mx-1">Портфолио</button>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>';                             
                                
                            }
                            else{
                                // console.log(item_student.offer_id);
                                // console.log(item_offer.id);
                            }
                            
                        });
                        if(html_students==''){
                            html_students=
                            '<div class="alert alert-primary" role="alert">'+
                                'На данную вакансию ещё никто не откликнулся'+
                            '</div>';                        
                            $("#of-rs-st-data-"+item_offer.id).html(html_students);           
                        }
                        else{
                            $("#of-rs-st-data-"+item_offer.id).html(html_students);
                            students.forEach(item_student => {
                                if(item_offer.id==item_student.offer_id){
                                    $("#btn_load_resume_gr_st-"+item_student.id).click(function(){
                                        console.log(item_student.id);
                                        load_resume(page,item_student.id)
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