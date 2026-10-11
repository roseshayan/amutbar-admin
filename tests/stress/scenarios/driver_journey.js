import http from 'k6/http';
import { check, sleep } from 'k6';
import { BASE_URL, HEADERS, getAuthHeaders, DEFAULT_THRESHOLDS } from '../config.js';

export const options = {
    stages: [
        { duration: '30s', target: 20 },   // ورود رانندگان اولیه
        { duration: '1m', target: 80 },    // اوج بار کاری روزانه
        { duration: '2m', target: 200 },   // ساعات پیک اعلام بار
        { duration: '30s', target: 0 },    // بازگشت به حالت عادی
    ],
    thresholds: DEFAULT_THRESHOLDS,
};

// دریافت یا تولید توکن تستی راننده
export function setup() {
    let token = __ENV.DRIVER_TOKEN;
    if (!token) {
        console.log('Notice: For authenticated tests, provide DRIVER_TOKEN: k6 run -e DRIVER_TOKEN=xxx ...');
    }
    return { token: token || '' };
}

export default function (data) {
    const authHeaders = data.token ? getAuthHeaders(data.token) : HEADERS;

    // ۱. دریافت پروفایل راننده و اعلانات
    if (data.token) {
        let resMe = http.get(`${BASE_URL}/me`, { headers: authHeaders });
        check(resMe, { 'me status 200': (r) => r.status === 200 });

        let resNotifs = http.get(`${BASE_URL}/me/notifications/unread-count`, { headers: authHeaders });
        check(resNotifs, { 'unread count 200': (r) => r.status === 200 });
    }

    // ۲. بارگذاری لیست بارهای پیشنهادی (با محاسبات فاصله جغرافیایی و مقایسه نرخ بازار)
    let prov = (Math.random() > 0.5) ? 8 : 11;
    let resLoads = http.get(`${BASE_URL}/driver/loads?origin_province_id=${prov}`, { headers: authHeaders });
    let loadsOk = check(resLoads, {
        'driver/loads status 200': (r) => r.status === 200,
        'driver/loads returns items': (r) => r.json('ok') === true,
    });

    // ۳. اگر باری یافت شد، جزئیات یکی از بارها را مشاهده می‌کند
    if (loadsOk) {
        try {
            let json = resLoads.json();
            let items = json.items || [];
            if (items.length > 0) {
                let firstLoad = items[0];
                let loadId = firstLoad.id;

                sleep(1); // زمان مکث راننده برای بررسی لیست

                // مشاهده جزئیات تکمیلی بار
                let resSingle = http.get(`${BASE_URL}/driver/loads/${loadId}`, { headers: authHeaders });
                check(resSingle, { 'driver single load 200': (r) => r.status === 200 });

                // ۴. شبیه‌سازی لمس دکمه تماس با صاحب بار
                if (data.token) {
                    let callPayload = JSON.stringify({
                        load_id: loadId,
                        client_platform: 'android',
                        client_version: 12
                    });
                    let resCall = http.post(`${BASE_URL}/driver/calls/log`, callPayload, { headers: authHeaders });
                    check(resCall, { 'call log recorded': (r) => r.status === 200 });
                }
            }
        } catch (e) {
            // خطا در پردازش پاسخ
        }
    }

    // ۵. ارسال دسته‌ای لاگ فعالیت اپ رانندگان (Telemetry)
    if (data.token) {
        let activityPayload = JSON.stringify({
            events: [
                { event_key: 'app_open', timestamp: Date.now() },
                { event_key: 'scroll_loads', timestamp: Date.now() + 1000 }
            ],
            client_platform: 'android',
            client_version_code: 12
        });
        let resActivity = http.post(`${BASE_URL}/driver/activity`, activityPayload, { headers: authHeaders });
        check(resActivity, { 'driver activity 200': (r) => r.status === 200 });
    }

    sleep(Math.random() * 3 + 1);
}
