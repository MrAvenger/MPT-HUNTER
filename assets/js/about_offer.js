function offer_info(id){
    $.ajax({
        url: "offerCrud/get",
        type: "POST",
        data:{id:id},
        dataType:"json",
        success: function(data){
            var offer=data.offer;
            var requirements=data.requirements;
            var org_info=data.org;
            var salary='';
            $("#offer_name").html(offer.offer_name);
            $("#employment").html(offer.employment);
            //document.getElementById('offer_description').value = offer.offer_description;
            if(!data.salary||data.salary<=0){
                salary="Не предусмотрено";
            }
            else{
                salary=data.salary+" руб";
            }
            $("#salary").html(salary);
            $("#offer_description").html(offer.offer_description);
            var requirements_html='';
            requirements.forEach(function(item, i, requirements) {
                requirements_html=requirements_html+'<li>'+item.name+'</li>';
            });
            $("#requirements").html(requirements_html);
            $("#org_name").html(org_info.org_name);
            $("#org_adress").html(org_info.org_adress);
            $("#org_description").html(org_info.org_description);
            console.log('Просмотр информации о вакансии');
        },
        error: function(response){
            console.log('Ошибка при выполнении запроса');
        }
    });
}