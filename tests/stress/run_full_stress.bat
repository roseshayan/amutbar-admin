@echo off
chcp 65001 > nul
echo =========================================================
echo       استرس تست جامع همزمان رانندگان و اعلام بار
echo =========================================================
set /p TARGET_URL="آدرس سرور را وارد کنید (پیش‌فرض: http://localhost/amutbar-admin/api/v1): "
if "%TARGET_URL%"=="" set TARGET_URL=http://localhost/amutbar-admin/api/v1

set /p VUS="تعداد کاربران همزمان (پیش‌فرض: 50): "
if "%VUS%"=="" set VUS=50

set /p DURATION="مدت زمان به ثانیه (پیش‌فرض: 30): "
if "%DURATION%"=="" set DURATION=30

python "%~dp0stress_tester.py" --target "%TARGET_URL%" --app both --concurrency %VUS% --duration %DURATION%
pause
