# 🔧 الملخص التقني - نظام التسجيلات

## 📌 نظرة عامة

نظام متكامل لإدارة التسجيلات الصوتية للطلاب في منصة تعليمية إسلامية.
يوفر تحكماً كاملاً على جودة الصوت والبيانات مع دعم الاستيراد الجماعي.

---

## 🏗️ العمارة المعمارية

```
┌─────────────────────────────────────────────┐
│         Frontend (Blade Templates)          │
│  - upload.blade.php                         │
│  - dashboard.blade.php                      │
│  - show.blade.php                           │
│  - bulk-import.blade.php                    │
└─────────────────────────────────────────────┘
                     ↓
┌─────────────────────────────────────────────┐
│      Routes & Controllers                   │
│  - RecordingController.php                  │
│  - web.php (13 routes)                      │
├─────────────────────────────────────────────┤
│  - GET/POST/DELETE operations               │
│  - CSRF Protection                          │
│  - Authorization checks                     │
└─────────────────────────────────────────────┘
                     ↓
┌─────────────────────────────────────────────┐
│      Business Logic Layer                   │
│  - RecordingBulkImportService.php          │
│  - CSV/Excel parsing & validation           │
│  - Row-by-row error tracking                │
└─────────────────────────────────────────────┘
                     ↓
┌─────────────────────────────────────────────┐
│      Data Layer                             │
│  - StudentSubmission Model                  │
│  - Database migrations                      │
│  - Relationships & queries                  │
└─────────────────────────────────────────────┘
```

---

## 🗂️ هيكل الملفات

```
app/
├── Http/Controllers/
│   └── RecordingController.php (300+ lines)
│
├── Models/
│   └── StudentSubmission.php (updated)
│
├── Services/
│   └── RecordingBulkImportService.php (200+ lines)
│
└── Policies/
    └── StudentProgressPolicy.php

database/
├── migrations/
│   ├── 2026_03_11_000002_add_surah_ayah_juz...
│   └── 2026_03_11_000003_add_recording_fields...
│
└── factories/
    └── UserFactory.php

resources/
└── views/
    └── recordings/
        ├── upload.blade.php (350+ lines)
        ├── dashboard.blade.php
        ├── show.blade.php
        └── bulk-import.blade.php

routes/
└── web.php (13 new routes)

storage/
└── app/
    └── public/
        └── recordings/
```

---

## 🗄️ خطاطة قاعدة البيانات

### الجداول المعنية

#### `student_submissions` (الحقول الجديدة)

```sql
ALTER TABLE student_submissions ADD (
    -- الحقول الأصلية (موجودة)
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    student_id BIGINT FOREIGN KEY,
    circle_id BIGINT FOREIGN KEY,
    file_path VARCHAR(255),
    image_path VARCHAR(255) NULLABLE,
    status ENUM('pending','accepted','needs_work'),
    notes LONGTEXT,
    reviewed_by BIGINT NULLABLE,
    review_notes LONGTEXT NULLABLE,
    rating INT(1) NULLABLE DEFAULT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    -- الحقول الجديدة (المضافة)
    surah VARCHAR(100) NULLABLE,
    ayah INT NULLABLE,
    juz VARCHAR(50) NULLABLE,
    surah_id BIGINT UNSIGNED NULLABLE,
    juz_id BIGINT UNSIGNED NULLABLE,
    ayah_from INT NULLABLE,
    ayah_to INT NULLABLE,
    self_rating INT(1) NULLABLE DEFAULT NULL,
    self_notes LONGTEXT NULLABLE
);
```

### العلاقات

```
StudentSubmission
    ├── belongs_to(StudentProfile) 📌 student_id
    ├── belongs_to(Circle) 📌 circle_id
    └── belongs_to(TeacherProfile) 📌 reviewed_by
```

---

## 🔌 نقاط النهاية (Endpoints)

### Web Routes

```php
// Dashboard & Views
GET     /recordings/dashboard           -> recordings.dashboard
GET     /recordings/upload              -> recordings.upload
GET     /recordings/{submission}        -> recordings.show

// CRUD Operations
POST    /recordings/store               -> recordings.store
POST    /recordings/{submission}/rate   -> recordings.rate
DELETE  /recordings/{submission}        -> recordings.delete

// Bulk Import
GET     /recordings/bulk-import         -> recordings.bulkImport.page
POST    /recordings/bulk-import         -> recordings.bulkImport
GET     /recordings/bulk-import/template -> recordings.bulkImport.template
```

### API Routes

```php
// Surahs
GET     /api/recordings/surahs                    -> جميع السور
GET     /api/recordings/surahs/search?q=query    -> بحث
    
// Dynamic Selection
GET     /api/recordings/surah/{surah}/juz        -> أجزاء السورة
GET     /api/recordings/surah/{surah}/juz/{juz}/ayahs -> الآيات
```

