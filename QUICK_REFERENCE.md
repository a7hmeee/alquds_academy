# 🚀 المراجع السريعة - نظام التسجيلات

## 📍 الروابط الرئيسية

### للطلاب (Students)

| الوظيفة | الرابط | الاختصار |
|--------|--------|---------|
| لوحة التسجيلات | `/recordings/dashboard` | `D` |
| رفع تسجيل جديد | `/recordings/upload` | `U` |
| عرض التسجيل | `/recordings/{id}` | `V` |

---

### للمعلمين (Teachers)

| الوظيفة | الرابط | الوصول |
|--------|--------|---------|
| مراجعة التسجيلات | `/teacher/submissions` | من اللوحة الإدارية |
| تقييم التسجيل | `/recordings/{id}` | نفس رابط الطالب |
| الإحصائيات | `/teacher/analytics` | من اللوحة الإدارية |

---

### المسؤولون (Admins)

| الوظيفة | الرابط | الملاحظات |
|--------|--------|---------|
| إدارة السور | `/admin/surahs` | CRUD كامل |
| إدارة الأجزاء | `/admin/juzs` | CRUD كامل |
| إدارة التسجيلات | `/admin/submissions` | عرض وإحصائيات |
| التقارير | `/admin/reports` | تحليلات شاملة |

---

## 📱 واجهات برمجية (API)

### الـ Endpoints المتاحة

```bash
# جميع السور
GET /api/recordings/surahs
Response: [
  { id: 1, name: "البقرة", juzs: 5, ayahs: 286 },
  { id: 2, name: "آل عمران", juzs: 3, ayahs: 200 }
]

# البحث عن سورة
GET /api/recordings/surahs/search?q=البقرة
Response: [
  { id: 1, name: "البقرة", juzs: 5 }
]

# أجزاء السورة
GET /api/recordings/surah/1/juz
Response: [1, 2, 3, 4, 5]

# آيات محددة
GET /api/recordings/surah/1/juz/1/ayahs
Response: { from: 1, to: 143 }
```

---

## 🗂️ الملفات الأساسية

### التطبيق

| الملف | المسار | الغرض |
|------|--------|--------|
| RecordingController | `app/Http/Controllers/` | تحكم التسجيلات |
| StudentSubmission | `app/Models/` | نموذج التسجيل |
| RecordingBulkImportService | `app/Services/` | خدمة الرفع الجماعي |

### الواجهات

| الملف | المسار | الوصف |
|------|--------|--------|
| upload.blade.php | `resources/views/recordings/` | صفحة الرفع |
| dashboard.blade.php | `resources/views/recordings/` | اللوحة الرئيسية |
| show.blade.php | `resources/views/recordings/` | عرض التسجيل |
| bulk-import.blade.php | `resources/views/recordings/` | الرفع الجماعي |

### قاعدة البيانات

| الجدول | الحقول الجديدة | الوصف |
|--------|----------------|--------|
| student_submissions | 6 حقول جديدة | بيانات التسجيل الإسلامية |
| surahs | موجود | السور القرآنية |
| juzs | موجود | الأجزاء الثلاثين |

---

## 🎛️ التكوينات المهمة

### في `.env`

```env
# التخزين
FILESYSTEM_DISK=public

# قاعدة البيانات
DB_CONNECTION=mysql
DB_DATABASE=alquds_academy

# البريد (للإشعارات - قريباً)
MAIL_DRIVER=smtp
MAIL_FROM_ADDRESS=noreply@example.com
```

### في `config/filesystems.php`

```php
'disks' => [
    'public' => [
        'driver' => 'local',
        'root' => storage_path('app/public'),
        'url' => env('APP_URL').'/storage',
    ],
],
```

---

## 🎨 الألوان والتصميم

### نظام الألوان

```css
/* الألوان الأساسية */
--gold: #d4af37;           /* العناوين */
--cream: #f5f1e8;          /* النصوص الأساسية */
--slate-blue: #3d4d64;     /* النصوص الثانوية */
--dark-bg: #1a1a1a;        /* الخلفيات */

/* الحالات */
--success: #10B981;        /* مقبول */
--pending: #F59E0B;        /* قيد الانتظار */
--error: #EF4444;          /* خطأ/يحتاج تحسين */
--info: #3B82F6;           /* معلومات */
```

### العناصر المستخدمة

```html
<!-- الأيقونات -->
<i class="fas fa-upload"></i>        <!-- رفع -->
<i class="fas fa-download"></i>      <!-- تحميل -->
<i class="fas fa-star"></i>          <!-- تقييم -->
<i class="fas fa-trash"></i>         <!-- حذف -->
<i class="fas fa-eye"></i>           <!-- عرض -->

<!-- الشارات -->
<span class="badge badge-success">مقبول</span>
<span class="badge badge-warning">قيد الانتظار</span>
<span class="badge badge-danger">يحتاج تحسين</span>
```

---

## 📊 الإحصائيات والـ Queries

### الاستعلامات الشائعة

```php
// عدد التسجيلات
StudentSubmission::where('student_id', $id)->count();

// التسجيلات حسب الحالة
StudentSubmission::where('status', 'pending')->count();

// متوسط التقييم
StudentSubmission::where('student_id', $id)->avg('rating');

// آخر تسجيل
StudentSubmission::latest()->first();

// التسجيلات بسورة معينة
StudentSubmission::where('surah_id', 1)->get();
```

