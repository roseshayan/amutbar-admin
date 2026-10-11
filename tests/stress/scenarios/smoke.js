import http from 'k6/http';
import { check, sleep } from 'k6';
import { BASE_URL, HEADERS } from '../config.js';

export const options = {
    vus: 2,
    duration: '20s',
    thresholds: {
        http_req_failed: ['rate<0.01'],
        http_req_duration: ['p(95)<300'],
    },
};

export default function () {
    // 1. Health check
    let res = http.get(`${BASE_URL}/health`, { headers: HEADERS });
    check(res, {
        'health is 200': (r) => r.status === 200,
        'health returns ok:true': (r) => r.json('ok') === true,
    });

    // 2. App config Driver
    res = http.get(`${BASE_URL}/meta/app-config?target_app_id=1`, { headers: HEADERS });
    check(res, {
        'app-config driver is 200': (r) => r.status === 200,
    });

    // 3. App config Cargo
    res = http.get(`${BASE_URL}/meta/app-config?target_app_id=2`, { headers: HEADERS });
    check(res, {
        'app-config cargo is 200': (r) => r.status === 200,
    });

    // 4. Provinces
    res = http.get(`${BASE_URL}/meta/provinces`, { headers: HEADERS });
    check(res, {
        'provinces is 200': (r) => r.status === 200,
        'provinces has items': (r) => Array.isArray(r.json('items')),
    });

    // 5. Vehicle types
    res = http.get(`${BASE_URL}/meta/vehicle-types`, { headers: HEADERS });
    check(res, {
        'vehicle-types is 200': (r) => r.status === 200,
    });

    // 6. Banners
    res = http.get(`${BASE_URL}/banners?target_app_id=1&placement=dashboard`, { headers: HEADERS });
    check(res, {
        'banners is 200': (r) => r.status === 200,
    });

    sleep(1);
}
