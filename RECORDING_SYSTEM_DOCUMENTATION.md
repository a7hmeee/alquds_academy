# نظام إدارة التسجيلات الصوتية - التوثيق الشامل ✅

## 🎙️ النظام المتكامل نسخة 2.0

```
مرحباً بك في نظام إدارة التسجيلات الصوتية المتقدم!
هذا النظام يوفر سهولة تامة للطلاب لتحميل تسجيلاتهم مع تتبع كامل للتقدم.
```

---

## 📊 الأرقام والإحصائيات

| المكون | العدد | الحالة |
|-------|------|--------|
| Controllers | 1 | ✅ جاهز |
| Views | 4 | ✅ جاهزة |
| API Routes | 4 | ✅ جاهزة |
| Web Routes | 10 | ✅ جاهزة |
| Migrations | 2 | ✅ مطبقة |
| Services | 2 | ✅ جاهزة |
| Database Columns | 6 | ✅ مضافة |

---

## 🚀 المكونات الرئيسية

### 1. **Controller**: `app/Http/Controllers/RecordingController.php`

#### الـ Methods:
```php
- dashboard()              // لوحة التسجيلات الرئيسية
- uploadPage()             // صفحة التحميل دينامية
- apiSurahs()              // API: جميع السور
- apiSearchSurahs()        // API: البحث عن سورة
- apiSurahJuz()            // API: أجزاء السورة
- apiSurahJuzAyahs()       // API: آيات السورة والجزء
- store()                  // حفظ التسجيل
- rate()                   // تقييم التسجيل
- delete()                 // حذف التسجيل
- show()                   // عرض تفاصيل التسجيل
- bulkImportPage()         // صفحة الرفع الجماعي
- bulkImport()             // API للرفع الجماعي
- downloadBulkTemplate()   // تحميل قالب CSV
```

---

### 2. **Views** (الواجهات)

#### `recordings/dashboard.blade.php` - لوحة التسجيلات
- عرض إحصائيات شاملة:
  - إجمالي التسجيلات
  - التسجيلات قيد المراجعة
  - التسجيلات المقبولة
  - التسجيلات التي تحتاج تحسين
  - متوسط التقييم

- عرض جميع التسجيلات بتفاصيل:
  - اسم السورة والآيات
  - الجزء والتاريخ
  - الحالة مع أيقونة
  - تقييم المعلم والطالب
  - ملاحظات ثنائية الاتجاه
  - أزرار أكشن (تحميل، عرض، حذف)

#### `recordings/upload.blade.php` - صفحة التحميل المتقدمة
- **الخطوة 1**: اختيار السورة
  - بحث ديناميكي عن السورة
  - عرض عدد الآيات والأجزاء
  - دعم البحث بالعربية والإنجليزية وبالرقم

- **الخطوة 2**: اختيار الجزء
  - قائمة ديناميكية حسب السورة
  - تحديث تلقائي بدون تحديث الصفحة

- **الخطوة 3**: اختيار الآيات
  - من الآية (إلزامي)
  - إلى الآية (اختياري)
  - تحديد تلقائي للحد الأقصى

- **الخطوة 4**: رفع الملف
  - Drag-and-drop الملفات
  - عرض مدة التسجيل تلقائياً
  - شريط تقدم مع سرعة التحميل
  - دعم الصيغ: mp3, wav, m4a, ogg

- **الخطوة 5**: معلومات إضافية
  - ملاحظات الطالب
  - صورة توضيحية (اختيارية)

#### `recordings/show.blade.php` - عرض التسجيل والتقييم
- مشغل صوت HTML5
- عرض الصورة إن وجدت
- حالة التسجيل مع تقييم المعلم
- ملاحظات المعلم مع تنسيق خاص
- **نظام التقييم الذاتي**:
  - 5 نجوم قابلة للتفاعل
  - ملاحظات شخصية من الطالب
  - حفظ فوري بدون تحديث صفحة

#### `recordings/bulk-import.blade.php` - الرفع الجماعي
- Drag-and-drop منطقة التحميل
- معلومات هامة عن الملف
- معالجة تدريجية مع عرض النتائج:
  - إجمالي التسجيلات
  - عدد الناجحة
  - عدد الفاشلة
  - تفاصيل الأخطاء
- رابط تحميل قالب CSV
- جدول توضيحي لصيغة الملف

---

### 3. **الـ Services**

#### `app/Services/RecordingBulkImportService.php`
```php
Methods:
- import($filePath, $studentId)     // استيراد من CSV
- parseFile($filePath, $ext)        // تحليل الملف
- parseCSV($filePath)               // قراءة CSV
- parseExcel($filePath)             // قراءة Excel (في المستقبل)
- processRow($row, $student)        // معالجة سطر واحد
- getTemplate()                     // الحصول على قالب CSV
```

#### الحقول المدعومة في CSV:
1. **اسم السورة** (البقرة أو 2) - إلزامي
2. **رقم الجزء** (1-30) - إلزامي
3. **من الآية** (رقم) - إلزامي
4. **إلى الآية** (رقم، اختياري)
5. **الملاحظات** (نص، اختياري)
6. **مسار الملف** (من التخزين، اختياري)

---

### 4. **الـ Routes** (المسارات)

#### Web Routes:
```
GET    /recordings/dashboard              -> recordings.dashboard
GET    /recordings/upload                 -> recordings.upload
POST   /recordings/store                  -> recordings.store
GET    /recordings/{submission}           -> recordings.show
POST   /recordings/{submission}/rate      -> recordings.rate
DELETE /recordings/{submission}           -> recordings.delete
GET    /recordings/bulk-import            -> recordings.bulkImport.page
POST   /recordings/bulk-import            -> recordings.bulkImport
GET    /recordings/bulk-import/template   -> recordings.bulkImport.template
```

