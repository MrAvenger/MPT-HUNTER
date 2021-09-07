<!-- Modal -->
<div class="modal fade" id="Modal_User_Mass" data-bs-backdrop="static" aria-labelledby="user_mass_data_title"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="user_mass_data" name="user_mass_data" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="user_mass_data_title">Массовое добавление данных</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div id="mass_body" class="col-md-12">
                                <p>Данные в файле должны быть отображены следующим образом (наличие всех столбцов - <i
                                        class="text-danger">обязательно</i>):</p>
                                <table class="table caption-top">
                                    <thead>
                                        <tr>
                                            <th scope="col">Фамилия</th>
                                            <th scope="col">Имя</th>
                                            <th scope="col">Отчество</th>
                                            <th scope="col">Специальность</th>
                                            <th scope="col">Группа</th>
                                            <th scope="col">Email</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>Иванов</th>
                                            <td>Иван</td>
                                            <td>Иванович</td>
                                            <td>09.02.03 Программирование в компьютерных системах</td>
                                            <td>П-3-17</td>
                                            <td>test@gmail.com</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <label for="file" class="form-label">Выберите файл</label>
                                <input class="form-control" name="file" type="file" id="file">
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <p><b>Только в случае если вы не указываете группу и специальность у студентов в excel
                                        файле, отметьте поле ниже и выберите специальность и группу</b></p>
                            </div>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" name="use_data" type="checkbox" id="use_data">
                            <label class="form-check-label" for="use_data">Использовать данные ниже</label>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="specialization_mass" class="form-label">Специальность</label>
                                <select id="specialization_mass" name="specialization_mass"
                                    class="form-select"></select>
                            </div>
                            <div class="col-md-6">
                                <label for="group_mass" class="form-label">Группы</label>
                                <select id="group_mass" name="group_mass" class="form-select"></select>
                            </div>
                        </div>
                        <div class="row">
                            <div id="errors_mass" class="alert-danger" class="col-md-12">
                            </div>
                        </div>
                        <div class="row">
                            <div id="mass_status" class="col-md-12">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="modal_mass_user_footer" class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button id="btn_mass_user" type="submit" class="btn btn-primary">Загрузить</button>
                </div>
            </div>
        </form>
    </div>
</div>