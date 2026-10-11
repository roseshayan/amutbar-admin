import http from 'k6/http';
import { check, sleep } from 'k6';
import { BASE_URL, HEADERS, DEFAULT_THRESHOLDS } from '../config.js';

export const options = {
    stages: [
        { duration: '30s', target: 50 },   // شیب اولیه تا ۵۰ کاربر همزمان
        { duration: '1m', target: 150 },   // افزایش به ۱۵۰ کاربر همزمان
        { duration: '2m', target: 300 },   // لود پایدار ۳۰۰ کاربر همزمان
        { duration: '30s', target: 0 },    // کاهش لود و اتمام تست
    ],
    thresholds: DEFAULT_THRESHOLDS,
};

const searchQueries = ['تهران', 'مشهد', 'اصفهان', 'شیراز', 'تبریز', 'یزد', 'اهواز', 'کرمان'];

export default function () {
    // 1. استعلام کانفیگ اپلیکیشن
    let res = http.get(`${BASE_URL}/meta/app-config?target_app_id=1`, { headers: HEADERS });
    check(res, { 'meta/app-config 200': (r) => r.status === 200 });

    // 2. بنرهای صفحه اصلی
    res = http.get(`${BASE_URL}/banners?target_app_id=1&placement=dashboard`, { headers: HEADERS });
    check(res, { 'banners 200': (r) => r.status === 200 });

    // 3. لیست استان‌ها
    res = http.get(`${BASE_URL}/meta/provinces`, { headers: HEADERS });
    check(res, { 'provinces 200': (r) => r.status === 200 });

    // 4. لیست شهرهای یک استان (مثلاً استان تهران=۸ یا خراسان=۱۱)
    let provId = (Math.random() > 0.5) ? 8 : 11;
    res = http.get(`${BASE_URL}/meta/cities?province_id=${provId}`, { headers: HEADERS });
    check(res, { 'cities 200': (r) => r.status === 200 });

    // 5. سرچ زنده شهرها (کوئری‌های پایگاه داده)
    let q = searchQueries[Math.floor(Math.random() * searchQueries.length)];
    res = http.get(`${BASE_URL}/meta/cities/search?q=${encodeURIComponent(q)}`, { headers: HEADERS });
    check(res, { 'cities search 200': (r) => r.status === 200 });

    // 6. سوالات متداول
    res = http.get(`${BASE_URL}/faqs?target_app_id=1`, { headers: HEADERS });
    check(res, { 'faqs 200': (r) => r.status === 200 });

    sleep(Math.random() * 2 + 0.5); // شبیه‌سازی فاصله طبیعی بین درخواست‌های کاربر (Think Time)
}
