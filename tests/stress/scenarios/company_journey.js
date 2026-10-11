import http from 'k6/http';
import { check, sleep } from 'k6';
import { BASE_URL, HEADERS, getAuthHeaders, DEFAULT_THRESHOLDS } from '../config.js';

export const options = {
    stages: [
        { duration: '30s', target: 10 },
        { duration: '1m', target: 40 },
        { duration: '1m', target: 80 },
        { duration: '30s', target: 0 },
    ],
    thresholds: DEFAULT_THRESHOLDS,
};

export function setup() {
    let token = __ENV.COMPANY_TOKEN;
    if (!token) {
        console.log('Notice: For authenticated tests, provide COMPANY_TOKEN: k6 run -e COMPANY_TOKEN=xxx ...');
    }
    return { token: token || '' };
}

export default function (data) {
    if (!data.token) {
        // در صورت نبود توکن، فقط مسیرهای عمومی مرتبط با باربری تست می‌شوند
        let res = http.get(`${BASE_URL}/meta/cargos/search?q=آهن`, { headers: HEADERS });
        check(res, { 'cargo search 200': (r) => r.status === 200 });
        sleep(1);
        return;
    }

    const authHeaders = getAuthHeaders(data.token);

    // ۱. دریافت پروفایل شرکت
    let resMe = http.get(`${BASE_URL}/company/me`, { headers: authHeaders });
    check(resMe, { 'company/me 200': (r) => r.status === 200 });

    // ۲. مشاهده لیست بارهای من
    let resLoads = http.get(`${BASE_URL}/company/loads`, { headers: authHeaders });
    check(resLoads, { 'company/loads list 200': (r) => r.status === 200 });

    // ۳. ثبت اعلام بار جدید
    let newLoadPayload = JSON.stringify({
        origin_city_id: 101, // تهران
        dest_city_id: 205,   // مشهد
        origin_address: 'انبار تست استرس',
        dest_address: 'مقصد تست استرس',
        load_type: 1,
        primary_vehicle_type_id: 5,
        cargo_type_id: 15,
        weight: 22,
        is_tonnage_free: '0',
        price_type: 1,
        proposed_price: Math.floor(Math.random() * 5000000) + 15000000,
        phone_coordination: '09121112233',
        description: 'بار تستی استرس لود',
        has_insurance: '1',
        insurance_value: 400000000
    });

    let resCreate = http.post(`${BASE_URL}/company/loads`, newLoadPayload, { headers: authHeaders });
    let createOk = check(resCreate, {
        'create load status 200': (r) => r.status === 200,
        'create load returns load_id': (r) => r.json('load_id') !== undefined,
    });

    // ۴. بستن بار تازه ایجاد شده
    if (createOk) {
        try {
            let loadId = resCreate.json('load_id');
            sleep(1);
            let resClose = http.post(`${BASE_URL}/company/loads/${loadId}/close`, null, { headers: authHeaders });
            check(resClose, { 'close load 200': (r) => r.status === 200 });
        } catch (e) {
            // هندل خطا
        }
    }

    sleep(Math.random() * 4 + 2);
}
