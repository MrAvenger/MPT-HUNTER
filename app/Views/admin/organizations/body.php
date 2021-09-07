<!doctype html>
<html lang="ru">

<body class="bg-dark">
    <main class="my-5 pt-5" style="margin-bottom: 170px;">
        <div class="card mb-3">
            <div class="row my-2 g-0">
                <div class="col-md-5">
                    <button type="button" class="btn btn-outline-primary my-1" data-bs-toggle="modal" onclick="add_open_org()"
                        data-bs-target="#Modal_Org_CRU">Добавить</button>
                </div>
                <div class="col-md-12">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="organizations_table" class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th style="max-width:200px;">Фото</th>
                                        <th style="max-width:200px;">Наименование</th>
                                        <th style="max-width:200px;">Представитель</th>
                                        <th style="max-width:200px;">Должность</th>
                                        <th style="max-width:200px;">Адрес</th>
                                        <th style="max-width:200px;">Действия</th>
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
</body>
<script src="<?php echo base_url()?>/assets/js/jquery-3.6.0.min.js"></script>
<script src="<?php echo base_url()?>/assets/js/ahunter_suggest.js"></script>
<script src="<?php echo base_url()?>/assets/js/select2.min.js"></script>
<script src="<?php echo base_url()?>/assets/js/select2.ru.js"></script>
<script src="<?php echo base_url()?>/assets/js/jquery.toast.js"></script>
<script src="<?php echo base_url()?>/assets/js/datatable/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url()?>/assets/js/base_crud_ajax.js"></script>
<script src="<?php echo base_url()?>/assets/js/admin/all_orgs.js"></script>

</html>