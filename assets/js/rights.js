var rights=[];
let first_rights=0;
$(document).ready(function(){
    setInterval(function() {
        update_rights(first_rights);
    }, 1000);
});
function update_rights(type) {
    $.ajax({
        url: '/Authorization/update_rights',         /* Куда пойдет запрос */
        method: 'post',             /* Метод передачи (post или get) */
        dataType: 'json',          /* Тип данных в ответе (xml, json, script, html). */
        success: function(data){   /* функция которая будет выполнена после успешного запроса.  */
            switch(type){
                case 0:{
                    rights=data;
                    first_rights=1;
                }break;
                case 1:{
                    if(JSON.stringify(rights)!=JSON.stringify(data)){
                        window.location.reload();
                    }
                }break;
            }            /* В переменной data содержится ответ от index.php. */
        }
    });
}