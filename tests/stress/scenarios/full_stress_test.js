import http from 'k6/http';
import { check, sleep, group } from 'k6';
import { BASE_URL, HEADERS, DEFAULT_THRESHOLDS } from '../config.js';

/**
 * تست سنگین ترکیبی (Full Stress & Spike Test)
 * هدف: یافتن نقطه شکست (Breaking Point) سرور و سنجش ماکزیمم ظرفیت RPS و کاربران همزمان
 */

export const options = {
    stages: [
        { duration: '30s', target: 50 },    // گرم کردن سرور (Warm-up)
        { duration: '1m', target: 150 },    // لود معمولی کاری
        { duration: '2m', target: 300 },    // لود سنگین
        { duration: '2m', target: 500 },    // حداکثر توان مورد انتظار
        { duration: '1m', target: 800 },    // جهش ناگهانی (Spike Test) برای سنجش توان سرور
        { duration: '1m', target: 200 },    // سنجش بازگشت خودکار سرور پس از عبور از اسپایک (Recovery)
        { duration: '30s', target: 0 },     // اتمام تست
    ],
    thresholds: {
        http_req_duration: ['p(95)<600', 'p(99)<1200'],
        http_req_failed: ['rate<0.02'], // خطای زیر ۲ درصد در شدیدترین حالت
    },
};

const cities = ['تهران', 'مشهد', 'اصفهان', 'شیراز', 'تبریز', 'یزد', 'اهواز', 'رشت', 'ساری', 'کرمان'];

export default function () {
    const roll = Math.random();

    group('Public Read Endpoints', function () {
        // ۸۰ درصد درخواست‌ها: متادیتا، کانفیگ، بنرها و جستجوها
        let resConfig = http.get(`${BASE_URL}/meta/app-config?target_app_id=${roll > 0.5 ? 1 : 2}`, { headers: HEADERS });
        check(resConfig, { 'app-config 200': (r) => r.status === 200 });

        let resBanners = http.get(`${BASE_URL}/banners?target_app_id=1&placement=dashboard`, { headers: HEADERS });
        check(resBanners, { 'banners 200': (r) => r.status === 200 });

        let resProv = http.get(`${BASE_URL}/meta/provinces`, { headers: HEADERS });
        check(resProv, { 'provinces 200': (r) => r.status === 200 });

        let randCity = cities[Math.floor(Math.random() * cities.length)];
        let resSearch = http.get(`${BASE_URL}/meta/cities/search?q=${encodeURIComponent(randCity)}`, { headers: HEADERS });
        check(resSearch, { 'search city 200': (r) => r.status === 200 });
    });

    if (roll < 0.3) {
        group('Driver Advanced Load Search', function () {
            let resLoads = http.get(`${BASE_URL}/driver/loads?origin_province_id=8&dest_province_id=11`, { headers: HEADERS });
            check(resLoads, { 'driver loads query 200': (r) => r.status === 200 || r.status === 401 });
        });
    }

    if (roll < 0.1) {
        group('Auth OTP Request', function () {
            let otpPayload = JSON.stringify({
                phone: '0912' + Math.floor(1000000 + Math.random() * 9000000),
                user_type: 1
            });
            let resOtp = http.post(`${BASE_URL}/auth/request-otp`, otpPayload, { headers: HEADERS });
            check(resOtp, { 'request-otp processed': (r) => r.status === 200 || r.status === 429 || r.status === 400 });
        });
    }

    sleep(Math.random() * 1.5 + 0.5);
}
