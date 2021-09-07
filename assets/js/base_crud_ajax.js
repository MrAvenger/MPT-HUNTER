function ajaxCRUD(type,ajax_url,modal,serialize_form,id,div_error,success_text) {
    switch(type){
        //При сериализации форм
        case 1:{
            $.ajax({
                url: ajax_url,
                type: "POST",
                data:$('#'+serialize_form).serialize(),
                dataType:"json",
                success: function(data){
                    if(data==true){
                        $.toast({
                            heading: 'Успех!',
                            text: success_text,
                            hideAfter: 3000,
                            position: 'top-right',
                            stack: false,
                            showHideTransition: 'slide',
                            icon: 'success'
                        });
                        if(modal){
                            $("#"+modal).modal('hide');
                        }
                        if(div_error){
                            const elem = document.getElementById(div_error);
                            elem.classList.remove("alert");
                            $("#"+div_error).html('');
                        }
                        //document.getElementById("serialize_form").reset();
                    }
                    else if(data.error){
                        $.toast({
                            heading: 'Ошибка',
                            text: data.error,
                            hideAfter: 5000,
                            position: 'top-right',
                            stack: false,
                            showHideTransition: 'slide',
                            icon: 'error'
                        })
                    }
                    else{
                        if(div_error){
                            const elem = document.getElementById(div_error);
                            elem.classList.add("alert");
                            $("#"+div_error).html(data.validation);
                        }        
                    }
                },
                error: function(response){
                    $.toast({
                        heading: 'Ошибка',
                        text: 'Ошибка при выполнении операции!',
                        hideAfter: 10000,
                        position: 'top-right',
                        stack: false,
                        showHideTransition: 'slide',
                        icon: 'error'
                    }); 
                }
            });
        }break;
        //При передаче одиночного значения
        case 2:{
            $.ajax({
                url: ajax_url,
                type: "POST",
                data:{id:id},
                dataType:"json",
                success: function(data){
                    if(data==true){
                        $.toast({
                            heading: 'Успех!',
                            text: success_text,
                            hideAfter: 3000,
                            position: 'top-right',
                            stack: false,
                            showHideTransition: 'slide',
                            icon: 'success'
                        })
                        if(modal){
                            $("#"+modal).modal('hide');
                        }
                    }
                    else if(data.error){
                        $.toast({
                            heading: 'Ошибка',
                            text: data.error,
                            hideAfter: 5000,
                            position: 'top-right',
                            stack: false,
                            showHideTransition: 'slide',
                            icon: 'error'
                        })
                    }
                    else{
                        if(div_error){
                            const elem = document.getElementById(div_error);
                            elem.classList.add("alert");
                            $("#"+div_error).html(data.validation);
                        }
        
                    }
                },
                error: function(response){
                    $.toast({
                        heading: 'Ошибка',
                        text: 'Ошибка при выполнении операции!',
                        hideAfter: 10000,
                        position: 'top-right',
                        stack: false,
                        showHideTransition: 'slide',
                        icon: 'error'
                    }); 
                }
            });
        }break;
        case 3:{
            $.ajax({
                url: ajax_url,
                type: "POST",
                data:new FormData(serialize_form),
                dataType:"json",
                success: function(data){
                    if(data==true){
                        $.toast({
                            heading: 'Успех!',
                            text: success_text,
                            hideAfter: 3000,
                            position: 'top-right',
                            stack: false,
                            showHideTransition: 'slide',
                            icon: 'success'
                        })
                        if(modal){
                            $("#"+modal).modal('hide');
                        }
                        const elem = document.getElementById(div_error);
                        elem.classList.remove("alert");
                        $("#"+div_error).html('');
                        document.getElementById("serialize_form").reset();
                    }
                    else{
                        const elem = document.getElementById(div_error);
                        elem.classList.add("alert");
                        $("#"+div_error).html(data.validation);
        
                    }
                },
                error: function(response){
                    $.toast({
                        heading: 'Ошибка',
                        text: 'Ошибка при выполнении операции!',
                        hideAfter: 10000,
                        position: 'top-right',
                        stack: false,
                        showHideTransition: 'slide',
                        icon: 'error'
                    }); 
                }
            });
        }break;
    }
    
}