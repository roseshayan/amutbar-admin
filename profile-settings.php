<?php
declare(strict_types=1);

$page_title = 'تنظیمات پروفایل';

require_once "views/panel/header.php";
require_once "views/panel/sidebar.php";

$admin_id = admin_id();
$admin_data = admin_get_profile_data($admin_id);

if (!$admin_data) {
    header('Location: /index.php?page=login');
    exit;
}

$message = '';
$message_type = '';
$csrfValid = true;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $providedCsrf = (string)($_POST['_csrf_token'] ?? '');
    $csrfValid = $providedCsrf !== '' && hash_equals(csrf_token(), $providedCsrf);
    if (!$csrfValid) {
        http_response_code(419);
        $message = 'درخواست نامعتبر است؛ صفحه را تازه‌سازی و دوباره تلاش کنید.';
        $message_type = 'danger';
    }
}

// پردازش فرم ارسال شده
if ($csrfValid && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    $update_data = [
        'id' => $admin_id,
        'full_name' => $_POST['full_name'] ?? '',
        'email' => $_POST['email'] ?? '',
        'phone' => $_POST['phone'] ?? '',
        'password' => $_POST['new_password'] ?? '',
        'current_password' => $_POST['current_password'] ?? ''
    ];
    
    $result = admin_update_profile($update_data);
    
    if ($result['ok']) {
        $message = $result['message'];
        $message_type = 'success';
        // رفرش داده‌ها
        $admin_data = admin_get_profile_data($admin_id);
    } else {
        $message = $result['message'];
        $message_type = 'danger';
    }
}

// پردازش آپلود عکس پروفایل
if ($csrfValid && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_avatar') {
    $res = admin_update_avatar((int)$admin_id, $_FILES['avatar'] ?? []);
    if ($res['ok']) {
        $message = $res['message'] ?? 'عکس پروفایل بروزرسانی شد';
        $message_type = 'success';
        $admin_data = admin_get_profile_data($admin_id);
    } else {
        $message = $res['message'] ?? 'خطا در بروزرسانی عکس پروفایل';
        $message_type = 'danger';
    }
}
?>

