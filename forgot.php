<?php
$page_title = 'فراموشی رمز عبور';
require __DIR__ . '/views/auth/header.php';

if (admin_id()) {
    redirect(url_path('dashboard.php'));
}
?>

<div id="forgotAlert" class="alert alert-danger d-none"></div>
<div id="forgotOk" class="alert alert-success d-none"></div>

<form id="forgotForm" class="row gy-3" onsubmit="return false;">
    <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
    <div class="col-xl-12">
        <label for="fp-phone" class="form-label text-default">شماره موبایل</label>
        <div class="position-relative">
            <input type="tel" class="form-control form-control-lg" id="fp-phone" name="phone"
                placeholder="0912*******" maxlength="11" style="direction:ltr">
        </div>
    </div>

    <div class="col-xl-12 d-grid">
        <button type="button" class="btn btn-outline-primary" onclick="sendOtp()">ارسال کد تایید</button>
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

    async function sendOtp(){
        const form = document.getElementById('forgotForm');
        const fd = new FormData(form);

        // اعتبارسنجی سریع سمت کلاینت (اختیاری ولی بهتر)
        const phone = (fd.get('phone') || '').toString().trim();
        if (!phone) {
            Swal.fire({icon:'warning', title:'خطا', text:'شماره موبایل را وارد کنید'});
            return;
        }

        showLoader('در حال ارسال کد تایید...');

        try {
            const res = await fetch('<?= url_path("ajax/auth_forgot_send.php") ?>', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin',
                body: fd
            });

            // اگر سرور JSON نمی‌دهد، این بخش جلوی کرش را می‌گیرد
            const text = await res.text();
            let data;
            try { data = JSON.parse(text); }
            catch(e){
                console.error('Non-JSON response:', text);
                Swal.fire({icon:'error', title:'خطای سرور', text:'پاسخ سرور معتبر نیست. کنسول را بررسی کنید.'});
                return;
            }

            if (!data.ok) {
                Swal.fire({icon:'error', title:'خطا', text: data.message || 'ارسال ناموفق بود'});
                return;
            }

            Swal.fire({icon:'success', title:'ارسال شد', text: data.message || 'کد تایید ارسال شد'});

            // نمایش مرحله دوم (اگر دارید)
            document.getElementById('step2')?.classList.remove('d-none');

            // اگر تایمر دارید (اختیاری)
            if (data.expires_in_sec) startExpiryTimer(data.expires_in_sec);
            if (data.resend_in_sec) startResendTimer(data.resend_in_sec);

        } catch (err) {
            console.error(err);
            Swal.fire({icon:'error', title:'خطا', text:'خطا در ارتباط با سرور'});
        } finally {
            hideLoader();
        }
    }

    async function resetPass(){
        const form = document.getElementById('forgotForm');
        const fd = new FormData(form);

        showLoader('در حال تغییر رمز عبور...');

        try {
            const res = await fetch('<?= url_path("ajax/auth_forgot_reset.php") ?>', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin',
                body: fd
            });

            const text = await res.text();
            let data;
            try { data = JSON.parse(text); }
            catch(e){
                console.error('Non-JSON response:', text);
                Swal.fire({icon:'error', title:'خطای سرور', text:'پاسخ سرور معتبر نیست. کنسول را بررسی کنید.'});
                return;
            }

            if (!data.ok) {
                Swal.fire({icon:'error', title:'خطا', text: data.message || 'عملیات ناموفق بود'});
                return;
            }

            Swal.fire({icon:'success', title:'موفق', text: data.message || 'رمز عبور تغییر کرد'});
            setTimeout(()=> location.href='<?= url_path("login.php") ?>', 700);

        } catch (err) {
            console.error(err);
            Swal.fire({icon:'error', title:'خطا', text:'خطا در ارتباط با سرور'});
        } finally {
            hideLoader();
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
