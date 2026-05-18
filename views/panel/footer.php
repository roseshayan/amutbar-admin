<!-- FOOTER -->

<footer class="footer mt-auto py-3 bg-white text-center">
    <div class="container">
        <span class="text-muted"> <span id="year"></span>
            طراحی با
            <span class="bi bi-heart-fill text-danger"></span>و
            <span class="bi bi-cup-fill text-orange"></span> توسط
            <a href="mailto:namayandeshayan@gmail.com" target="_blank">
                <span class="fw-medium text-primary">شایان نماینده</span>
            </a>
        </span>
    </div>
</footer>
<!-- END FOOTER -->

</div>
<!-- END PAGE-->

<!-- SCRIPTS -->

<!-- SCROLL-TO-TOP -->
<div class="scrollToTop">
    <span class="arrow lh-1"><i class="ti ti-arrow-big-up fs-16"></i></span>
</div>
<div id="responsive-overlay"></div>

<!-- POPPER JS -->
<script src="assets/libs/%40popperjs/core/umd/popper.min.js"></script>

<!-- BOOTSTRAP JS -->
<script src="assets/vendor/bootstrap/bootstrap.bundle.min.js"></script>

<!-- NODE WAVES JS -->
<script src="assets/libs/node-waves/waves.min.js"></script>

<!-- AUTO COMPLETE JS -->
<script src="assets/libs/%40tarekraafat/autocomplete.js/autoComplete.min.js"></script>

<!-- Datatable -->
<script src="assets/vendor/datatables/dataTables.min.js"></script>
<script src="assets/vendor/datatables/dataTables.bootstrap5.min.js"></script>

<!-- STICKY JS -->
<script src="assets/js/sticky.js"></script>

<!-- DEFAULTMENU JS -->
<script src="assets/js/defaultmenu.js"></script>

<!-- CUSTOM JS -->
<script src="assets/js/custom.js"></script>

<!-- END SCRIPTS -->

<script>
    let lastTicketCount = -1;
    const BASE_URL_NOTIF = "<?= base_url() ?>";

    function checkAdminNotifs() {
        fetch(`${BASE_URL_NOTIF}/ajax/get_header_notifs.php`)
            .then(res => res.json())
            .then(data => {
                if (data.ok) {
                    const currentCount = data.open_tickets;
                    const badge = document.getElementById('ticket-badge');

                    if (badge) {
                        badge.textContent = currentCount;
                        badge.style.display = currentCount > 0 ? 'inline-block' : 'none';
                    }

                    // اگر تیکت جدیدی اضافه شده بود (نسبت به چک قبلی)
                    if (lastTicketCount !== -1 && currentCount > lastTicketCount) {
                        const audio = document.getElementById('notifSound');
                        if (audio) audio.play().catch(e => console.log('Audio play prevented by browser'));

                        if (window.Toastify) {
                            Toastify({
                                text: "تیکت پشتیبانی جدید دریافت شد!",
                                duration: 5000,
                                backgroundColor: "#28a745"
                            }).showToast();
                        }
                    }
                    lastTicketCount = currentCount;
                }
            }).catch(e => console.error(e));
    }

    // چک کردن اولیه و سپس هر 15 ثانیه یک‌بار
    document.addEventListener("DOMContentLoaded", () => {
        checkAdminNotifs();
        setInterval(checkAdminNotifs, 15000);
    });
</script>

</body>

</html>