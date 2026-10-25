@echo off
chcp 65001 > nul
echo =========================================================
echo       استرس تست اپلیکیشن اعلام بار آموت‌بار (Cargo App)
echo =========================================================
set /p TARGET_URL="آدرس سرور را وارد کنید (پیش‌فرض: http://localhost/amutbar-admin/api/v1): "
if "%TARGET_URL%"=="" set TARGET_URL=http://localhost/amutbar-admin/api/v1

set /p VUS="تعداد کاربران همزمان (پیش‌فرض: 30): "
if "%VUS%"=="" set VUS=30

set /p DURATION="مدت زمان به ثانیه (پیش‌فرض: 20): "
if "%DURATION%"=="" set DURATION=20

python "%~dp0stress_tester.py" --target "%TARGET_URL%" --app cargo --concurrency %VUS% --duration %DURATION%
pause
