var specialization;
var group;
$( document ).ready(function() {
    specialization=$('#specialization').select2({
        placeholder: "Выберите специальность",
        theme: 'bootstrap-5',
        width: '100%',
        "language": "ru"
    });
    group=$('#groups').select2({
        placeholder: "Выберите группу",
        theme: 'bootstrap-5',
        width: '100%',
        "language": "ru"
    });
    load_specializations();
    //alert($("#specialization").val());
    load_groups(document.getElementById("specialization").value);
    $('#specialization').on('change', function() {
        load_groups(document.getElementById("specialization").value);
    })
});

function load_specializations(){
    $.ajax({
        url: "/GroupsSpecializationsCrud/get_specialization_list",
        type: "POST",
        dataType:"json",
        success: function(data){
            if(data.length>0){
                var options='';
                $("#specialization").html("");
                data.forEach(function(item, i, data) {
                    options=options+'<option value="'+item.id+'">'+item.name+'</option>';
                });
                $("#specialization").append(options);
                $("#specialization").val(data[0].id).trigger("change");
                if(select_spec){
                    $("#specialization").val(select_spec);
                    $('#specialization').trigger('change'); // Notify any JS components that the value changed
                }
            }
            else{
                var options='';
                $("#specialization").html("<option value=''>Специальностей нет</option>");
            }
        },
        error: function(response){
            console.log('Ошибка при выполнении запроса получения списка специальностей');
        }
    });
   
}

function load_groups(id) {
    $.ajax({
        url: "/GroupsSpecializationsCrud/get_groups_list",
        type: "POST",
        dataType:"json",
        data:{specialization_id:id,type:type_select},
        success: function(data){
            $('#groups').html('');
            const list=[];
            if(data.length>0){
                var options='';                
                data.forEach(function(item, i, data) {
                    options=options+'<option value="'+item.id+'">'+item.name+'</option>';
                });
                $("#groups").html(options);
                
                if(select_group){
                    $("#groups").val(select_group);
                    $('#groups').trigger('change'); // Notify any JS components that the value changed
                }
            }
            else{
                var options='';
                $("#groups").html(options);
            }
        },
        error: function(response){
            console.log('Ошибка при выполнении запроса получения групп');
        }
    });
}