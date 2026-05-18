<?php
require_once "includes/init.php";
require_admin();

$id = (int)($_GET['id'] ?? 0);
$cargo = ['id' => 0, 'title' => ''];

if ($id > 0) {
    $pdo = db();
    $st = $pdo->prepare("SELECT * FROM cargos_list WHERE id = ?");
    $st->execute([$id]);
    $cargo = $st->fetch() ?: $cargo;
}

require_once "views/panel/header.php";
require_once "views/panel/sidebar.php";
?>

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-2"><?= $id > 0 ? 'ویرایش کالا' : 'افزودن کالا جدید' ?></h1>
            </div>
            <div class="btn-list"><a href="cargos_list.php" class="btn btn-secondary">بازگشت</a></div>
        </div>

        <div class="row">
            <div class="col-xl-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <form id="cargoForm">
                            <input type="hidden" name="id" value="<?= $cargo['id'] ?>">
                            <div class="mb-3">
                                <label class="form-label">نام کالا *</label>
                                <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($cargo['title']) ?>" required autofocus>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="submitCargo()">ذخیره کالا</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function submitCargo() {
        let form = $('#cargoForm')[0];
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        $.post('<?= base_url() ?>/ajax/cargos.php?action=save', $('#cargoForm').serialize(), function(res) {
            if (res.ok) {
                window.location.href = 'cargos_list.php';
            } else {
                alert(res.message);
            }
        }, 'json');
    }
</script>

<?php require_once "views/panel/footer.php"; ?>