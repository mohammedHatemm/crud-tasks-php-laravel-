# مشروع إدارة الأخبار (CRUD News Application)

## الوصف

هذا المشروع هو تطبيق ويب لإدارة الأخبار
(CRUD) تم تطويره باستخدام إطار عمل Laravel.
يتيح للمستخدمين إنشاء، قراءة، تحديث، وحذف المقالات الإخبارية والفئات الخاصة بها.
كما ييتيح ايضا التطبيق استخدام المهام المجدوله و يتيح يضا ارسال mail في حاله ان المستخدم عمل تسجيل دخول او عمل registeration

## الميزات

-   **إدارة الأخبار:** -. >إضافة، تعديل، حذف، وعرض المقالات الإخبارية.
-   **إدارة الفئات:** ->إنشاء فئات للأخبار وتعيين الأخبار لها.
-   **واجهة مستخدم بسيطة:** ->تصميم سهل الاستخدام لإدارة المحتوى.
-   **قاعدة بيانات:** استخدام MySQL لتخزين البيانات.
-   **ارسال اميلات :**ارسال mail في حاله ال login و ال registeration

## التقنيات المستخدمة

-   **الواجهة الخلفية (Backend):** Laravel (PHP Framework)
-   **قاعدة البيانات (Database):** MySQL
-   **الواجهة الأمامية (Frontend):** HTML, CSS, JavaScript (مع Blade Templates)
-   **إدارة الحزم (Package Management):** Composer (لـ PHP) و npm (لـ JavaScript)
-   \**mailارسال:*mailtrap

## المتطلبات

قبل البدء، تأكد من تثبيت ما يلي على جهازك:

-   PHP >= 8.1
-   Composer
-   Node.js و npm
-   MySQL Server

## التثبيت

اتبع الخطوات التالية لتشغيل المشروع محليًا:

1. **استنساخ المستودع (Clone the repository):**
    ```bash
    git clone <رابط المستودع الخاص بك>
    cd crud-news
    ```
2. **تنصيب الحزم (Install dependencies):**
    ```bash
    composer install
    npm install
    ```
3. **إنشاء ملف .env:**
    ```bash
    cp .env.example .env
    ```
    وعدل ملف .env لتكوين قاعدة البيانات الخاصة بك.
4. **إنشاء مفتاح التطبيق:**

5. إنشاء مفتاح التطبيق (Generate Application Key):

```bash
php artisan key:generate
```

6. **إنشاء قاعدة البيانات:**
    ```bash
    php artisan migrate
    ```
7. **تشغيل الخادم:**
    ```bash
    php artisan serve
    ```
8. **تشفيل المهام ف الخلفيه**

````bash
   php artisan schedule:work
   ```

````
