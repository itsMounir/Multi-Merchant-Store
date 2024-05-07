<?php

return [

    'required' => 'حقل :attribute مطلوب.',
    'nullable' => 'حقل :attribute قد يكون فارغًا.',
    'numeric' => 'يجب أن يكون حقل :attribute رقمًا.',
    'string' => 'يجب أن يكون حقل :attribute نصًا.',
    'integer' => 'يجب أن يكون حقل :attribute عددًا صحيحًا.',
    'array' => 'يجب أن يكون حقل :attribute مصفوفة.',
    'date' => 'يجب أن يكون حقل :attribute تاريخًا صالحًا.',
    'image' => 'يجب أن يكون حقل :attribute صورة.',
    'unique' => 'حقل :attribute مُستخدم بالفعل.',
    'exists' => 'القيمة المحددة لحقل :attribute غير موجودة.',
    'min' => [
        'numeric' => 'يجب أن يكون حقل :attribute على الأقل :min.',
        'string'  => 'يجب أن يكون حقل :attribute على الأقل :min أحرف.',
        'file'    => 'يجب أن يكون حجم الملف :attribute على الأقل :min كيلوبايت.',
        'array'   => 'يجب أن يحتوي حقل :attribute على الأقل :min عناصر.',
    ],
    'digits' => 'يجب أن يحتوي حقل :attribute على :digits أرقام.',
    'after_or_equal' => 'يجب أن يكون تاريخ حقل :attribute بعد أو يساوي :date.',
    'lt' => [
        'numeric' => 'يجب أن يكون حقل :attribute أقل من :value.',
    ],
    'required_if' => 'حقل :attribute مطلوب    ',
    'distinct' => 'حقل :attribute يحتوي على قيمة مكررة.',


    'discount.*.starting_date' => 'تاريخ البدء مطلوب ويجب أن يكون تاريخًا صالحًا.',
    'discount.*.expiring_date' => 'تاريخ الانتهاء مطلوب ويجب أن يكون تاريخًا صالحًا وبعد أو يساوي تاريخ البدء.',
    'discount.*.min_bill_price' => 'الحد الأدنى لسعر الفاتورة مطلوب ويجب أن يكون رقمًا ولا يقل عن 0.',
    'discount.*.discount_price' => 'سعر الخصم مطلوب ويجب أن يكون رقمًا وأقل من الحد الأدنى لسعر الفاتورة.',

    'custom' => [
        'to_sites' => [
            'required' => 'حقل مناطق التوزيع مطلوب.',
            'array' => 'يجب أن يكون حقل مناطق التوزيع عبارة عن مصفوفة.',
        ],
        'to_sites.*' => [
            'required' => 'كل عنصر في مناطق التوزيع مطلوب.',
            'integer' => 'يجب أن يكون كل عنصر في مناطق التوزيع عددًا صحيحًا.',
            'distinct' => 'يجب ألا تتكرر القيم في حقل مناطق التوزيع.',
            'exists' => 'العنصر المحدد في مناطق التوزيع غير موجود في جدول المدن.',
        ],
    ],




    'phone_number' => 'رقم الهاتف مطلوب ويجب أن يكون نصًا وفريدًا ويحتوي على 11 رقمًا.',
    'password' => 'كلمة المرور مطلوبة ويجب أن تكون نصًا ولا تقل عن 6 أحرف.',
    'supplier_category_id' => 'معرف فئة المورد مطلوب ويجب أن يكون عددًا صحيحًا وموجودًا في جدول فئات الموردين.',
    'city_id' => 'معرف المدينة مطلوب ويجب أن يكون عددًا صحيحًا وموجودًا في جدول المدن.',


    'product_id' => ' حقل المنتج مطلوب.',
    'price' => 'السعر مطلوب ويجب أن يكون رقمًا.',
    'max_selling_quantity' => 'الكمية القصوى للبيع مطلوبة.',


    'attributes' => [
        'discount.*.discount_price' => 'سعر الخصم',
        'discount.*.min_bill_price'=>'سعر الفاتورة',
        'discount' => 'الخصم',
        'has_offer' => 'يوجد عرض',
        'offer_price' => 'سعر العرض',
        'max_offer_quantity' => 'الكمية القصوى للعرض',
        'offer_expires_at' => 'تاريخ انتهاء العرض',
        'to_sites' => 'مواقع التوزيع',
        'to_sites_id' => ' مواقع النوزيع',
        'first_name' => 'الاسم الأول',
        'middle_name' => 'اسم الأب',
        'last_name' => 'الاسم الأخير',
        'phone_number' => 'رقم الهاتف',
        'password' => 'كلمة المرور',
        'verification_code'=>'رمز التحقق',
        'store_name' => 'اسم المتجر',
        'supplier_category_id' => 'فئة المورد',
        'min_bill_price' => 'الحد الأدنى لسعر الفاتورة',
        'min_selling_quantity' => 'الحد الأدنى لكمية البيع',
        'delivery_duration' => 'مدة التوصيل',
        'city_id' => 'المدينة',
        'image' => 'الصورة',
        'product_id' => 'المنتج',
        'price' => 'السعر',
        'max_selling_quantity' => 'الكمية القصوى للبيع',
       // 'to_sites' => 'مواقع التوزيع',
        'products' => 'المنتجات',
        'products.*.quantity' => 'كمية المنتجات',
        'rejection_reason' => 'سبب الرفض',
        'status' => 'الحالة',
        'recieved_price' => 'السعر المستلم',
        'is_available'=>'المنتج متاح',
    ],
];