---

## 📋 المتطلبات المسبقة

### متطلبات النظام

```
- PHP 8.1+
- Laravel 11+
- MySQL 8.0+
- Node.js 16+ (للـ Vite)
```

### الـ Dependencies المستخدمة

```php
// Laravel Framework
laravel/framework: ^11.0

// التوثيق والتحقق
spatie/laravel-permission: ^5.0
laravel/sanctum: ^3.0

// الأدوات الإضافية
composer require laravel/tinker

// اختيارية (للـ Excel)
composer require phpoffice/phpspreadsheet
```

---

## 🔐 معايير الأمان

### التحقق من الأمان

```php
// 1. CSRF Protection
@csrf // في جميع الـ forms

// 2. Authorization
$this->authorize('view', $submission);

// 3. File Upload Validation
'file' => 'required|file|mimes:mp3,wav,m4a,ogg|max:51200',
'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120'

// 4. Input Validation
Validator::make($request->all(), [
    'surah_id' => 'required|integer|exists:surahs,id',
    'juz_id' => 'required|integer|exists:juzs,id',
    'ayah_from' => 'required|integer|min:1',
    'ayah_to' => 'nullable|integer|gte:ayah_from',
    'self_rating' => 'nullable|integer|between:1,5',
    'self_notes' => 'nullable|string|max:2000',
])->validate();

// 5. Mass Assignment Protection
protected $fillable = [
    'student_id', 'circle_id', 'file_path', 'image_path',
    'surah', 'ayah', 'juz', 'surah_id', 'juz_id', 
    'ayah_from', 'ayah_to', 'self_rating', 'self_notes', 
    'notes', 'status', 'reviewed_by', 'review_notes', 'rating'
];
```

---

## 📊 أداء ملفات المعالجة

### CSV Processing

```php
function processRow($row, $student) {
    // التحقق من البيانات
    // ✓ التحقق من وجود السورة
    // ✓ التحقق من صحة الجزء
    // ✓ التحقق من نطاق الآيات
    
    // المعالجة
    try {
        // إنشاء السجل
        // تخزين الملف (إن وجد)
        // إرجاع النتيجة
    } catch (Exception $e) {
        // تتبع الخطأ
        // إرجاع رسالة واضحة
    }
}

// الأداء المتوقع:
// - 100 row = ~5 ثواني
// - 1000 row = ~50 ثانية
```

---

## 🎯 المنطق الأساسي

### سير المعالجة الأساسي

```
1. Student Access
   ↓
2. Check Authentication
   ↓
3. Load Recording Form
   (4 API calls for dropdowns)
   ↓
4. Validate Input
   ↓
5. Store File
   ↓
6. Create Database Record
   ↓
7. Return Success/Error
```

### سير المعالجة للرفع الجماعي

```
1. User Selects CSV/Excel
   ↓
2. Upload to Temporary Location
   ↓
3. Parse File
   ├─ Detect Format (CSV/Excel)
   ├─ Extract Rows
   └─ Clean Data
   ↓
4. Validate Each Row
   ├─ Check Surah Exists
   ├─ Check Juz Range
   ├─ Check Ayah Numbers
   └─ Track Errors
   ↓
5. Create Records (Success Rows)
   ↓
6. Clean Up Temporary File
   ↓
7. Return Results
   ├─ Success Count
   ├─ Error Count
   └─ Error Details
```

---

## 🎛️ إعدادات التخزين

### مسارات الملفات

```php
// Configuration
'disks' => [
    'public' => [
        'driver' => 'local',
        'root' => storage_path('app/public'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'public',
    ],
],

// Usage
Storage::disk('public')->put(
    "recordings/{$studentId}/{$filename}",
    $file,
    'public'
);

// Final Path
/storage/app/public/recordings/{studentId}/{filename}
// Accessible at: /storage/recordings/{studentId}/{filename}
```

### تنظيف التخزين

```bash
# تنظيف الملفات المؤقتة
php artisan storage:link

# الملفات المحذوفة
php artisan tinker
> use Illuminate\Support\Facades\Storage;
> Storage::disk('public')->delete('path/to/file');
```

---

## ⚙️ المتغيرات البيئية

### `.env` ذات الصلة

```env
APP_KEY=base64:...
APP_DEBUG=true
APP_ENV=local

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=alquds_academy
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=public

# Optional for Email Notifications
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
```

---

## 🧪 الاختبار

### Unit Tests (مثال)