---

## 🔒 الأمان والحماية

### معايير الأمان المطبقة

```
✓ CSRF Protection
✓ File Upload Validation
✓ Input Sanitization
✓ Authorization Checks
✓ Rate Limiting (جاهز)
✓ SQL Injection Prevention
✓ XSS Protection
```

### الأذونات المطلوبة

```php
// الطالب يمكنه:
- عرض تسجيلاته هو فقط
- تعديل ملاحظاته
- حذف تسجيله

// المعلم يمكنه:
- عرض تسجيلات طلابه
- إضافة تقييم وملاحظات
- حذف التسجيلات المخالفة

// الإدارة يمكنها:
- عرض جميع التسجيلات
- تقارير شاملة
- إدارة البيانات الأساسية
```

---

## 🎓 التعليم والدعم

### للمستخدم الجديد

1. **قراءة الدليل**: `STUDENT_USER_GUIDE.md`
2. **مشاهدة الفيديو**: (قريباً)
3. **السؤال**: اتصل بالدعم

### للمطور الجديد

1. **قراءة التوثيق**: `TECHNICAL_SUMMARY.md`
2. **فهم العمارة**: `RECORDING_SYSTEM_DOCUMENTATION.md`
3. **اتباع Checklist**: `FINAL_CHECKLIST.md`

---

## 🐛 استكشاف الأخطاء الشائعة

### الخطأ ❌ → الحل ✅

```
❌ "404 Not Found"
✅ تأكد من تاريخ الكود والـ routes مسجلة

❌ "Foreign Key Constraint"
✅ تطبيق الـ migrations: php artisan migrate

❌ "File Not Uploading"
✅ التحقق من التصاريح والمجلد

❌ "سورة غير موجودة"
✅ تأكد من قاعدة البيانات surahs

❌ "الصورة لا تظهر"
✅ تشغيل: php artisan storage:link

❌ "خطأ في الـ CSV"
✅ الحفظ بصيغة UTF-8 وليس ANSI
```

---

## 📞 نقاط الاتصال

### الدعم الفني

| المشكلة | الاتصال |
|--------|---------|
| مشكلة الرفع | البريد الإلكتروني |
| مشكلة البيانات | الهاتف |
| مشكلة النظام | اجتماع سريع |

### نموذج الإبلاغ عن المشاكل

```
الموضوع: [Recording System] مشكلة في...
المشكلة: وصف واضح
الخطوات: كيفية تكرار المشكلة
النتيجة: ما يحدث بالضبط
التوقع: ما يجب أن يحدث
```

---

## 📈 الإحصائيات الأداء

### الأرقام المتوقعة

```
رفع الملف (50 MB):        ~10 ثواني
معالجة CSV (100 صف):      ~5 ثواني
تحميل اللوحة:              <2 ثانية
البحث عن سورة:             <500 ms
تقييم التسجيل:            <1 ثانية
```

### حدود النظام

```
الحد الأقصى للملف:         50 MB
عدد الملفات لكل طالب:      بدون حد
الحد الأقصى للـ CSV:        1000 صف
عمر التخزين المؤقت:       24 ساعة
```

---

## 🎯 الأهداف المستقبلية

### الميزات المخططة

- [ ] تحويل الصوت تلقائياً (FFmpeg)
- [ ] عرض الـ Waveform
- [ ] نسخ احتياطي تلقائي
- [ ] تطبيق الهاتف
- [ ] واجهة موظفي المدرسة
- [ ] تقارير PDF
- [ ] مشاركة مع الأهل
- [ ] تحليلات متقدمة

---

## 📚 المراجع السريعة

### توثيق Laravel

- [Laravel Documentation](https://laravel.com/docs/11.x)
- [Database Queries](https://laravel.com/docs/11.x/queries)
- [File Storage](https://laravel.com/docs/11.x/filesystem)
- [Validation](https://laravel.com/docs/11.x/validation)

### توثيق أخرى

- [PHP Manual](https://php.net)
- [CSS Reference](https://developer.mozilla.org/en-US/docs/Web/CSS)
- [JavaScript Guide](https://developer.mozilla.org/en-US/docs/Web/JavaScript)

---

## 💡 نصائح وحيل

### اختصارات مفيدة

```bash
# تشغيل سريع
php artisan serve
npm run dev

# Tinker للاختبار
php artisan tinker
> User::count()

# اختبار الـ Routes
php artisan route:list | grep recording

# مشاهدة الـ Logs
tail -f storage/logs/laravel.log
```

### أوامر مفيدة

```bash
# مسح الـ Cache
php artisan cache:clear

# تحسين الأداء
php artisan optimize

# تنظيف التخزين المؤقت
php artisan storage:clear

# إعادة تعيين البيانات
php artisan migrate:refresh --seed
```

---

## ✨ الخلاصة

```
🎉 نظام متكامل وجاهز للاستخدام

📖 جميع المكونات موثقة
🎨 جميع الواجهات مصممة
🔒 جميع البيانات محمية
📊 جميع الإحصائيات متاحة

👍 استمتع بالمنتج!
```

---

**تاريخ الإنشاء**: 11/3/2026  
**الإصدار**: 2.0  
**الحالة**: ✅ جاهز للإنتاج

---

آخر تحديث: `11/3/2026 14:30`