#### API Routes:
```
GET /api/recordings/surahs                           -> جميع السور
GET /api/recordings/surahs/search?q=                 -> بحث السور
GET /api/recordings/surah/{surah}/juz               -> أجزاء السورة
GET /api/recordings/surah/{surah}/juz/{juz}/ayahs   -> آيات معينة
```

---

### 5. **Migrations المطبقة**

#### `2026_03_11_000002_add_surah_ayah_juz_to_student_submissions_table`
```sql
- surah: varchar (اسم السورة)
- ayah: integer (رقم الآية)
- juz: varchar (رقم الجزء)
```

#### `2026_03_11_000003_add_recording_fields_to_student_submissions`
```sql
- surah_id: unsigned bigInteger (مرجع السورة)
- juz_id: unsigned bigInteger (مرجع الجزء)
- ayah_from: integer (من الآية)
- ayah_to: integer (إلى الآية)
- self_rating: integer 1-5 (تقييم الطالب)
- self_notes: text (ملاحظات الطالب)
```

---

## 💾 الـ Model

### `StudentSubmission.php`
```php
Relationships:
- student()     -> StudentProfile
- circle()      -> Circle
- reviewer()    -> TeacherProfile

Attributes Filled:
- student_id, circle_id
- file_path, image_path
- notes, surah, ayah, juz
- surah_id, juz_id, ayah_from, ayah_to
- self_rating, self_notes
- status, reviewed_by, review_notes, rating
```

---

## 🎯 سير العمل

### للطالب (Student Flow):
1. الذهاب لـ `/recordings/dashboard`
2. الضغط على "تسجيل جديد"
3. اختيار السورة (بحث ديناميكي)
4. اختيار الجزء (يتحدث تلقائياً)
5. اختيار الآيات (من-إلى)
6. رفع الملف الصوتي (Drag & Drop)
7. إضافة ملاحظات اختيارية
8. الضغط على "رفع"
9. عرض التسجيل في اللوحة
10. تقييم التسجيل بنفسه

### للاستيراد الجماعي:
1. الذهاب لـ `/recordings/bulk-import`
2. تحميل قالب CSV
3. ملء البيانات في Excel/Calc
4. حفظ الملف بصيغة CSV
5. رفع الملف
6. عرض النتائج (نجح/فشل/أخطاء)

---

## 🎨 الواجهة والتصميم

### الألوان المستخدمة:
- **Gold** (`var(--gold)`) - للعناوين والأيقونات الرئيسية
- **Cream** (`var(--cream)`) - للنصوص الأساسية
- **Slate-Blue** (`var(--slate-blue)`) - للنصوص الثانوية
- **Dark-bg** (`var(--dark-bg)`) - للخلفيات
- **Green** (`#10B981`) - للنجاح
- **Orange** (`#F59E0B`) - للانتظار
- **Red** (`#EF4444`) - للأخطاء

### العناصر:
- بطاقات متجاوبة (Responsive)
- أيقونات FontAwesome
- Dropdowns ديناميكية
- Drag-and-drop مناطق
- شرائط تقدم
- نماذج تفاعلية
- جداول احصائية

---

## 🔒 الحماية والأمان

| الميزة | التفاصيل |
|--------|----------|
| CSRF Protection | `@csrf` في جميع الفorms |
| Authorization | التحقق من ملكية التسجيل |
| File Validation | اختبار نوع الملف والحجم |
| Data Validation | التحقق من جميع المدخلات |
| Rate Limiting | (يمكن إضافته) |

---

## 📱 المتطلبات

- Laravel 11+
- PHP 8.1+
- MySQL 8.0+
- Browser مع دعم:
  - HTML5 Audio
  - ES6 JavaScript
  - CSS Grid/Flexbox
  - Fetch API

---

## ⚙️ التثبيت والتشغيل

### 1. تطبيق الـ Migrations:
```bash
php artisan migrate
```

### 2. تشغيل الخادم:
```bash
php artisan serve
```

### 3. الوصول للتطبيق:
```
http://localhost:8000/recordings/dashboard
```

---

## 🎁 الميزات الإضافية

### يمكن إضافتها مستقبلاً:
- [ ] تحويل الصوت تلقائياً (Convert to MP3)
- [ ] تحليل الصوت (Waveform Display)
- [ ] النسخ الاحتياطي التلقائي
- [ ] المراجعة الصوتية من المعلم
- [ ] إحصائيات متقدمة بالرسوم البيانية
- [ ] تصدير التقارير (PDF/Excel)
- [ ] المشاركة مع الأهل
- [ ] النسخ والتكرار الذكي

---

## 🐛 استكشاف الأخطاء

### الخطأ: ملف غير موجود
**الحل**: تأكد من وجود الملف في `storage/app/public`

### الخطأ: السورة غير موجودة
**الحل**: تحقق من قاعدة البيانات أن الأسماء صحيحة

### الخطأ: الحد الأقصى للملف
**الحل**: الحد الأقصى 50 ميجابايت للصوت و5 للصور

---

## 📞 الدعم

للمشاكل والاستفسارات:
- تحقق من console المتصفح (F12)
- راجع ملفات الـ logs
- اتصل بالفريق التقني

---

## ™️ المعلومات الإضافية

| المعلومة | القيمة |
|---------|--------|
| آخر تحديث | 11/3/2026 |
| الإصدار | 2.0 |
| الحالة | ✅ جاهز للإنتاج |
| الأداء | مптимизировано |
| التوثيق | كاملة |

---

**تم إنشاء هذا النظام بعناية فائقة لضمان أفضل تجربة للطلاب والمعلمين.**

```
✨ استمتع بالنظام الجديد! ✨
```
