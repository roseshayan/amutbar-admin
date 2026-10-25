<?php
$page_title = 'فراموشی رمز عبور';
require __DIR__ . '/views/auth/header.php';

if (admin_id()) {
    redirect(url_path('dashboard.php'));
}
?>

<style>
    .position-relative {
        position: relative !important;
    }

    .show-password-button {
        position: absolute !important;
        top: 50% !important;
        transform: translateY(-115%) !important;
        inset-block-start: auto !important;
        inset-inline-end: 8px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        height: 34px !important;
        width: 36px !important;
        padding: 0 !important;
        background-color: var(--primary01, rgba(115, 93, 255, 0.1)) !important;
        border-radius: 6px !important;
        z-index: 5 !important;
        text-decoration: none !important;
        cursor: pointer !important;
    }

    .show-password-button i {
        font-size: 1.15rem !important;
        line-height: 1 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        color: var(--primary-color, #735dff) !important;
    }

    .position-relative input.form-control {
        padding-inline-end: 50px !important;
    }
</style>

<div id="forgotAlert" class="alert alert-danger d-none"></div>
<div id="forgotOk" class="alert alert-success d-none"></div>

<form id="forgotForm" class="row gy-3" onsubmit="return false;">
    <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
    <div class="col-xl-12" id="phoneGroup">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <label for="fp-phone" class="form-label text-default mb-0">شماره موبایل</label>
            <a href="javascript:void(0);" id="changePhoneBtn" class="fs-12 text-primary d-none text-decoration-none" onclick="editPhone()">
                <i class="ri-edit-line align-middle me-1"></i>ویرایش شماره
            </a>
        </div>
        <div class="position-relative">
            <input type="tel" class="form-control form-control-lg" id="fp-phone" name="phone"
                placeholder="0912*******" maxlength="11" style="direction:ltr">
        </div>
    </div>

    <div class="col-xl-12 d-grid" id="sendOtpBtnWrap">
        <button type="button" class="btn btn-outline-primary" id="btnSendOtp" onclick="sendOtp()">ارسال کد تایید</button>
    </div>

    <div id="step2" class="row gy-3 d-none p-0 m-0">
        <div class="col-xl-12">
            <label for="fp-code" class="form-label text-default">کد تایید</label>
            <div class="position-relative">
                <input type="text" class="form-control form-control-lg" id="fp-code" name="code"
                    placeholder="کد 6 رقمی" maxlength="6" style="direction:ltr">
            </div>
            <div class="d-flex justify-content-between align-items-center mt-2">
                <small id="otpTimer" class="text-muted"></small>
                <button id="resendBtn" type="button" class="btn btn-link p-0" onclick="sendOtp()" disabled>ارسال مجدد</button>
            </div>
        </div>

        <div class="col-xl-12">
            <label for="fp-newpass" class="form-label text-default">رمز عبور جدید</label>
            <div class="position-relative">
                <input type="password" class="form-control form-control-lg" id="fp-newpass" name="new_password"
                    placeholder="حداقل ۱۲ کاراکتر" minlength="12">
                <a href="javascript:void(0);" class="show-password-button text-muted"
                    onclick="createpassword('fp-newpass',this)">
                    <i class="ri-eye-off-line align-middle"></i>
                </a>
            </div>
        </div>

        <div class="col-xl-12">
            <label for="fp-newpass2" class="form-label text-default">تکرار رمز عبور جدید</label>
            <div class="position-relative">
                <input type="password" class="form-control form-control-lg" id="fp-newpass2" name="new_password_confirm"
                    placeholder="تکرار رمز عبور جدید" minlength="12">
                <a href="javascript:void(0);" class="show-password-button text-muted"
                    onclick="createpassword('fp-newpass2',this)">
                    <i class="ri-eye-off-line align-middle"></i>
                </a>
            </div>
        </div>

        <div class="col-xl-12 d-grid mt-2">
            <button type="button" class="btn btn-primary" onclick="resetPass()">تغییر رمز عبور</button>
        </div>
    </div>
</form>

<div class="text-center">
    <p class="text-muted mt-3 mb-0">قبلاً حساب دارید؟ <a href="login.php" class="text-primary">ورود</a></p>
</div>

<script>
    const BASE_URL = "<?= base_url(); ?>";

    let resendCooldown = 0;
    let otpExpiry = 0;
    let resendInterval = null;
    let expiryInterval = null;

    function swalErr(msg) {
        Swal.fire({
            icon: 'error',
            title: 'خطا',
            text: msg || 'خطای نامشخص'
        });
    }

    function swalOk(msg) {
        Swal.fire({
            icon: 'success',
            title: 'موفق',
            text: msg || 'انجام شد'
        });
    }

    async function safeJson(res) {
        const txt = await res.text();
        try {
            return JSON.parse(txt);
        } catch (e) {
            console.error('Non-JSON response:', txt);
            throw new Error('SERVER_NON_JSON');
        }
    }

    function startResendTimer(seconds) {
        resendCooldown = seconds;
        const btn = document.getElementById('resendBtn');
        const lbl = document.getElementById('otpTimer');

        btn.disabled = true;

        if (resendInterval) clearInterval(resendInterval);
        resendInterval = setInterval(() => {
            resendCooldown--;
            if (resendCooldown <= 0) {
                clearInterval(resendInterval);
                btn.disabled = false;
                btn.textContent = 'ارسال مجدد';
                return;
            }
            btn.textContent = `ارسال مجدد (${resendCooldown}s)`;
        }, 1000);

        // متن تایمر اگر دوست داشتید:
        if (lbl) lbl.textContent = '';
    }

    function startExpiryTimer(seconds) {
        otpExpiry = seconds;
        const lbl = document.getElementById('otpTimer');

        if (expiryInterval) clearInterval(expiryInterval);
        expiryInterval = setInterval(() => {
            otpExpiry--;
            if (otpExpiry <= 0) {
                clearInterval(expiryInterval);
                if (lbl) lbl.textContent = 'کد منقضی شد. ارسال مجدد بزنید.';
                return;
            }
            const m = Math.floor(otpExpiry / 60);
            const s = otpExpiry % 60;
            if (lbl) lbl.textContent = `اعتبار کد: ${m}:${String(s).padStart(2,'0')}`;
        }, 1000);
    }

    function showAlert(msg, isSuccess = false) {
        const alertBox = document.getElementById('forgotAlert');
        const okBox = document.getElementById('forgotOk');
        if (isSuccess) {
            if (alertBox) alertBox.classList.add('d-none');
            if (okBox) {
                okBox.textContent = msg;
                okBox.classList.remove('d-none');
            }
        } else {
            if (okBox) okBox.classList.add('d-none');
            if (alertBox) {
                alertBox.textContent = msg;
                alertBox.classList.remove('d-none');
            }
        }
    }

    async function sendOtp() {
        showAlert('', false);
        const form = document.getElementById('forgotForm');
        const fd = new FormData(form);

        const phone = (fd.get('phone') || '').toString().trim();
        if (!phone || !/^09\d{9}$/.test(phone)) {
            const msg = 'شماره موبایل را به صورت ۱۱ رقمی و معتبر (مانند 09121234567) وارد کنید.';
            showAlert(msg, false);
            Swal.fire({
                icon: 'warning',
                title: 'شماره نامعتبر',
                text: msg,
                confirmButtonText: 'متوجه شدم'
            });
            return;
        }

        showLoader('در حال ارسال کد تایید...');

        try {
            const res = await fetch('<?= url_path("ajax/auth_forgot_send.php") ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                body: fd
            });

            const text = await res.text();
            let data;
            try {
                data = JSON.parse(text);
            } catch (e) {
                console.error('Non-JSON response:', text);
                Swal.fire({
                    icon: 'error',
                    title: 'خطای سرور',
                    text: 'پاسخ سرور معتبر نیست. لطفاً مجدداً تلاش کنید.'
                });
                return;
            }

            if (!data.ok) {
                const msg = data.message || 'ارسال ناموفق بود';
                showAlert(msg, false);
                Swal.fire({
                    icon: 'error',
                    title: 'خطا',
                    text: msg,
                    confirmButtonText: 'متوجه شدم'
                });
                return;
            }

            showAlert(data.message || 'کد تایید ارسال شد', true);
            Swal.fire({
                icon: 'success',
                title: 'ارسال شد',
                text: data.message || 'کد تایید ارسال شد',
                timer: 2500,
                showConfirmButton: false
            });

            document.getElementById('step2')?.classList.remove('d-none');
            document.getElementById('sendOtpBtnWrap')?.classList.add('d-none');
            document.getElementById('changePhoneBtn')?.classList.remove('d-none');
            const phoneInput = document.getElementById('fp-phone');
            if (phoneInput) {
                phoneInput.readOnly = true;
                phoneInput.classList.add('bg-light');
            }

            if (data.expires_in_sec) startExpiryTimer(data.expires_in_sec);
            if (data.resend_in_sec) startResendTimer(data.resend_in_sec);

        } catch (err) {
            console.error(err);
            const msg = 'خطا در برقراری ارتباط با سرور. اتصال اینترنت را بررسی کنید.';
            showAlert(msg, false);
            Swal.fire({
                icon: 'error',
                title: 'خطا',
                text: msg,
                confirmButtonText: 'متوجه شدم'
            });
        }
    }

    function editPhone() {
        document.getElementById('step2')?.classList.add('d-none');
        document.getElementById('sendOtpBtnWrap')?.classList.remove('d-none');
        document.getElementById('changePhoneBtn')?.classList.add('d-none');
        const phoneInput = document.getElementById('fp-phone');
        if (phoneInput) {
            phoneInput.readOnly = false;
            phoneInput.classList.remove('bg-light');
            phoneInput.focus();
        }
        if (resendInterval) clearInterval(resendInterval);
        if (expiryInterval) clearInterval(expiryInterval);
        const lbl = document.getElementById('otpTimer');
        if (lbl) lbl.textContent = '';
        showAlert('', false);
    }

    async function resetPass() {
        showAlert('', false);
        const form = document.getElementById('forgotForm');
        const fd = new FormData(form);

        const phone = (fd.get('phone') || '').toString().trim();
        const code = (fd.get('code') || '').toString().trim();
        const new1 = (fd.get('new_password') || '').toString();
        const new2 = (fd.get('new_password_confirm') || '').toString();

        if (!phone || !/^09\d{9}$/.test(phone)) {
            const msg = 'شماره موبایل نامعتبر است.';
            showAlert(msg, false);
            Swal.fire({
                icon: 'warning',
                title: 'خطا',
                text: msg,
                confirmButtonText: 'متوجه شدم'
            });
            return;
        }

        if (!code || code.length !== 6) {
            const msg = 'لطفاً کد تایید ۶ رقمی را وارد کنید.';
            showAlert(msg, false);
            Swal.fire({
                icon: 'warning',
                title: 'خطا',
                text: msg,
                confirmButtonText: 'متوجه شدم'
            });
            return;
        }

        if (new1.length < 12) {
            const msg = 'رمز عبور باید حداقل ۱۲ کاراکتر باشد.';
            showAlert(msg, false);
            Swal.fire({
                icon: 'warning',
                title: 'خطا در رمز عبور',
                text: msg,
                confirmButtonText: 'متوجه شدم'
            });
            return;
        }

        if (new1 !== new2) {
            const msg = 'تکرار رمز عبور جدید با رمز عبور وارد شده یکسان نیست.';
            showAlert(msg, false);
            Swal.fire({
                icon: 'warning',
                title: 'خطا در تکرار رمز',
                text: msg,
                confirmButtonText: 'متوجه شدم'
            });
            return;
        }

        showLoader('در حال تغییر رمز عبور...');

        try {
            const res = await fetch('<?= url_path("ajax/auth_forgot_reset.php") ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                body: fd
            });

            const text = await res.text();
            let data;
            try {
                data = JSON.parse(text);
            } catch (e) {
                console.error('Non-JSON response:', text);
                Swal.fire({
                    icon: 'error',
                    title: 'خطای سرور',
                    text: 'پاسخ سرور معتبر نیست. کنسول را بررسی کنید.'
                });
                return;
            }

            if (!data.ok) {
                const msg = data.message || 'عملیات ناموفق بود';
                showAlert(msg, false);
                Swal.fire({
                    icon: 'error',
                    title: 'خطا',
                    text: msg,
                    confirmButtonText: 'متوجه شدم'
                });
                return;
            }

            showAlert(data.message || 'رمز عبور تغییر کرد', true);
            await Swal.fire({
                icon: 'success',
                title: 'موفق',
                text: data.message || 'رمز عبور تغییر کرد',
                confirmButtonText: 'ورود به پنل'
            });
            location.href = '<?= url_path("login.php") ?>';

        } catch (err) {
            console.error(err);
            const msg = 'خطا در ارتباط با سرور.';
            showAlert(msg, false);
            Swal.fire({
                icon: 'error',
                title: 'خطا',
                text: msg,
                confirmButtonText: 'متوجه شدم'
            });
        }
    }

    function showLoader(title = 'در حال پردازش...') {
        Swal.fire({
            title: title,
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }

    function hideLoader() {
        Swal.close();
    }
</script>


<?php require __DIR__ . '/views/auth/footer.php'; ?>