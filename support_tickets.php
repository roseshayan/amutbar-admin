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
                <h1 class="page-title fw-medium fs-18 mb-2">تیکت‌های پشتیبانی</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="ticketsTable" class="table table-bordered table-striped w-100">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>کاربر</th>
                                        <th>موضوع</th>
                                        <th>وضعیت</th>
                                        <th>آخرین بروزرسانی</th>
                                        <th>عملیات</th>
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
        $('#ticketsTable').DataTable({
            ajax: {
                url: '<?= base_url() ?>/ajax/tickets.php?action=list',
                type: 'GET'
            },
            columns: [{
                    data: 'id'
                }, {
                    data: 'user'
                }, {
                    data: 'subject'
                }, {
                    data: 'status'
                },
                {
                    data: 'updated_at'
                }, {
                    data: 'actions',
                    orderable: false
                }
            ],
            language: {
                url: 'assets/vendor/datatables/fa.json'
            },
            order: [
                [0, 'desc']
            ]
        });
    });
</script>

<?php require_once "views/panel/footer.php"; ?>