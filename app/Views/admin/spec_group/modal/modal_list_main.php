<!-- Modal -->
<div class="modal fade" id="Modal_List_View" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modal_list_title"
    aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_list_title">Управление списками групп и специальностей</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <button id="btn_group" type="button" data-bs-toggle="modal" data-bs-target="#Cru_Group" class="btn btn-outline-primary">Добавить группу</button>
                            <button id="edit_btn_groups" type="button" class="btn btn-outline-primary">Изменить список групп</button>
                            <div class="table-responsive">
                                <table id="groups_table" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Группа</th>
                                        </tr>
                                    </thead>
                                    <tbody>


                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <button id="btn_specialization" type="button" class="btn btn-outline-primary">Добавить специальность</button>
                            <button id="edit_btn_specializations" type="button" class="btn btn-outline-primary">Изменить список специальностей</button>
                            <div class="table-responsive">
                                <table id="specializations_table" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Специальность</th>
                                        </tr>
                                    </thead>
                                    <tbody>


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="modal_cru_footer" class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>