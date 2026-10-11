"""
اسکریپت جایگزین تست لود با استفاده از ابزار پایتونی Locust (در صورتی که ترجیح می‌دهید از پایتون استفاده کنید)
نحوه اجرا:
    pip install locust
    locust -f tests/stress/scenarios/locustfile.py --host http://YOUR_SERVER_IP/api/v1
سپس پنل کاربری لکاست در مرورگر به آدرس http://localhost:8089 باز می‌شود.
"""

from locust import HttpUser, task, between
import random

CITIES = ['تهران', 'مشهد', 'اصفهان', 'شیراز', 'تبریز', 'یزد']

class AmutbarApiUser(HttpUser):
    wait_time = between(1, 3)

    @task(5)
    def test_app_config(self):
        target = random.choice([1, 2])
        self.client.get(f"/meta/app-config?target_app_id={target}", name="/meta/app-config")

    @task(4)
    def test_banners(self):
        self.client.get("/banners?target_app_id=1&placement=dashboard", name="/banners")

    @task(3)
    def test_provinces(self):
        self.client.get("/meta/provinces", name="/meta/provinces")

    @task(3)
    def test_cities_search(self):
        q = random.choice(CITIES)
        self.client.get(f"/meta/cities/search?q={q}", name="/meta/cities/search")

    @task(2)
    def test_vehicle_types(self):
        self.client.get("/meta/vehicle-types", name="/meta/vehicle-types")

    @task(1)
    def test_health(self):
        self.client.get("/health", name="/health")
