#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Amutbar Stress Testing Suite (Driver & Cargo Apps)
Comprehensive load and stress testing tool for Amutbar backend and mobile APIs.
Usage:
    python stress_tester.py --target http://127.0.0.1/amutbar-admin/api/v1 --app both --concurrency 25 --duration 20
"""

import sys
import os
import time
import json
import argparse
import random
import urllib.request
import urllib.error
import ssl
from concurrent.futures import ThreadPoolExecutor, as_completed
from collections import defaultdict
import math

class StressTester:
    def __init__(self, target_url, app_type='both', concurrency=20, duration=15, driver_token=None, company_token=None):
        self.target_url = target_url.rstrip('/')
        self.app_type = app_type
        self.concurrency = concurrency
        self.duration = duration
        self.driver_token = driver_token or ''
        self.company_token = company_token or ''
        
        self.results = []
        self.start_time = 0
        self.end_time = 0
        self.stop_requested = False

        # Ignore TLS errors for local/staging self-signed certs
        self.ctx = ssl.create_default_context()
        self.ctx.check_hostname = False
        self.ctx.verify_mode = ssl.CERT_NONE

    def _make_request(self, method, endpoint, payload=None, token=None):
        url = f"{self.target_url}{endpoint}"
        headers = {
            'User-Agent': 'Amutbar-StressTest-Runner/2.0',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
        if token:
            headers['Authorization'] = f"Bearer {token}"

        data = json.dumps(payload).encode('utf-8') if payload else None
        req = urllib.request.Request(url, data=data, headers=headers, method=method)

        t0 = time.perf_counter()
        status = 0
        byte_count = 0
        error_msg = None

        try:
            with urllib.request.urlopen(req, timeout=15, context=self.ctx) as resp:
                status = resp.status
                body = resp.read()
                byte_count = len(body)
        except urllib.error.HTTPError as e:
            status = e.code
            try:
                byte_count = len(e.read())
            except Exception:
                pass
        except urllib.error.URLError as e:
            status = 0
            error_msg = str(e.reason)
        except Exception as e:
            status = 0
            error_msg = str(e)

        t1 = time.perf_counter()
        duration_ms = (t1 - t0) * 1000

        return {
            'endpoint': endpoint,
            'method': method,
            'status': status,
            'duration_ms': duration_ms,
            'bytes': byte_count,
            'error': error_msg,
            'timestamp': t1
        }

    def _worker_loop(self):
        endpoints_driver = [
            ('GET', '/meta/app-config?target_app_id=1', None, None),
            ('GET', '/banners?target_app_id=1&placement=dashboard', None, None),
            ('GET', '/meta/provinces', None, None),
            ('GET', '/meta/cities/search?q=%D8%AA%D9%87%D8%B1%D8%A7%D9%86', None, None),
            ('GET', '/meta/vehicle-types', None, None),
            ('GET', '/driver/loads?origin_province_id=8', None, self.driver_token),
            ('GET', '/auth/check-ip', None, None),
        ]

        endpoints_cargo = [
            ('GET', '/meta/app-config?target_app_id=2', None, None),
            ('GET', '/banners?target_app_id=2&placement=dashboard', None, None),
            ('GET', '/meta/cities/search?q=%D9%85%D8%B4%D9%87%D8%AF', None, None),
            ('GET', '/meta/cargos/search?q=%D8%A2%D9%87%D9%86', None, None),
            ('GET', '/company/loads', None, self.company_token),
            ('GET', '/auth/check-ip', None, None),
        ]

        if self.app_type == 'driver':
            pool = endpoints_driver
        elif self.app_type == 'cargo':
            pool = endpoints_cargo
        else:
            pool = endpoints_driver + endpoints_cargo

        while not self.stop_requested and (time.time() - self.start_time) < self.duration:
            item = random.choice(pool)
            method, ep, payload, tok = item
            
            # 5% chance to simulate OTP request
            if random.random() < 0.05:
                mock_phone = f"0912{random.randint(1000000, 9999999)}"
                u_type = 1 if self.app_type == 'driver' else 2
                res = self._make_request('POST', '/auth/request-otp', {'phone': mock_phone, 'user_type': u_type})
                self.results.append(res)
            else:
                res = self._make_request(method, ep, payload, tok)
                self.results.append(res)

            # Micro-pause simulating client UI render
            time.sleep(random.uniform(0.02, 0.15))

    def run(self):
        print(f"\n{'='*70}")
        print(f"🚀 شروع استرس تست آموت‌بار (Amutbar Load & Stress Tester)")
        print(f"{'='*70}")
        print(f"🎯 آدرس هدف:       {self.target_url}")
        print(f"📱 هدف اپلیکیشن:   {self.app_type.upper()} ({'رانندگان و اعلام بار' if self.app_type == 'both' else ('رانندگان' if self.app_type == 'driver' else 'اعلام بار')})")
        print(f"👥 کاربران همزمان: {self.concurrency} VU (Virtual Users)")
        print(f"⏱️ مدت زمان تست:   {self.duration} ثانیه")
        print(f"{'='*70}\n")

        self.start_time = time.time()
        with ThreadPoolExecutor(max_workers=self.concurrency) as executor:
            futures = [executor.submit(self._worker_loop) for _ in range(self.concurrency)]
            
            # Progress loop
            while time.time() - self.start_time < self.duration:
                elapsed = time.time() - self.start_time
                done = len(self.results)
                rps = done / elapsed if elapsed > 0 else 0
                sys.stdout.write(f"\r⏳ در حال اجرا... {elapsed:.1f}s / {self.duration}s | درخواست‌ها: {done:,} | نرخ: {rps:.1f} req/s")
                sys.stdout.flush()
                time.sleep(0.5)

            self.stop_requested = True
            for f in as_completed(futures):
                pass

        self.end_time = time.time()
        print(f"\n\n✅ تست با موفقیت به پایان رسید. در حال محاسبه آمار ریز و تحلیل نتایج...\n")
        return self._generate_report()

    def _generate_report(self):
        total_reqs = len(self.results)
        if total_reqs == 0:
            print("⚠️ هیچ درخواستی ثبت نشد (احتمالاً سرور در دسترس نبوده است).")
            return None

        total_time = self.end_time - self.start_time
        durations = sorted([r['duration_ms'] for r in self.results])
        total_bytes = sum(r['bytes'] for r in self.results)

        status_counts = defaultdict(int)
        for r in self.results:
            status_counts[r['status']] += 1

        success_reqs = sum(c for s, c in status_counts.items() if 200 <= s < 400)
        client_errs = sum(c for s, c in status_counts.items() if 400 <= s < 500)
        server_errs = sum(c for s, c in status_counts.items() if s >= 500 or s == 0)

        rps = total_reqs / total_time if total_time > 0 else 0
        bandwidth_kbps = (total_bytes / 1024) / total_time if total_time > 0 else 0

        # Percentiles
        p50 = durations[int(len(durations) * 0.50)]
        p90 = durations[int(len(durations) * 0.90)]
        p95 = durations[int(len(durations) * 0.95)]
        p99 = durations[int(len(durations) * 0.99)]
        min_d = durations[0]
        max_d = durations[-1]
        avg_d = sum(durations) / len(durations)

        # Per-endpoint stats
        ep_stats = defaultdict(lambda: {'count': 0, 'durations': [], 'errors': 0, 'statuses': defaultdict(int)})
        for r in self.results:
            ep = f"{r['method']} {r['endpoint']}"
            ep_stats[ep]['count'] += 1
            ep_stats[ep]['durations'].append(r['duration_ms'])
            ep_stats[ep]['statuses'][r['status']] += 1
            if r['status'] >= 400 or r['status'] == 0:
                ep_stats[ep]['errors'] += 1

        # Print Beautiful Table to Console
        print(f"{'='*70}")
        print(f"📊 خلاصه نتایج کلیدی کارایی و استرس (Executive Summary)")
        print(f"{'='*70}")
        print(f"• کل درخواست‌های ارسال‌شده:   {total_reqs:,}")
        print(f"• میانگین نرخ پردازش (RPS):   {rps:.2f} req/sec")
        print(f"• پهنای باند مصرفی:           {bandwidth_kbps:.2f} KB/sec")
        print(f"• موفق (2xx / 3xx):           {success_reqs:,} ({(success_reqs/total_reqs)*100:.1f}%)")
        print(f"• خطای کلاینت (4xx):          {client_errs:,} ({(client_errs/total_reqs)*100:.1f}%)")
        print(f"• خطای سرور (5xx / اتصال):    {server_errs:,} ({(server_errs/total_reqs)*100:.1f}%)")
        print(f"{'-'*70}")
        print(f"⏱️ توزیع زمان پاسخ‌دهی (Latency Distribution):")
        print(f"  - کمترین (Min):              {min_d:.1f} ms")
        print(f"  - میانگین (Average):         {avg_d:.1f} ms")
        print(f"  - میانه (Median - p50):      {p50:.1f} ms")
        print(f"  - صدک ۹۰ (p90):              {p90:.1f} ms")
        print(f"  - صدک ۹۵ (p95):              {p95:.1f} ms {'✅ استاندارد (<500ms)' if p95 < 500 else '⚠️ نیاز به بهینه‌سازی'}")
        print(f"  - صدک ۹۹ (p99):              {p99:.1f} ms")
        print(f"  - بیشترین (Max):             {max_d:.1f} ms")
        print(f"{'='*70}\n")

        print("📋 جزئیات به تفکیک اندپوینت‌های اپلیکیشن:")
        print(f"{'اندپوینت':<45} | {'تعداد':<6} | {'میانگین':<8} | {'p95':<8} | {'خطا':<6}")
        print("-" * 75)
        for ep, s in sorted(ep_stats.items(), key=lambda x: x[1]['count'], reverse=True):
            d_list = sorted(s['durations'])
            ep_avg = sum(d_list) / len(d_list)
            ep_p95 = d_list[int(len(d_list) * 0.95)]
            err_pct = (s['errors'] / s['count']) * 100
            print(f"{ep[:44]:<45} | {s['count']:<6} | {ep_avg:6.1f}ms | {ep_p95:6.1f}ms | {err_pct:5.1f}%")

        report_data = {
            'target_url': self.target_url,
            'app_type': self.app_type,
            'concurrency': self.concurrency,
            'duration_sec': self.duration,
            'total_requests': total_reqs,
            'rps': rps,
            'bandwidth_kbps': bandwidth_kbps,
            'success_rate': (success_reqs / total_reqs) * 100,
            'server_error_rate': (server_errs / total_reqs) * 100,
            'latency': {
                'min': min_d,
                'avg': avg_d,
                'p50': p50,
                'p90': p90,
                'p95': p95,
                'p99': p99,
                'max': max_d
            },
            'status_codes': dict(status_counts),
            'endpoints': {
                ep: {
                    'count': s['count'],
                    'avg_ms': sum(s['durations']) / len(s['durations']),
                    'p95_ms': sorted(s['durations'])[int(len(s['durations']) * 0.95)],
                    'errors': s['errors']
                } for ep, s in ep_stats.items()
            }
        }

        # Save JSON artifact
        json_path = os.path.join(os.path.dirname(__file__), 'stress_report_latest.json')
        try:
            with open(json_path, 'w', encoding='utf-8') as f:
                json.dump(report_data, f, ensure_ascii=False, indent=2)
            print(f"\n💾 گزارش ساختاریافته در مسیر {json_path} ذخیره شد.")
        except Exception:
            pass

        return report_data

if __name__ == '__main__':
    parser = argparse.ArgumentParser(description='Amutbar Stress Testing Tool')
    parser.add_argument('--target', default='http://localhost/amutbar-admin/api/v1', help='Target API Base URL')
    parser.add_argument('--app', choices=['driver', 'cargo', 'both'], default='both', help='App to simulate (driver, cargo, or both)')
    parser.add_argument('--concurrency', type=int, default=20, help='Concurrent Virtual Users (VUs)')
    parser.add_argument('--duration', type=int, default=15, help='Duration in seconds')
    parser.add_argument('--driver-token', default='', help='Driver Bearer Token')
    parser.add_argument('--company-token', default='', help='Company/Cargo Bearer Token')

    args = parser.parse_args()
    tester = StressTester(
        target_url=args.target,
        app_type=args.app,
        concurrency=args.concurrency,
        duration=args.duration,
        driver_token=args.driver_token,
        company_token=args.company_token
    )
    tester.run()
