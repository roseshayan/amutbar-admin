"use strict";

window.createpassword = function (inputId, ele) {
    let input = document.getElementById(inputId);
    if (!input) input = ele.closest(".position-relative")?.querySelector("input");
    if (!input) return;

    const toText = (input.type === "password");
    input.type = toText ? "text" : "password";

    // اگر قالب از text-security برای نقطه‌کردن استفاده کرده باشد
    input.style.webkitTextSecurity = toText ? "none" : "disc";

    const iconEl = ele.querySelector("i") || ele.firstElementChild;
    if (!iconEl || !iconEl.classList) return;

    if (toText) {
        iconEl.classList.add("ri-eye-line");
        iconEl.classList.remove("ri-eye-off-line");
    } else {
        iconEl.classList.remove("ri-eye-line");
        iconEl.classList.add("ri-eye-off-line");
    }
};
