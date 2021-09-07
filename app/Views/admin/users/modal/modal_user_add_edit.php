<!-- Modal -->
<div class="modal fade" id="Modal_User_CRU" role="dialog" data-bs-backdrop="static" aria-labelledby="modal_cru_title"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_cru_title">Управление данными пользователя</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="user_data" name="user_data" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="content">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label for="first_name" class="form-label">Имя</label>
                                        <input type="text" name="first_name" class="form-control" id="first_name">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label for="last_name" class="form-label">Фамилия</label>
                                        <input type="text" name="last_name" class="form-control" id="last_name">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label for="middle_name" class="form-label">Отчество</label>
                                        <input type="text" name="middle_name" class="form-control" id="middle_name">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label for="sex" class="form-label">Пол</label>
                                        <select id="sex" name="sex" class="form-select" aria-label="Default select example">
                                            <option value='' selected>Не выбрано</option>
                                            <option value="Мужской">Мужской</option>
                                            <option value="Женский">Женский</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label for="date_birth" class="form-label">Дата рождения</label>
                                        <input type="date" name="date_birth" class="form-control" id="date_birth">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label for="number_phone" class="form-label">Номер телефона</label>
                                        <input type="tel" name="number_phone" class="form-control" id="number_phone">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label for="photo" class="form-label">Выберите фото</label>
                                        <input class="form-control" name="photo" type="file" id="photo">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" id="email">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Пароль</label>
                                        <input type="password" name="password" class="form-control" id="password">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label for="role" class="form-label">Роль</label>
                                        <select id="role" name="role" class="form-select" aria-label="Default select example">
                                            <option value="Студент" selected>Студент</option>
                                            <option value="Работодатель">Работодатель</option>
                                            <option value="Куратор">Куратор</option>
                                            <option value="Администратор">Администратор</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="dinam_row" class="row"></div>
                            <div class="row">
                                <div id="errors" class="col-md-12 alert-danger">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="modal_cru_footer" class="modal-footer">
                    <input id="user_id" name="user_id" type="hidden">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>