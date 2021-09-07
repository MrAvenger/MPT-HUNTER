$( document ).ready(function(){
    $("#add_field_group_name").click(function(){
        $("#group_row").append('<div class="col-md-7 col-sm-12 mt-1 input-group"><input class="form-control" type="text" name="group_name[]"/><a class="btn btn-secondary">Удалить</a></div>');
        $("#group_row").find("a").unbind("click");
        $("#group_row").find("a").click(function(){
        $(this).parent().remove();      
        });      
    });
      $("#btn_add_open").click(function(){
          add_open_group();
      });
      $("#btn_edit_open").click(function(){
          edit_open_group();
      });
});

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