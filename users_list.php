<?php
require_once __DIR__ . '/includes/init.php';
require_admin();
require_once "views/panel/header.php";
require_once "views/panel/sidebar.php";
?>

<div class="main-content app-content">
    <div class="container-fluid">

        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-2">مدیریت کاربران</h1>
                <div>
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="dashboard.php">داشبورد</a></li>
                            <li class="breadcrumb-item active" aria-current="page">کاربران</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="btn-list">
                <a href="user_create.php" class="btn btn-primary btn-wave">
                    <i class="ri-user-add-line align-middle"></i> افزودن کاربر
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body">

                        <div class="table-responsive">
                            <table id="usersTable" class="table table-striped table-bordered w-100">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>نام</th>
                                    <th>موبایل</th>
                                    <th>ایمیل</th>
                                    <th>نوع</th>
                                    <th>وضعیت</th>
                                    <th>تاریخ ایجاد</th>
                                    <th>عملیات</th>
                                </tr>
                                </thead>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    const BASE_URL = "<?= base_url(); ?>";

    function loader(title) {
        Swal.fire({
            title: title || 'در حال پردازش...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => Swal.showLoading()
        });
    }

    let dt;
    document.addEventListener('DOMContentLoaded', () => {
        dt = $('#usersTable').DataTable({
            processing: true,
            serverSide: true,
            searchDelay: 350,
            order: [[0, 'desc']],
            ajax: {
                url: `${BASE_URL}/ajax/users/users_list.php`,
                type: 'POST'
            },
            columns: [
                {data: 'id'},
                {data: 'full_name'},
                {data: 'phone'},
                {data: 'email'},
                {data: 'user_type_label'},
                {data: 'status_label'},
                {
                    "data": "created_at",
                    "render": function (data) {
                        return data ? new Date(data).toLocaleString('fa-IR') : '-';
                    }
                },
                {
                    data: null, orderable: false, searchable: false, render: function (row) {
                        return `
          <a class="btn btn-sm btn-outline-primary" href="user_edit.php?id=${row.id}">ویرایش</a>
          <button class="btn btn-sm btn-outline-danger ms-1" onclick="deleteUser(${row.id})">حذف</button>
        `;
                    }
                }
            ],
            language: {
                url: 'assets/vendor/datatables/fa.json',
            }
        });
    });

    async function deleteUser(id) {
        const r = await Swal.fire({
            icon: 'warning',
            title: 'حذف کاربر',
            text: 'آیا مطمئن هستید؟',
            showCancelButton: true,
            confirmButtonText: 'بله، حذف شود',
            cancelButtonText: 'انصراف'
        });
        if (!r.isConfirmed) return;

        loader('در حال حذف...');
        try {
            const fd = new FormData();
            fd.append('id', id);

            const res = await fetch(`${BASE_URL}/ajax/users/users_delete.php`, {
                method: 'POST',
                headers: {'X-Requested-With': 'XMLHttpRequest'},
                credentials: 'same-origin',
                body: fd
            });

            Swal.close();

            if (!res.ok) {
                Swal.fire({icon: 'error', title: 'خطا', text: data.message || 'عملیات ناموفق بود'});
                console.log(res.statusText);
                return;
            }

            Swal.fire({icon: 'success', title: 'موفق', text: 'حذف شد'});
            dt.ajax.reload(null, false);
        } catch (e) {
            Swal.close();
            Swal.fire({icon: 'error', title: 'خطا', text: 'خطا در ارتباط با سرور'});
            console.log(e);
        }
    }
</script>

<?php require_once "views/panel/footer.php"; ?>
