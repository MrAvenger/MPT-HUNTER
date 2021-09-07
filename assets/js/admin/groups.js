var groups=[];
var dateTable_Group;
$( document ).ready(function() {
    $("#add_field_group_name").click(function(){
      $("#group_row").append('<div class="col-md-7 col-sm-12 mt-1 input-group"><input class="form-control" type="text" name="group_name[]"/><a class="btn btn-secondary">Удалить</a></div>');
      $("#group_row").find("a").unbind("click");
      $("#group_row").find("a").click(function(){
      $(this).parent().remove();      
      });      
    });
    $("#btn_group").click(function(){
        add_open_group();
    });
    $("#edit_btn_groups").click(function(){
        edit_open_group();
    });
    dateTable_Group=$('#groups_table').DataTable({
        "oLanguage": {
            "sUrl": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Russian.json"
        },
        "ajax": {
            url : "/groupCrud/get_all",
            type : 'POST'
        },
    });
    jQuery.fn.exists = function() {
        return $(this).length;
    };
    setInterval( function () {
        load_table_groups();
    }, 1000 );
});

function load_table_groups() {
    $.ajax({
        url: "/groupCrud/get_all",
        type: "POST",
        dataType:"json",
        success: function(data){
            if(JSON.stringify(groups)!=JSON.stringify(data)){
                groups=data;
                dateTable_Group.ajax.reload();
            }
        },
        error: function(response){
            console.log('Ошибка при выполнении запроса');
        }
    });
}

function add_open_group(){
    const elem = document.getElementById("errors_groups");
    elem.classList.remove("alert");
    $("#errors_groups").html('');
    $("#group_row").html('');
    $("#group_row").append('<div class="col-md-7 col-sm-12 mt-1 input-group"><input class="form-control" type="text" name="group_name[]"/></div>');
    $("#group_footer").html('<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button><button id="btn_group_save" type="button" class="btn btn-primary">Сохранить</button>');
    $("#btn_group_save").click(function(){
        ajaxCRUD(1,'/groupCrud/add','Cru_Group','group_form',null,'errors_groups','Успешное добавление!');
    });
}

function edit_open_group(){
    $.ajax({
        url: "/groupCrud/get",
        type: "POST",
        dataType:"json",
        success: function(data){
            if(data.length>0){
                const elem = document.getElementById("errors_groups");
                elem.classList.remove("alert");
                $("#errors_groups").html('');
                $("#group_row").html('');
                data.forEach(function(item, i, data) {
                    $("#group_row").append('<div class="col-md-7 col-sm-12 mt-1 input-group"><input class="form-control" type="text" name="old_group_name['+item.id+']" value="'+item.name+'"/><a class="btn btn-secondary">Удалить</a></div>');
                    $("#group_row").find("a").unbind("click");
                    $("#group_row").find("a").click(function(){
                    $(this).parent().remove();      
                    }); 
                });
                $("#group_footer").html('<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button><button id="btn_group_edit" type="button" class="btn btn-primary">Изменить</button>');
                $("#btn_group_edit").click(function(){
                    ajaxCRUD(1,'/groupCrud/edit','Cru_Group','group_form',null,'errors_groups','Успешное изменение данных!');
                });
                $("#Cru_Group").modal('show');
            }
            else{
                $.toast({
                    heading: 'Список групп пуст',
                    text: 'Сначала добавьте хотя бы одну группу!',
                    hideAfter: 10000,
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