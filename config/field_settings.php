<?php

// تنظیمات فیلدها برای انواع مختلف کاربران
return [
    'user' => [
        'full_name' => ['required' => true, 'label' => 'نام و نام خانوادگی'],
        'phone' => ['required' => true, 'label' => 'شماره موبایل'],
        'code_meli' => ['required' => false, 'label' => 'کد ملی'],
        'birth_date' => ['required' => false, 'label' => 'تاریخ تولد'],
        'gender' => ['required' => false, 'label' => 'جنسیت'],
        'avatar' => ['required' => false, 'label' => 'عکس پروفایل'],
    ],

    'driver' => [
        // اطلاعات پایه راننده
        'plate_number' => ['required' => true, 'label' => 'پلاک ماشین'],
        'vehicle_type_id' => ['required' => true, 'label' => 'نوع وسیله نقلیه'],

        // اطلاعات گواهینامه
        'national_card_image' => ['required' => true, 'label' => 'عکس کارت ملی'],
        'license_image' => ['required' => true, 'label' => 'عکس گواهینامه'],
        'license_serial' => ['required' => true, 'label' => 'شماره گواهینامه'],
        'license_base' => ['required' => false, 'label' => 'پایه گواهینامه'],

        // اطلاعات خودرو
        'vehicle_card_image' => ['required' => true, 'label' => 'عکس کارت ماشین'],
        'green_card_image' => ['required' => true, 'label' => 'عکس برگه سبز'],
        'insurance_image' => ['required' => true, 'label' => 'عکس بیمه نامه خودرو'],
        'insurance_number' => ['required' => true, 'label' => 'شماره بیمه نامه'],
        'insurance_expiry' => ['required' => true, 'label' => 'تاریخ اتمام بیمه'],
        'vin_number' => ['required' => false, 'label' => 'شماره VIN'],
        'engine_number' => ['required' => false, 'label' => 'شماره موتور'],
        'chassis_number' => ['required' => false, 'label' => 'شماره شاسی'],

        // اطلاعات تماس
        'issued_from' => ['required' => false, 'label' => 'صادره از'],
        'address' => ['required' => false, 'label' => 'آدرس'],
        'home_phone' => ['required' => false, 'label' => 'شماره منزل'],
        'postal_code' => ['required' => false, 'label' => 'کد پستی'],
        'extra_phones' => ['required' => false, 'label' => 'شماره موبایل اضافه'],

        // احراز هویت
        'verification_video' => ['required' => false, 'label' => 'ویدئو احراز هویت'],
    ],

    'company' => [
        'company_name' => ['required' => true, 'label' => 'نام شرکت'],
        'national_card_image' => ['required' => true, 'label' => 'عکس کارت ملی'],
        'registration_no' => ['required' => true, 'label' => 'شماره ثبت'],
        'registration_date' => ['required' => false, 'label' => 'تاریخ ثبت'],
        'economic_code' => ['required' => true, 'label' => 'شماره اقتصادی'],
        'address' => ['required' => false, 'label' => 'آدرس'],
        'postal_code' => ['required' => false, 'label' => 'کد پستی'],
    ]
];