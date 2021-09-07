<div class="modal fade" id="CRU_Main" data-bs-backdrop="static" aria-labelledby="Main_Add_Edit_Modal_Label"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="Main_Add_Edit_Modal_Label">Управление данными о специальности</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="main_form">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="specialization" class="form-label">Специальность</label>
                                    <select id="specialization" name="specialization" class="form-select"
                                        aria-label="Default select example">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="curator" class="form-label">Куратор (ПЦМК)</label>
                                    <select id="curator" name="curator" class="form-select"
                                        aria-label="Default select example">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="groups" class="form-label">Группы</label>
                                    <select id="groups" name="groups[]" class="form-select" multiple="multiple">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="exampleFormControlTextarea1" class="form-label">Описание</label>
                                    <textarea id="description" name="description" class="form-control"
                                        id="exampleFormControlTextarea1"
                                        rows="5"><?php if(isset($resume['additionally'])):echo htmlspecialchars($resume['additionally']); else: echo '';endif;?></textarea>
                                    <!-- <div id="additionally_HelpBlock" class="form-text">
                                        Здесь вы можете написать о том, в каких программах вы умеете
                                        работать
                                        (например Photoshop 2019) и тп.
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-7">
                                <div class="mb-3">
                                    <button id="btn_main_skill" type="button" class="btn btn-danger">Добавить
                                        навыки/умения</button>
                                </div>
                                <div id="skills_row" class="row"></div>
                            </div>
                            <div class="row mt-3">
                                <div id="errors_skills" class="col-md-12 alert-danger">

                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div id="main_footer" class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>