```php
// tests/Unit/RecordingBulkImportServiceTest.php

public function test_parse_csv_with_valid_data() {
    $service = new RecordingBulkImportService();
    $filePath = 'test_data.csv';
    
    $rows = $service->parseCSV($filePath);
    
    $this->assertCount(5, $rows);
}

public function test_invalid_surah_returns_error() {
    $service = new RecordingBulkImportService();
    $row = ['surah' => 'Invalid', 'juz' => 1, 'ayah_from' => 1];
    
    $result = $service->processRow($row, $student);
    
    $this->assertFalse($result['success']);
}
```

### Feature Tests (مثال)

```php
// tests/Feature/RecordingUploadTest.php

public function test_student_can_upload_recording() {
    $student = User::factory()
                   ->has(StudentProfile::factory())
                   ->create();
    
    $this->actingAs($student);
    
    $response = $this->post('/recordings/store', [
        'surah_id' => 1,
        'juz_id' => 1,
        'ayah_from' => 1,
        'ayah_to' => 5,
        'file' => UploadedFile::fake()
                              ->create('recording.mp3'),
    ]);
    
    $response->assertStatus(302);
    $this->assertDatabaseHas('student_submissions', [
        'student_id' => $student->id,
        'surah_id' => 1,
    ]);
}
```

---

## 📈 المقاييس والإحصائيات

### الاستعلامات الرئيسية

```php
// إجمالي التسجيلات
$total = StudentSubmission::where('student_id', $studentId)->count();

// التسجيلات حسب الحالة
$pending = StudentSubmission::where('status', 'pending')->count();
$accepted = StudentSubmission::where('status', 'accepted')->count();
$needs_work = StudentSubmission::where('status', 'needs_work')->count();

// متوسط التقييم
$avgRating = StudentSubmission::where('student_id', $studentId)
                              ->avg('rating');

// آخر تسجيلات
$recent = StudentSubmission::where('student_id', $studentId)
                           ->orderBy('created_at', 'desc')
                           ->take(10)
                           ->get();
```

---

## 🐛 استكشاف الأخطاء

### الأخطاء الشائعة والحلول

```
❌ Error: "Foreign key constraint"
✓ Fix: تأكد من وجود الجداول المرجعية
        php artisan migrate:rollback
        php artisan migrate

❌ Error: "File not found"
✓ Fix: تأكد من وجود المجلد
        mkdir -p storage/app/public/recordings
        php artisan storage:link

❌ Error: "CSV encoding"
✓ Fix: حفظ الملف بـ UTF-8
        استخدم Notepad++ أو VS Code

❌ Error: "Surah not found"
✓ Fix: تحقق من تهجئة السورة
        تأكد من أن `surahs` جدول موجود

❌ Error: "413 Payload too large"
✓ Fix: زيادة حد الملف في nginx/Apache
        php.ini: upload_max_filesize = 50M
                 post_max_size = 50M
```

---

## 🚀 نشر الكود

### خطوات النشر

```bash
# 1. سحب الكود الجديد
git pull origin main

# 2. تثبيت الـ Dependencies
composer install --no-dev

# 3. تطبيق الـ Migrations
php artisan migrate --production

# 4. Seed البيانات الأساسية (إذا لزم)
php artisan db:seed

# 5. تنظيف الـ Cache
php artisan optimize
php artisan cache:clear

# 6. Build الـ Frontend Assets
npm run build

# 7. التحقق من الأيقونات
php artisan storage:link
```

### ملف التدقيق

```bash
✓ All migrations applied
✓ All routes registered
✓ No PHP errors
✓ Assets compiled
✓ Database connected
✓ Storage accessible
✓ Permissions correct
```

---

## 📞 الدعم التقني

### معلومات مهمة للدعم

```
النسخة: 2.0
تاريخ الإصدار: 11/3/2026
حالة الثبات: ✅ مستقر
أداء النظام: ✅ جيد
دعم المتصفح: ✓ Chrome/Firefox/Edge
         ✗ Safari (بعض المشاكل)
```

### الملفات المهمة للمرجعية

```
- docs/API.md
- docs/DATABASE.md
- docs/ARCHITECTURE.md
- docs/TROUBLESHOOTING.md
- .env.example
- phpunit.xml
```

---

## 📚 المراجع

### المراجع المرتبطة

```
- Laravel 11 Documentation: https://laravel.com/docs
- Asset Storage: https://laravel.com/docs/filesystem
- CSV Processing: https://php.net/manual/en/function.fgetcsv.php
- Arabic Text: UTF-8 Encoding Guide
```

---

**تاريخ التحديث**: 11/3/2026  
**الإصدار**: 2.0  
**الحالة**: ✅ جاهز للإنتاج

---

📧 للأسئلة التقنية، يرجى الاتصال بفريق التطوير.
