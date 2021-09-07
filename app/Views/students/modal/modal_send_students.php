<div class="modal fade" id="Modal_Send_Studs" tabindex="-1" aria-labelledby="Modal_Send_Studs_Label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="Modal_Send_Studs_Label">Перевод в другую группу</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="send_studs" name="send_studs">
                    <div id="content" class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="students" class="form-label">Студенты</label>
                                    <select id="students" name="students[]" multiple="multiple" class="form-select">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="specialization" class="form-label">Специальность</label>
                                    <select id="specialization" name="specialization" class="form-select">
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="groups" class="form-label">В группу</label>
                                    <select id="groups" name="groups" class="form-select">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div id="errors_transfer" class="col-md-12 alert-danger">

                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="save_transfer" type="button" class="btn btn-outline-danger">Сохранить</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>