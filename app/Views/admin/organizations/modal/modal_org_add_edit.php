<!-- Modal -->
<div class="modal fade" id="Modal_Org_CRU" data-bs-backdrop="static" aria-labelledby="modal_cru_title" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_cru_title">Управление организациями</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="org_data" name="org_data" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="content">
                        <div class="container">
                            <div class="row my-2 g-3">
                                <div class="col-md-6 col-sm-12 my-1">
                                    <div class="mb-3">
                                        <label for="org_name" class="form-label">Название
                                            организации</label>
                                        <input class="form-control" id="org_name" name="org_name" type="text"
                                            aria-label="">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 my-1">
                                    <div class="mb-3">
                                        <label for="user" class="form-label">Представитель (<i class="text-danger">Работодатель</i>)</label>
                                        <select id="user" name="user" class="form-select">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row my-2 g-3">
                                <div class="col-md-6 col-sm-12 my-1">
                                    <div class="mb-3">
                                        <label for="post" class="form-label">Должность</label>
                                        <input class="form-control" id="post" name="post" type="text" aria-label="">
                                    </div>
                                </div>
                            </div>
                            <div class="row my-2 g-3">
                                <div class="col-md-6 col-sm-12 my-1">
                                    <div class="mb-3">
                                        <label for="org_adress" class="form-label">Адрес организации</label>
                                        <input class="form-control" id="org_adress" name="org_adress" type="text"
                                            aria-label="">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 my-1">
                                    <div class="mb-3">
                                        <label for="formFile" class="form-label">Новое фото
                                            организации</label>
                                        <input class="form-control" type="file" id="org_photo" name="org_photo">
                                    </div>
                                </div>
                            </div>
                            <div class="row my-2 g-3">
                                <div class="col-md-12 col-sm-12 my-1">
                                    <div class="mb-3">
                                        <label for="formFile" class="form-label">Описание
                                            организации</label>
                                        <textarea class="form-control" id="org_description" name="org_description"
                                            rows="3"></textarea>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div id="errors" class="col-md-12 alert-danger">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="modal_cru_footer" class="modal-footer">
                    <input id="id_org" name="id_org" type="hidden">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>