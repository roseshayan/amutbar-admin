/**
 * تنظیمات مرکزی و مشترک استرس تست آموت‌بار (k6 Configuration)
 */

export const BASE_URL = __ENV.BASE_URL || 'http://localhost/amutbar-admin/api/v1';

export const HEADERS = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'User-Agent': 'Amutbar-StressTest-k6/1.0',
};

// دریافت هدر با توکن احراز هویت
export function getAuthHeaders(token) {
    return {
        ...HEADERS,
        'Authorization': `Bearer ${token}`,
    };
}

// استانداردهای پذیرش کیفی (Quality Gates / Thresholds)
export const DEFAULT_THRESHOLDS = {
    // ۹۵ درصد درخواست‌ها باید زیر ۵۰۰ میلی‌ثانیه پاسخ داده شوند
    http_req_duration: ['p(95)<500', 'p(99)<1000'],
    // نرخ خطای کلی کمتر از ۱ درصد باشد
    http_req_failed: ['rate<0.01'],
};

// کاربر تستی پیش‌فرض (در صورت وجود توکن در متغیر محیطی)
export const TEST_DRIVER_TOKEN = __ENV.DRIVER_TOKEN || '';
export const TEST_COMPANY_TOKEN = __ENV.COMPANY_TOKEN || '';
