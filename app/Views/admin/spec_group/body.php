<!doctype html>
<html lang="ru">

<body class="bg-dark">
    <main class="my-5 pt-5" style="margin-bottom: 60px;">
        <div class="card mb-3">
            <div class="row g-0">
                <div class="col-md-5">
                    <button id="btn_main" type="button" class="btn btn-outline-primary my-1" data-bs-toggle="modal"
                        data-bs-target="#CRU_Main">Добавить данные</button>
                    <button type="button" class="btn btn-outline-primary my-1" data-bs-toggle="modal"
                        data-bs-target="#Modal_List_View">Управление группами и специальностями</button>
                </div>
                <div class="col-md-12">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="custom_table" class="table table-striped " style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="max-width:200px">Специальность</th>
                                        <th style="max-width:100px">Куратор</th>
                                        <th>Группы</th>
                                        <th>Навыки</th>
                                        <th style="max-width:200px">Действия</th>
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
    </main>
    <script src="<?php echo base_url()?>/assets/js/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
    const type_select=1;
    </script>
    <script src="<?php echo base_url()?>/assets/js/jquery.toast.js"></script>
    <script src="<?php echo base_url()?>/assets/js/datatable/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url()?>/assets/js/select2.min.js"></script>
    <script src="<?php echo base_url()?>/assets/js/select2.ru.js"></script>
    <script src="<?php echo base_url()?>/assets/js/admin/groups.js"></script>
    <script src="<?php echo base_url()?>/assets/js/admin/specializations.js"></script>
    <script src="<?php echo base_url()?>/assets/js/base_crud_ajax.js"></script>
    <script src="<?php echo base_url()?>/assets/js/admin/groupsspecializations.js"></script>
</body>

</html>