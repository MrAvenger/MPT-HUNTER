$( document ).ready(function() {
    $("#btn_main_skill").click(function(){
      $("#skills_row").append('<div class="col-md-7 col-sm-12 mt-1 input-group"><input class="form-control" type="text" name="skills[]"/><a class="btn btn-secondary">Удалить</a></div>');
      $("#skills_row").find("a").unbind("click");
      $("#skills_row").find("a").click(function(){
      $(this).parent().remove();      
      });      
    });
    $('#groups').select2({
        placeholder: "Выберите группы",
        theme: 'bootstrap-5',
        width: '100%',
        // dropdownParent: $('#CRU_Main'),
        "language": "ru"
    });     
    jQuery.fn.exists = function() {
        return $(this).length;
    };
    load_groups_list(groups);
    $("#description").html(specialization.description);
    if(skills){
        skills.forEach(function(item, i, skills) {
            $("#skills_row").append('<div class="col-md-7 col-sm-12 mt-1 input-group"><input class="form-control" type="text" name="skills[]" value="'+escapeHtml(item.name)+'"/><a class="btn btn-secondary">Удалить</a></div>');
            $("#skills_row").find("a").unbind("click");
            $("#skills_row").find("a").click(function(){
            $(this).parent().remove();      
            }); 
        });
    }
    $("#btn_save").click(function(){
        ajaxCRUD(1,'/GroupsSpecializationsCrud/edit/'+specialization.id,null,'form_spec',null,'errors','Успешное изменение!');
    });
});

function load_groups_list(my_groups) {
    $.ajax({
        url: "/GroupsSpecializationsCrud/get_groups_list",
        type: "POST",
        dataType:"json",
        data:{type:type_select},
        success: function(data){
            const list=[];
            $('#groups').val(null).trigger('change');
            
            if(data.length>0){
                console.log(groups);
                var options='';
                $("#groups").html("");
                data.forEach(function(item, i, data) {
                    options=options+'<option value="'+item.id+'">'+item.name+'</option>';
                });
                $("#groups").html(options);
                
                if(my_groups){
                    my_groups.forEach(function(item, i, my_groups) {                  
                        list.push(item.group_id);
                    });
                    $("#groups").val(list).trigger('change');
                }
                else{
                    //console.log(groups);
                }
            }
            else{
                //console.log('Нет групп');
            }
        },
        error: function(response){
            console.log('Ошибка при выполнении запроса');
        }
    });
}
function escapeHtml(text) {
    var map = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#039;'
    };
    
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}