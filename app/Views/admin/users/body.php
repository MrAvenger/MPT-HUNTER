<!doctype html>
<html lang="ru">
<style>
.select-dropdown {
    position: static;
}

.select-dropdown .select-dropdown--above {
    margin-top: 336px;
}
</style>

<body class="bg-dark">
    <main class='content' style="margin-bottom: 70px;">
        <div class="container my-5 pt-5">
            <div class="card mb-3">
                <div class="row my-2 g-0">
                    <div class="col-md-12">
                        <button type="button" onclick="open_user_modal(null,1)" class="btn btn-outline-primary my-1"
                            data-bs-toggle="modal" data-bs-target="#Modal_User_CRU">Добавить</button>
                        <button type="button" onclick="open_user_modal(null,5)"
                            class="btn btn-outline-primary my-1">Массовое добавление студентов</button>
                        <button type="button" onclick="open_user_modal(null,4)"
                            class="btn btn-outline-danger my-1">Массовое удаление</button>
                    </div>
                    <div class="col-md-12">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="users_table" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>ФИО</th>
                                            <th>Email</th>
                                            <th>Роль</th>
                                            <th>Действия</th>
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
        </div>
    </main>
</body>
<script src="<?php echo base_url()?>/assets/js/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery.maskedinput@1.4.1/src/jquery.maskedinput.min.js"
    type="text/javascript"></script>
<script src="<?php echo base_url()?>/assets/js/select2.min.js"></script>
<script src="<?php echo base_url()?>/assets/js/select2.ru.js"></script>
<script src="<?php echo base_url()?>/assets/js/jquery.toast.js"></script>
<script src="<?php echo base_url()?>/assets/js/datatable/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url()?>/assets/js/admin/users_script.js"></script>
<script src="<?php echo base_url()?>/assets/js/base_crud_ajax.js"></script>

</html>