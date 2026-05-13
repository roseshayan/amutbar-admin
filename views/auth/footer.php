</div>
</div>
</div>
</div>
</div>
</div>

<!-- SCRIPTS -->
<!-- BOOTSTRAP JS -->
<script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Particles JS -->
<script src="assets/libs/particles/particles.min.js"></script>
<!-- SweetAlert2 -->
<script src="assets/vendor/sweetalert2/sweetalert2.all.min.js"></script>
<script src="assets/js/basic-password.js"></script>
<script>
    function swalError(msg) {
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

    // Inline + safe: بدون فایل جدا
    function createpassword(inputId, el) {
        const input = document.getElementById(inputId);
        if (!input) return;

        const isPass = (input.getAttribute('type') === 'password');
        input.setAttribute('type', isPass ? 'text' : 'password');

        const icon = el ? el.querySelector('i') : null;
        if (icon) {
            icon.classList.toggle('ri-eye-line', isPass);
            icon.classList.toggle('ri-eye-off-line', !isPass);
        }
    }
</script>
</body>

</html>