<?php
$page_title = 'ورود ادمین';
require __DIR__ . '/views/auth/header.php';

if (admin_id()) {
    redirect('/dashboard.php');
}
?>

<div id="loginAlert" class="alert alert-danger d-none"></div>

<form id="loginForm" class="row gy-3" onsubmit="return false;">
    <div class="col-xl-12">
        <label for="phone" class="form-label text-default">شماره موبایل</label>
        <div class="position-relative">
            <input type="tel" class="form-control form-control-lg" id="phone"
                name="phone" placeholder="0912*******" maxlength="11" style="direction: ltr" autocomplete="username">
        </div>
    </div>

    <div class="col-xl-12 mb-2">
        <label for="password" class="form-label text-default d-block">رمز عبور</label>
        <div class="position-relative">
            <input type="password" class="form-control form-control-lg" id="password"
                name="password" placeholder="رمز عبور" autocomplete="current-password">
            <a href="javascript:void(0);" class="show-password-button text-muted" onclick="togglePassword(this)">
                <i class="ri-eye-off-line align-middle"></i>
            </a>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-2">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
                <label class="form-check-label text-muted fw-normal fs-12" for="remember">من را به خاطر بسپار</label>
            </div>

            <div class="mt-1">
                <a href="forgot.php" class="float-end link-danger op-5 fw-medium fs-12">فراموشی رمز عبور؟</a>
            </div>
        </div>
    </div>

    <div class="d-grid mt-4">
        <input type="submit" class="btn btn-primary" value="ورود" onclick="doLogin()">
    </div>
</form>

<script>
    async function doLogin() {
        const alertBox = document.getElementById('loginAlert');
        alertBox.classList.add('d-none');
        alertBox.textContent = '';

        const form = document.getElementById('loginForm');
        const fd = new FormData(form);

        const phone = document.querySelector('input[name="phone"]').value.trim();
        const pass = document.querySelector('input[name="password"]').value;
        if (!phone || !pass) {
            showError('شماره موبایل و رمز عبور الزامی است');
            return;
        }

        const res = await fetch('/ajax/auth_login.php', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: fd
        });

        let data;
        try {
            data = await res.json();
        } catch (e) {
            const t = await res.text();
            Swal.fire({
                icon: 'error',
                title: 'خطای سرور',
                text: 'پاسخ سرور JSON نیست. (Console را ببین)'
            });
            console.error(t);
            return;
        }
        if (!data.ok) {
            swalError(data.message);
            return;
        }
        location.href = data.redirect || 'dashboard.php';
    }

    function togglePassword(btn) {
        const wrapper = btn.closest('.position-relative');
        const input = wrapper ? wrapper.querySelector('input') : null;
        const icon = btn.querySelector('i');

        if (!input) return;

        const show = (input.type === 'password');
        input.type = show ? 'text' : 'password';

        // اگر مرورگر/قالب ماسک اعمال کند، این خط کمک می‌کند
        input.style.webkitTextSecurity = show ? 'none' : 'disc';

        if (icon) {
            icon.classList.toggle('ri-eye-line', show);
            icon.classList.toggle('ri-eye-off-line', !show);
        }
    }
</script>

<?php require __DIR__ . '/views/auth/footer.php'; ?>