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
                                    <div class="fw-bold mb-1" style="font-size: 11px; opacity: 0.8;">
                                        <?= $isAdmin ? 'پشتیبانی' : htmlspecialchars($m['full_name']) ?> - <?= jdate('Y/m/d - H:i', strtotime($m['created_at'])) ?>
                                    </div>

                                    <?php if ((int)$m['message_type'] === 1): ?>
                                        <div><?= nl2br(htmlspecialchars($m['message'])) ?></div>
                                    <?php elseif ((int)$m['message_type'] === 2): // تصویر 
                                    ?>
                                        <div>
                                            <?php if (!empty($m['message'])): ?>
                                                <p><?= nl2br(htmlspecialchars($m['message'])) ?></p>
                                            <?php endif; ?>
                                            <img src="<?= base_url() . '/storage/' . $m['attachment_key'] ?>"
                                                alt="پیوست" class="img-fluid rounded" style="max-width: 250px;">
                                        </div>
                                    <?php elseif ((int)$m['message_type'] === 3): // PDF 
                                    ?>
                                        <div>
                                            <?php if (!empty($m['message'])): ?>
                                                <p><?= nl2br(htmlspecialchars($m['message'])) ?></p>
                                            <?php endif; ?>
                                            <a href="<?= base_url() . '/storage/' . $m['attachment_key'] ?>"
                                                target="_blank" class="btn btn-sm btn-light">
                                                <i class="ri-file-pdf-line"></i> دانلود فایل: <?= htmlspecialchars($m['attachment_name']) ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if ($ticket['status'] != 3): ?>
                        <div class="card-footer">
                            <form id="replyForm" enctype="multipart/form-data">
                                <input type="hidden" name="ticket_id" value="<?= $id ?>">
                                <div class="mb-2">
                                    <textarea class="form-control" name="message" rows="2" placeholder="پاسخ خود را بنویسید..."></textarea>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <button type="submit" class="btn btn-success"><i class="ri-send-plane-fill"></i> ارسال</button>
                                    <label class="btn btn-outline-secondary mb-0">
                                        <i class="ri-attachment-2"></i> پیوست
                                        <input type="file" name="attachment" id="attachmentInput" accept="image/*,.pdf" style="display:none" onchange="showFileName(this)">
                                    </label>
                                    <small id="fileLabel" class="text-muted"></small>
                                </div>
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

    function showFileName(input) {
        const label = document.getElementById('fileLabel');
        if (input.files && input.files.length > 0) {
            label.textContent = 'فایل انتخاب‌شده: ' + input.files[0].name;
        } else {
            label.textContent = '';
        }
    }

    const cb = document.getElementById('chatBox');
    cb.scrollTop = cb.scrollHeight;

    $('#replyForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: `${BASE_URL}/ajax/tickets.php?action=reply`,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(res) {
                if (res.ok) location.reload();
                else alert(res.message);
            },
            error: function() {
                alert('خطا در ارسال درخواست');
            }
        });
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