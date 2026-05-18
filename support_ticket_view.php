<?php
require_once "includes/init.php";
require_admin();

$id = (int)($_GET['id'] ?? 0);
$pdo = db();
$st = $pdo->prepare("SELECT t.*, u.full_name, u.phone FROM support_tickets t JOIN users u ON t.user_id = u.id WHERE t.id = ?");
$st->execute([$id]);
$ticket = $st->fetch();

if (!$ticket) redirect('support_tickets.php');

$stMsg = $pdo->prepare("SELECT m.*, u.user_type, u.full_name FROM support_ticket_messages m JOIN users u ON m.sender_user_id = u.id WHERE m.ticket_id = ? ORDER BY m.created_at ASC");
$stMsg->execute([$id]);
$messages = $stMsg->fetchAll();

require_once "views/panel/header.php";
require_once "views/panel/sidebar.php";
require_once "includes/jdf.php";
?>

<div class="main-content app-content">
    <div class="container-fluid">
        <div class="my-4 d-flex justify-content-between align-items-center">
            <h1 class="page-title fs-18">تیکت #<?= $id ?> : <?= htmlspecialchars($ticket['subject']) ?></h1>
            <div>
                <?php if ($ticket['status'] != 3): ?>
                    <button class="btn btn-danger btn-wave" onclick="closeTicket()">بستن تیکت</button>
                <?php endif; ?>
                <a href="support_tickets.php" class="btn btn-secondary">بازگشت</a>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-4">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">اطلاعات کاربر</div>
                    </div>
                    <div class="card-body">
                        <p><strong>نام:</strong> <?= htmlspecialchars($ticket['full_name']) ?></p>
                        <p><strong>موبایل:</strong> <span dir="ltr"><?= $ticket['phone'] ?></span></p>
                        <p><strong>وضعیت تیکت:</strong> <?= $ticket['status'] == 1 ? 'باز' : ($ticket['status'] == 2 ? 'پاسخ داده شده' : 'بسته') ?></p>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="card custom-card">
                    <div class="card-body" style="height: 400px; overflow-y: auto;" id="chatBox">
                        <?php foreach ($messages as $m): ?>
                            <?php $isAdmin = $m['user_type'] == 3; ?>
                            <div class="d-flex w-100 <?= $isAdmin ? 'justify-content-start' : 'justify-content-end' ?> mb-3">
                                <div class="p-3 rounded-3 <?= $isAdmin ? 'bg-light text-dark' : 'bg-primary text-white' ?>" style="max-width: 75%;">
                                    <div class="fw-bold mb-1" style="font-size: 11px; opacity: 0.8;"><?= $isAdmin ? 'پشتیبانی' : htmlspecialchars($m['full_name']) ?> - <?= jdate('Y/m/d - H:i', strtotime($m['created_at'])) ?></div>
                                    <div><?= nl2br(htmlspecialchars($m['message'])) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if ($ticket['status'] != 3): ?>
                        <div class="card-footer">
                            <form id="replyForm" class="d-flex gap-2">
                                <input type="hidden" name="ticket_id" value="<?= $id ?>">
                                <textarea class="form-control" name="message" rows="2" placeholder="پاسخ خود را بنویسید..." required></textarea>
                                <button type="submit" class="btn btn-success"><i class="ri-send-plane-fill"></i> ارسال</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const BASE_URL = "<?= base_url() ?>";

    // اسکرول به پایین
    const cb = document.getElementById('chatBox');
    cb.scrollTop = cb.scrollHeight;

    $('#replyForm').submit(function(e) {
        e.preventDefault();
        $.post(`${BASE_URL}/ajax/tickets.php?action=reply`, $(this).serialize(), function(res) {
            if (res.ok) location.reload();
            else alert(res.message);
        }, 'json');
    });

    function closeTicket() {
        if (confirm('تیکت بسته شود؟')) {
            $.post(`${BASE_URL}/ajax/tickets.php?action=close`, {
                ticket_id: <?= $id ?>
            }, function(res) {
                if (res.ok) location.reload();
            }, 'json');
        }
    }
</script>

<?php require_once "views/panel/footer.php"; ?>