<!-- MAIN-CONTENT -->
<div class="main-content app-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="page-title fw-medium fs-18 mb-2">تنظیمات پروفایل</h1>
                <div class="">
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="dashboard.php">داشبورد</a></li>
                            <li class="breadcrumb-item"><a href="profile.php">پروفایل</a></li>
                            <li class="breadcrumb-item active" aria-current="page">تنظیمات</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="btn-list">
                <a href="profile.php" class="btn btn-secondary btn-wave">
                    <i class="ri-arrow-right-line align-middle"></i> بازگشت به پروفایل
                </a>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- نمایش پیام -->
        <?php if ($message): ?>
        <div class="row">
            <div class="col-xl-12">
                <div class="alert alert-<?= $message_type ?> alert-dismissible fade show" role="alert">
                    <i class="ri-information-line me-2"></i>
                    <?= htmlspecialchars($message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Start::row-1 -->
        <div class="row">
            <div class="col-xl-8">
                <div class="card custom-card">
                    <div class="card-header">
                        <h5 class="card-title">ویرایش اطلاعات شخصی</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="" id="profileForm">
                            <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="action" value="update_profile">
                            
                            <div class="row gy-4">
                                <div class="col-xl-6">
                                    <label for="full_name" class="form-label">نام و نام خانوادگی <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" 
                                           value="<?= htmlspecialchars($admin_data['full_name']) ?>" 
                                           required placeholder="نام کامل خود را وارد کنید">
                                    <div class="form-text">نام شما در سیستم نمایش داده می‌شود</div>
                                </div>
                                
                                <div class="col-xl-6">
                                    <label for="phone" class="form-label">شماره موبایل <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           value="<?= htmlspecialchars($admin_data['phone']) ?>" 
                                           required placeholder="09xxxxxxxxx">
                                    <div class="form-text">شماره موبایل برای ورود به سیستم استفاده می‌شود</div>
                                </div>
                                
                                <div class="col-xl-12">
                                    <label for="email" class="form-label">آدرس ایمیل</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?= htmlspecialchars($admin_data['email'] ?? '') ?>" 
                                           placeholder="example@domain.com">
                                    <div class="form-text">ایمیل برای بازیابی رمز عبور و اطلاع‌رسانی استفاده می‌شود</div>
                                </div>
                                
                                <div class="col-xl-12">
                                    <hr class="my-4">
                                    <h6 class="fw-semibold mb-3">تغییر رمز عبور</h6>
                                    <p class="text-muted mb-3">اگر می‌خواهید رمز عبور خود را تغییر دهید، فیلدهای زیر را پر کنید.</p>
                                    
                                    <div class="row">
                                        <div class="col-xl-6 mb-3">
                                            <label for="current_password" class="form-label">رمز عبور فعلی</label>
                                            <input type="password" class="form-control" id="current_password" name="current_password" 
                                                   placeholder="رمز عبور فعلی را وارد کنید">
                                            <div class="form-text">برای تغییر رمز عبور، رمز عبور فعلی را وارد کنید</div>
                                        </div>
                                        
                                        <div class="col-xl-6 mb-3">
                                            <label for="new_password" class="form-label">رمز عبور جدید</label>
                                            <input type="password" class="form-control" id="new_password" name="new_password" 
                                                   placeholder="رمز عبور جدید را وارد کنید" minlength="12">
                                            <div class="form-text">حداقل ۱۲ کاراکتر باشد</div>
                                        </div>
                                        
                                        <div class="col-xl-6">
                                            <label for="confirm_password" class="form-label">تأیید رمز عبور جدید</label>
                                            <input type="password" class="form-control" id="confirm_password" 
                                                   placeholder="رمز عبور جدید را مجدد وارد کنید">
                                            <div class="form-text">تأیید رمز عبور جدید</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-xl-12">
                                    <div class="alert alert-info">
                                        <div class="d-flex">
                                            <div class="me-3">
                                                <i class="ri-information-line fs-20"></i>
                                            </div>
                                            <div class="flex-fill">
                                                <h6 class="alert-heading">توجه!</h6>
                                                <p class="mb-0">پس از تغییر شماره موبایل، برای ورود مجدد باید از شماره جدید استفاده کنید.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer border-top-0 pt-4">
                                <div class="btn-list float-end">
                                    <button type="button" class="btn btn-secondary btn-wave" onclick="window.history.back()">لغو</button>
                                    <button type="submit" class="btn btn-primary btn-wave">ذخیره تغییرات</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-4">
                <div class="card custom-card">
                    <div class="card-header">
                        <h5 class="card-title">اطلاعات حساب</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <span class="avatar avatar-xxl avatar-rounded bg-primary bg-opacity-10 mb-3">
                                <?php if ($admin_data['avatar_key']): ?>
                                    <?php
                                        $a = (string)$admin_data['avatar_key'];
                                        if ($a !== '' && !preg_match('~^https?://~i', $a) && $a[0] !== '/') {
                                            $a = '/' . ltrim($a, '/');
                                        }
                                    ?>
                                    <img src="<?= htmlspecialchars($a) ?>" alt="آواتار">
                                <?php else: ?>
                                    <span class="avatar-initials fs-24 text-primary">
                                        <?= mb_substr($admin_data['full_name'], 0, 1) ?>
                                    </span>
                                <?php endif; ?>
                            </span>
                            <h5 class="fw-semibold mb-1"><?= htmlspecialchars($admin_data['full_name']) ?></h5>
                            <p class="text-muted mb-0">مدیر سیستم</p>
                        </div>

                        <form method="POST" action="" enctype="multipart/form-data" class="mb-4">
                            <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="action" value="update_avatar">
                            <label class="form-label">تغییر عکس پروفایل</label>
                            <input type="file" name="avatar" class="form-control" accept="image/png,image/jpeg,image/webp" required>
                            <div class="form-text">فرمت‌های مجاز: jpg / png / webp — حداکثر 3MB</div>
                            <button type="submit" class="btn btn-outline-primary btn-wave w-100 mt-2">
                                <i class="ri-image-edit-line me-2"></i>آپلود عکس جدید
                            </button>
                        </form>
                        
                        <ul class="list-group list-group-flush mb-4">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-muted">شناسه کاربری:</span>
                                <span class="fw-medium">#<?= $admin_data['id'] ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-muted">وضعیت:</span>
                                <span class="badge bg-success">فعال</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-muted">نوع حساب:</span>
                                <span class="fw-medium"><?= user_type_label($admin_data['user_type']) ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-muted">تاریخ عضویت:</span>
                                <span class="fw-medium">
                                    <?php 
                                        $date = new DateTime($admin_data['created_at']);
                                        echo jalali_date($date->format('Y/m/d'));
                                    ?>
                                </span>
                            </li>
                        </ul>
                        
                        <div class="alert alert-warning">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="ri-alert-line fs-20"></i>
                                </div>
                                <div class="flex-fill">
                                    <h6 class="alert-heading">امنیت حساب</h6>
                                    <p class="mb-0">اطلاعات حساب خود را محرمانه نگه دارید و به کسی ندهید.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="profile.php" class="btn btn-outline-primary">
                                <i class="ri-eye-line me-2"></i>مشاهده پروفایل
                            </a>
                            <a href="logout.php" class="btn btn-outline-danger">
                                <i class="ri-logout-box-r-line me-2"></i>خروج از سیستم
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--End::row-1 -->
    </div>
</div>
<!-- END MAIN-CONTENT -->

<script>
// اعتبارسنجی رمز عبور
document.getElementById('profileForm').addEventListener('submit', function(e) {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const currentPassword = document.getElementById('current_password').value;
    
    // اگر رمز عبور جدید وارد شده اما رمز عبور فعلی وارد نشده
    if (newPassword && !currentPassword) {
        e.preventDefault();
        alert('برای تغییر رمز عبور، رمز عبور فعلی را وارد کنید');
        return false;
    }
    
    // اگر رمز عبور جدید وارد شده اما تأیید آن مطابقت ندارد
    if (newPassword && newPassword !== confirmPassword) {
        e.preventDefault();
        alert('رمز عبور جدید و تأیید آن مطابقت ندارند');
        return false;
    }
    
    // اگر رمز عبور جدید کمتر از ۱۲ کاراکتر باشد
    if (newPassword && newPassword.length < 12) {
        e.preventDefault();
        alert('رمز عبور جدید باید حداقل ۱۲ کاراکتر باشد');
        return false;
    }
    
    // اعتبارسنجی شماره موبایل
    const phone = document.getElementById('phone').value;
    const phoneRegex = /^09\d{9}$/;
    if (!phoneRegex.test(phone)) {
        e.preventDefault();
        alert('شماره موبایل باید با 09 شروع شده و 11 رقم باشد');
        return false;
    }
    
    return true;
});

// قالب شماره موبایل
document.getElementById('phone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 11) {
        value = value.substring(0, 11);
    }
    e.target.value = value;
});
</script>

<?php require_once "views/panel/footer.php"; ?>
