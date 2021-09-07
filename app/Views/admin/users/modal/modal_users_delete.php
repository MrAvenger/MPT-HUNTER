<!-- Modal -->
<div class="modal fade" id="Modal_User_Delete" data-bs-backdrop="static" aria-labelledby="modal_del_title"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_del_title">Удаление пользователя</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div id="delete_body" class="col-md-12">
                            <p>Вы действительно хотите удалить пользователя и все связанные с ним данные?</p>
                        </div>
                    </div>
                </div>
            </div>
            <div id="modal_delete_user_footer" class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                <button id="btn_delete_user" type="button" class="btn btn-primary">Удалить</button>
            </div>
        </div>
    </div>
</div>