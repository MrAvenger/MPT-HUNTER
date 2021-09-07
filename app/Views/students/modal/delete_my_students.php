<div class="modal fade" id="Model_Students_Delete" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="Model_Students_DeleteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="Model_Students_DeleteLabel">Отчисление студентов</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="delete_form">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="students_delete" class="form-label">Студенты</label>
                                    <select id="students_delete" name="students_delete[]" multiple="multiple" class="form-select">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div id="errors_delete_students" class="col-md-12 alert-danger">

                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                <button id="btn_delete_students" type="button" class="btn btn-danger">Отчислить</button>
            </div>
        </div>
    </div>
</div>