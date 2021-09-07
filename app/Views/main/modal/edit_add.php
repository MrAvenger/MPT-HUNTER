<!-- Modal -->
<div class="modal fade" id="Add_Edit_Modal" name="Add_Edit_Modal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="Add_Edit_Modal_Title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body justify-content-center">
                <div class="container">
                    <div class="row">
                        <form id="offer_form">
                            <div class="row g-3">
                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Тип занятости</label>
                                        <select id="employment" name="employment" class="form-select"
                                            aria-label="Default select example">
                                            <option value="Частичная">Частичная</option>
                                            <option value="Полная">Полная</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Наименование предложения</label>
                                        <input class="form-control" id="offer_name" name="offer_name" type="text"
                                            placeholder="" aria-label="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Зарплата</label>
                                        <input class="form-control" id="salary" name="salary" type="number"
                                            placeholder="" aria-label="">
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Описание</label>
                                        <textarea class="form-control" id="offer_description" name="offer_description"
                                            rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <p>Требования к кандидату</p>
                                        <button id="add_require" type="button" class="btn btn-danger">Добавить</button>
                                    </div>
                                </div>
                                <div id="require_list" class="row">

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div id="Footer_buttons" name="Footer_buttons" class="modal-footer">
            </div>
        </div>
    </div>
</div>