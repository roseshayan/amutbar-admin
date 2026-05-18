<?php
require_once "includes/init.php";
require_admin();
require_once "views/panel/header.php";
require_once "views/panel/sidebar.php";
?>

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-2">لیست کالاها</h1>
            </div>
            <div class="btn-list">
                <a href="cargo_edit.php" class="btn btn-primary btn-wave">
                    <i class="ri-add-line align-middle"></i> افزودن کالا جدید
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="cargosTable" class="table table-bordered table-striped w-100">
                                <thead>
                                    <tr>
                                        <th width="50">ID</th>
                                        <th>نام کالا</th>
                                        <th width="100">عملیات</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        $('#cargosTable').DataTable({
            ajax: {
                url: '<?= base_url() ?>/ajax/cargos.php?action=list',
                type: 'GET'
            },
            columns: [{
                    data: 'id'
                },
                {
                    data: 'title'
                },
                {
                    data: 'actions',
                    orderable: false
                }
            ],
            order: [
                [0, 'desc']
            ],
            language: {
                url: 'assets/vendor/datatables/fa.json'
            }
        });
    });

    function deleteCargo(id) {
        if (confirm('آیا از حذف این کالا مطمئن هستید؟')) {
            $.post('<?= base_url() ?>/ajax/cargos.php?action=delete', {
                id: id
            }, function(res) {
                if (res.ok) {
                    $('#cargosTable').DataTable().ajax.reload();
                } else {
                    alert(res.message);
                }
            }, 'json');
        }
    }
</script>

<?php require_once "views/panel/footer.php"; ?>