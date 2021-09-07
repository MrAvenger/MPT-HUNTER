var specializations=[];
var dateTable_Specialization;
$( document ).ready(function() {
    $("#add_field_specialization_name").click(function(){
      $("#specialization_row").append('<div class="col-md-7 col-sm-12 mt-1 input-group"><input class="form-control" type="text" name="specialization_name[]"/><a class="btn btn-secondary">Удалить</a></div>');
      $("#specialization_row").find("a").unbind("click");
      $("#specialization_row").find("a").click(function(){
      $(this).parent().remove();      
      });      
    });
    $("#btn_specialization").click(function(){
        add_open_specialization();
    });
    $("#edit_btn_specializations").click(function(){
        edit_open_specialization();
    });
    dateTable_Specialization=$('#specializations_table').DataTable({
        "oLanguage": {
            "sUrl": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Russian.json"
        },
        "ajax": {
            url : "/specializationCrud/get_all",
            type : 'POST'
        },
    });
    jQuery.fn.exists = function() {
        return $(this).length;
    };
    setInterval( function () {
        load_table_specializations();
    }, 2000 );
});

function load_table_specializations() {
    $.ajax({
        url: "/specializationCrud/get_all",
        type: "POST",
        dataType:"json",
        success: function(data){
            if(JSON.stringify(specializations)!=JSON.stringify(data)){
                specializations=data;
                dateTable_Specialization.ajax.reload();
            }
        },
        error: function(response){
            console.log('Ошибка при выполнении запроса');
        }
    });
}

function add_open_specialization(){
    const elem = document.getElementById("errors_specializations");
    elem.classList.remove("alert");
    $("#errors_specializations").html('');
    $("#specialization_row").html('');
    $("#specialization_row").append('<div class="col-md-7 col-sm-12 mt-1 input-group"><input class="form-control" type="text" name="specialization_name[]"/></div>');
    $("#specialization_footer").html('<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button><button id="btn_specialization_save" type="button" class="btn btn-primary">Сохранить</button>');
    $("#btn_specialization_save").click(function(){
        ajaxCRUD(1,'/specializationCrud/add','Cru_Specialization','specialization_form',null,'errors_specializations','Успешное добавление!');
    });
    $("#Cru_Specialization").modal('show');
}

function edit_open_specialization(){
    $.ajax({
        url: "/specializationCrud/get",
        type: "POST",
        dataType:"json",
        success: function(data){
            if(data.length>0){
                const elem = document.getElementById("errors_specializations");
                elem.classList.remove("alert");
                $("#errors_specializations").html('');
                $("#specialization_row").html('');
                data.forEach(function(item, i, data) {
                    $("#specialization_row").append('<div class="col-md-7 col-sm-12 mt-1 input-group"><input class="form-control" type="text" name="old_specialization_name['+item.id+']" value="'+escapeHtml(item.name)+'"/><a class="btn btn-secondary">Удалить</a></div>');
                    $("#specialization_row").find("a").unbind("click");
                    $("#specialization_row").find("a").click(function(){
                    $(this).parent().remove();      
                    }); 
                });
                $("#specialization_footer").html('<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button><button id="btn_specialization_edit" type="button" class="btn btn-primary">Изменить</button>');
                $("#btn_specialization_edit").click(function(){
                    ajaxCRUD(1,'/specializationCrud/edit','Cru_Specialization','specialization_form',null,'errors_specializations','Успешное изменение данных!');
                });
                $("#Cru_Specialization").modal('show');
            }
            else{
                $.toast({
                    heading: 'Список специальностей пуст',
                    text: 'Сначала добавьте хотя бы одну специальность!',
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
    $("#group_footer").html('<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button><button type="button" onclick="edit_group();" class="btn btn-primary">Изменить</button>');
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