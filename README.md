# نظام إدارة الأخبار والفئات

     (PHP) هذا المشروع عبارة عن نظام إدارة أخبار وفئات مبني ب لغة    ،

يتيح للمستخدمين إنشاء وإدارة الأخبار والفئات بطريقة سهلة وفعالة. يعرض النظام الفئات بشكل مجلدات (Folder View) ويسمح بربط الأخبار بأكثر من فئة.
المستخدمين عباره عن (addmin - user)
يمكنك التسجيل كما تريد ك addmin او user

## المميزات

- إدارة الفئات (إنشاء، قراءة، تحديث، حذف)
- إدارة الأخبار (إنشاء، قراءة، تحديث، حذف)
- ربط الأخبار بفئات متعددة
- عرض الفئات بشكل مجلدات (Folder View)
- واجهة مستخدم سهلة الاستخدام
- تصميم متجاوب مع جميع الأجهزة
- دعم اللغة العربية والاتجاه من اليمين إلى اليسار (RTL)

## متطلبات النظام

- PHP 7.0 أو أحدث
- MySQL 5.6 أو أحدث
- خادم ويب (Apache، Nginx، إلخ)

## التثبيت

1. قم بنسخ ملفات المشروع إلى مجلد الخادم الخاص بك (مثل `htdocs` في XAMPP أو `www` في WampServer).

2. قم بإنشاء قاعدة بيانات جديدة باسم `news_system`.

3. قم باستيراد ملف `database.sql` إلى قاعدة البيانات التي أنشأتها:

   ```
   mysql -u username -p news_system < database.sql
   ```

   أو استخدم phpMyAdmin لاستيراد الملف.

4. قم بتعديل ملف الاتصال بقاعدة البيانات `config/database.php` بمعلومات قاعدة البيانات الخاصة بك:

   ```php
   private $host = 'localhost';
   private $db_name = 'category';
   private $username = 'your_username';
   private $password = 'your_password';
   ```

5. قم بتعديل متغير `$base_url` في ملف `includes/header.php` ليتناسب مع عنوان URL الخاص بموقعك:
   ```php
   $base_url = "http://localhost/";
   ```

## هيكل المشروع

```
/
├── admin/
│   ├── categories/
│   │   ├── create.php
│   │   ├── index.php
│   │   ├── update.php
│   │   └── view.php
│   └── news/
│       ├── create.php
│       ├── index.php
│       ├── update.php
│       └── view.php
├── assets/
│   └── css/
│       └── style.css
├── config/
│   └── database.php
├── includes/
│   ├── footer.php
│   └── header.php
├── models/
│   ├── Category.php
│   └── News.php
│   └── User.php
├── login/
│   ├── login.css
│   └── login.php
├── register/
│   ├── register.css
│   └── register.php
├── auth/
│   ├── login_process.php
    ├── logout.php
│   └── register_process.php

├── category.php
├── index.php
├── news.php
└── README.md
```

## الاستخدام

### الواجهة الأمامية

### as admin

- الصفحة الرئيسية: تعرض جميع الفئات بشكل مجلدات وأحدث الأخبار.
- صفحة الفئة: تعرض جميع الأخبار في فئة معينة.
- صفحة الخبر: تعرض تفاصيل خبر معين والفئات المرتبطة به.

### as user

- الصفحة الرئيسية: تعرض الفئات بشكل مجلدات وأحدث الأخبار الخاصه ب ال مستخدم .

- صفحة الخبر: تعرض تفاصيل خبر معين والفئات المرتبطة به.

### لوحة التحكم

- إدارة الفئات: `/admin/categories/index.php`

  - إضافة فئة جديدة
  - تعديل فئة موجودة
  - حذف فئة
  - عرض تفاصيل الفئة

- إدارة الأخبار: `/admin/news/index.php`
  - إضافة خبر جديد
  - تعديل خبر موجود
  - حذف خبر
  - عرض تفاصيل الخبر

## المساهمة

نرحب بمساهماتكم في تطوير هذا المشروع. يمكنكم إنشاء fork للمشروع وإرسال pull request بالتعديلات المقترحة.

## الترخيص

هذا المشروع متاح تحت ترخيص MIT. راجع ملف LICENSE للمزيد من المعلومات.
