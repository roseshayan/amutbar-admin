-- 2026_10_18_000010_api_ir_services_expansion.sql
-- Add full api.ir services (ShahkarLite, Shahkar, PersonInfo, PersonData, CallOTP, CallOTPalt, DrivingLicense, DrivingLisense, DrivingScore)
-- and add configurable settings for driving license and voice OTP.

SET @api_ir_id = (SELECT id FROM external_api_providers WHERE slug='api_ir' LIMIT 1);

-- Make sure obsolete services or endpoint paths are updated
UPDATE identity_verification_services 
SET endpoint_path = '/api/sw1/ShahkarLite', is_active = 1, sort_order = 10 
WHERE code = 'ShahkarLite';

UPDATE identity_verification_services 
SET endpoint_path = '/api/sw1/Shahkar', is_active = 1, sort_order = 20 
WHERE code = 'Shahkar';

-- Disable obsolete Shahkar2 & VideoMatch if present
UPDATE identity_verification_services 
SET is_active = 0 
WHERE code IN ('Shahkar2', 'VideoMatch');

-- PersonInfo
INSERT INTO identity_verification_services (provider_id, code, title, description, endpoint_path, http_method, subject_kind, is_active, sort_order, created_at, updated_at)
SELECT @api_ir_id, 'PersonInfo', 'استعلام مشخصات هویتی (بدون تصویر)', 'استعلام اطلاعات سجلی و حیات فرد از ثبت احوال بر اساس کد ملی و تاریخ تولد', '/api/sw1/PersonInfo', 'POST', 0, 1, 30, NOW(3), NOW(3)
WHERE NOT EXISTS (SELECT 1 FROM identity_verification_services WHERE code='PersonInfo');

-- PersonData
INSERT INTO identity_verification_services (provider_id, code, title, description, endpoint_path, http_method, subject_kind, is_active, sort_order, created_at, updated_at)
SELECT @api_ir_id, 'PersonData', 'استعلام مشخصات و تصویر هویتی', 'استعلام مشخصات سجلی به همراه تصویر چهره پرسنلی از سازمان ثبت احوال', '/api/sw1/PersonData', 'POST', 0, 1, 40, NOW(3), NOW(3)
WHERE NOT EXISTS (SELECT 1 FROM identity_verification_services WHERE code='PersonData');

-- VideoVerifySpeechText
INSERT INTO identity_verification_services (provider_id, code, title, description, endpoint_path, http_method, subject_kind, is_active, sort_order, created_at, updated_at)
SELECT @api_ir_id, 'VideoVerifySpeechText', 'متن چالش احراز ویدئویی', 'دریافت جمله تصادفی جهت قرائت در زمان ضبط ویدئوی احراز هویت', '/api/sw1/VideoVerifySpeechText', 'POST', 0, 1, 50, NOW(3), NOW(3)
WHERE NOT EXISTS (SELECT 1 FROM identity_verification_services WHERE code='VideoVerifySpeechText');

-- CallOTP
INSERT INTO identity_verification_services (provider_id, code, title, description, endpoint_path, http_method, subject_kind, is_active, sort_order, created_at, updated_at)
SELECT @api_ir_id, 'CallOTP', 'تماس صوتی اعلام کد OTP (اصلی)', 'تماس صوتی خودکار جهت خواندن کد تایید در صورت اختلال پیامک', '/api/sw1/CallOTP', 'POST', 0, 1, 70, NOW(3), NOW(3)
WHERE NOT EXISTS (SELECT 1 FROM identity_verification_services WHERE code='CallOTP');

-- CallOTPalt
INSERT INTO identity_verification_services (provider_id, code, title, description, endpoint_path, http_method, subject_kind, is_active, sort_order, created_at, updated_at)
SELECT @api_ir_id, 'CallOTPalt', 'تماس صوتی اعلام کد OTP (جایگزین)', 'مسیر دوم تماس صوتی جهت خواندن کد تایید یکبار مصرف', '/api/sw1/CallOTPalt', 'POST', 0, 1, 80, NOW(3), NOW(3)
WHERE NOT EXISTS (SELECT 1 FROM identity_verification_services WHERE code='CallOTPalt');

-- DrivingLicense (New)
INSERT INTO identity_verification_services (provider_id, code, title, description, endpoint_path, http_method, subject_kind, is_active, sort_order, created_at, updated_at)
SELECT @api_ir_id, 'DrivingLicense', 'استعلام گواهینامه رانندگی (جدید)', 'استعلام وضعیت، سوابق و اصالت گواهینامه راننده (سوئیچ جدید)', '/api/sw1/DrivingLicense', 'POST', 1, 0, 90, NOW(3), NOW(3)
WHERE NOT EXISTS (SELECT 1 FROM identity_verification_services WHERE code='DrivingLicense');

-- DrivingLisense (Old)
INSERT INTO identity_verification_services (provider_id, code, title, description, endpoint_path, http_method, subject_kind, is_active, sort_order, created_at, updated_at)
SELECT @api_ir_id, 'DrivingLisense', 'استعلام گواهینامه رانندگی (قدیم)', 'استعلام وضعیت گواهینامه راننده (سوئیچ سابق / بک‌آپ)', '/api/sw1/DrivingLisense', 'POST', 1, 0, 100, NOW(3), NOW(3)
WHERE NOT EXISTS (SELECT 1 FROM identity_verification_services WHERE code='DrivingLisense');

-- DrivingScore
INSERT INTO identity_verification_services (provider_id, code, title, description, endpoint_path, http_method, subject_kind, is_active, sort_order, created_at, updated_at)
SELECT @api_ir_id, 'DrivingScore', 'استعلام نمره منفی گواهینامه', 'استعلام امتیاز و نمره منفی رانندگی بر اساس شماره گواهینامه و کد ملی', '/api/sw1/DrivingScore', 'POST', 1, 0, 110, NOW(3), NOW(3)
WHERE NOT EXISTS (SELECT 1 FROM identity_verification_services WHERE code='DrivingScore');

-- Insert default system settings
INSERT INTO system_settings (setting_key, setting_value, updated_at)
VALUES 
    ('verification.shahkar_service', 'ShahkarLite', NOW(3)),
    ('verification.driver.require_driving_license', '0', NOW(3)),
    ('verification.driver.driving_license_service', 'DrivingLicense', NOW(3)),
    ('auth.otp_voice_fallback', '1', NOW(3))
ON DUPLICATE KEY UPDATE updated_at = NOW(3);
