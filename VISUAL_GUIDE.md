# 🎨 التصور المرئي - نظام التسجيلات الصوتية

## 🏗️ خريطة النظام الشاملة

```
┌───────────────────────────────────────────────────────────────┐
│                    نظام إدارة التسجيلات                      │
│         (Recording Management System)                         │
└───────────────────────────────────────────────────────────────┘
                              ↓
          ┌─────────────────────────────────────────┐
          │          Three Main Portals              │
          ├─────────────┬──────────┬────────────────┤
          │   Students  │ Teachers │   Admins       │
          └─────────────┴──────────┴────────────────┘
                       ↓              ↓            ↓
   ┌──────────────────────────────────────────────────────────┐
   │                  Interfaces & Views                      │
   │                  الواجهات والصفحات                       │
   └──────────────────────────────────────────────────────────┘
   │
   ├─► 📊 Dashboard (upload.blade.php)
   │    [  5-Step Form with Dynamic Selection  ]
   │
   ├─► 📈 Dashboard (dashboard.blade.php)
   │    [  Statistics | Submissions List  ]
   │
   ├─► 👁️ Show Page (show.blade.php)
   │    [  Audio Player | Rating System | Feedback  ]
   │
   └─► 📤 Bulk Import (bulk-import.blade.php)
        [  CSV/Excel Upload | Results  ]
```

---

## 📊 سير العمل للطالب

```
START
  ↓
┌─────────────────────────────────────┐
│  Student Login                      │
│  (تسجيل دخول الطالب)               │
└─────────────────────────────────────┘
  ↓
┌─────────────────────────────────────┐
│  Main Dashboard                     │
│  (اللوحة الرئيسية)                  │
│                                     │
│  📈 Statistics                      │
│  - Total: 10                        │
│  - Pending: 3                       │
│  - Accepted: 5                      │
│  - Needs Work: 2                    │
│  - Avg Rating: 4.2★                │
└─────────────────────────────────────┘
  ↓
  ├─→ Option 1: Record New
  │     (تسجيل جديد)
  │
  └─→ Option 2: View Existing
       (عرض موجود)

IF Option 1: NEW RECORDING
  ↓
  STEP 1: Choose Surah
  ┌──────────────────────────────┐
  │  [Search: البقرة/2/Al-Baqarah] │
  │                               │
  │  ▼ Results:                  │
  │  □ البقرة (286 آية)          │
  │  □ آل عمران (200 آية)        │
  │  □ النساء (176 آية)          │
  └──────────────────────────────┘
  ↓
  STEP 2: Choose Juz
  ┌──────────────────────────────┐
  │  Juz: [1 ▼] [2 ▼] [3 ▼] ... │
  │                               │
  │  Selected: Juz 1 (5)          │
  └──────────────────────────────┘
  ↓
  STEP 3: Choose Ayahs
  ┌──────────────────────────────┐
  │  From: [1_____]               │
  │  To:   [5_____] (optional)    │
  │                               │
  │  Range: 1-5 (5 Ayahs)         │
  └──────────────────────────────┘
  ↓
  STEP 4: Upload Audio
  ┌──────────────────────────────┐
  │  📁 Drop file here            │
  │       or click to select      │
  │                               │
  │  File: recording.mp3 (2.5MB)  │
  │  Duration: 2:35               │
  │                               │
  │  ███████████░░░░░░░░░ 65%    │
  │  Speed: 150 KB/s              │
  │  Time left: 10s               │
  └──────────────────────────────┘
  ↓
  STEP 5: Add Notes
  ┌──────────────────────────────┐
  │  Notes:                       │
  │  ┌───────────────────────────┐│
  │  │ صعوبة في الآية الثالثة  ││
  │  │ صوت واضح لكن سريع قليلاً ││
  │  └───────────────────────────┘│
  │                               │
  │  Image: [Choose Image...]     │
  └──────────────────────────────┘
  ↓
  SUBMIT
  ↓
  CONFIRMATION
  ✅ Recording uploaded successfully!
  ↓
  Back to Dashboard

IF Option 2: VIEW EXISTING
  ↓
┌─────────────────────────────────────┐
│  Submission Card                    │
│                                     │
│  🕌 Surah: سورة البقرة              │
│  📖 Ayahs: 1-5                      │
│  〽️  Juz: 1                         │
│  📅 Date: 2026-03-11                │
│  ⏱️  Duration: 2:35                 │
│                                     │
│  Status: 🟢 Accepted / 🟡 Pending │
│                                     │
│  🏆 Teacher Rating: ⭐⭐⭐⭐ (4/5)  │
│  👤 My Rating: ⭐⭐⭐ (3/5)         │
│                                     │
│  💭 Teacher Notes:                 │
│  ┌─────────────────────────────┐  │
│  │ تحسن جيد في النطق          │  │
│  │ القراءة سليمة بشكل عام      │  │
│  └─────────────────────────────┘  │
│                                     │
│  👁️ View | 🗑️ Delete | 📥 Download│
└─────────────────────────────────────┘
  ↓
  IF Click View
    ↓
    ┌──────────────────────────────┐
    │  Recording Details Page      │
    │                              │
    │  🔊 [▶️ ║ ─────●────] 2:35  │
    │      Volume: ◄───●─────→    │
    │                              │
    │  🖼️  [Image Display/Gallery] │
    │                              │
    │  📝 My Notes:                │
    │  ┌────────────────────────┐  │
    │  │ صعوبة في الآية الثالثة│  │
    │  └────────────────────────┘  │
    │                              │
    │  ⭐ My Rating:               │
    │  ☆☆☆☆☆ → ★★★☆☆ (Save)   │
    │                              │
    │  💬 Teacher Feedback:        │
    │  ┌────────────────────────┐  │
    │  │ [Gold Box]             │  │
    │  │ تحسن جيد جداً          │  │
    │  └────────────────────────┘  │
    └──────────────────────────────┘
    ↓
    END

OR BULK IMPORT
  ↓
  ┌─────────────────────────────────┐
  │  Bulk Import Page               │
  │                                 │
  │  📥 Drag & Drop Zone            │
  │     or [Choose Files...]        │
  │                                 │
  │  1. Download Template           │
  │  2. Fill Data                   │
  │  3. Upload CSV                  │
  └─────────────────────────────────┘
  ↓
  [Download CSV Template]
  ↓
  Template Format:
  ┌──────────────────────────────────────────┐
  │ Surah | Juz | From | To | Notes | Path   │
  │─────────────────────────────────────────│
  │ البقرة | 1   | 1    | 5  | بداية| -      │
  │ آل عمران| 2  | 10   |-   | جيد|- file.mp3│
  └──────────────────────────────────────────┘
  ↓
  [Upload CSV]
  ↓
  Processing...
  ↓
  ┌──────────────────────┐
  │  Results             │
  │  ✅ Success: 8       │
  │  ❌ Failed: 2        │
  │                      │
  │  Errors:             │
  │  Row 1: Surah error  │
  │  Row 5: Juz invalid  │
  └──────────────────────┘
  ↓
  END
```

---

## 🔄 سير العمل للمعلم

```
START
  ↓
┌─────────────────────────────────────┐
│  Teacher Portal                     │
│  (بوابة المعلم)                     │
└─────────────────────────────────────┘
  ↓
┌─────────────────────────────────────┐
│  Submissions to Review              │
│  (التسجيلات المنتظرة للمراجعة)      │
│                                     │
│  - Class A: 5 pending               │
│  - Class B: 3 pending               │
│  - Class C: 2 pending               │
│  - Total: 10 pending                │
└─────────────────────────────────────┘
  ↓
  SELECT SUBMISSION
  ↓
┌─────────────────────────────────────┐
│  Review Page                        │
│                                     │
│  Student: أحمد محمد                │
│  Surah: سورة البقرة                │
│  Ayahs: 1-5                         │
│  Date: 2026-03-11                   │
│                                     │
│  🔊 [▶️ ║ ────●────────] 2:35      │
│                                     │
│  📝 Student Notes:                 │
│  صعوبة في الآية الثالثة            │
│                                     │
│  ⭐ Student Rating: 3/5             │
└─────────────────────────────────────┘
  ↓
  DECIDE RATING
  ├─ ⭐⭐⭐⭐⭐ Excellent
  ├─ ⭐⭐⭐⭐  Very Good
  ├─ ⭐⭐⭐    Good
  ├─ ⭐⭐      Needs Work
  └─ ⭐      Poor
  ↓
  ADD FEEDBACK
  ┌─────────────────────────────┐
  │  Feedback:                  │
  │  ┌───────────────────────┐  │
  │  │ تحسن جيد في النطق   │  │
  │  │ العمل على السرعة     │  │
  │  │ متابع مستمر مطلوب    │  │
  │  └───────────────────────┘  │
  └─────────────────────────────┘
  ↓
  [ACCEPT] [NEEDS WORK] [REJECT]
  ↓
  STATUS UPDATE
  ├─ Accepted (🟢)
  ├─ Needs Work (🟡)
  └─ Rejected (🔴)
  ↓
  SAVE & NOTIFY STUDENT
  ↓
  RETURN TO LIST
  ↓
  END
```

---

## 🗄️ خريطة قاعدة البيانات

```
┌──────────────────────────────────────────────────────────────┐
│                     DATABASE SCHEMA                          │
└──────────────────────────────────────────────────────────────┘

SURAHS TABLE
┌─────────────────────────────┐
│ id (PK)                    │
│ name (varchar)             │
│ number (int)               │
│ number_of_ayahs (int)      │
│ number_of_juzs (int)       │
└─────────────────────────────┘
         ↑
         │ Foreign Key
         │
JUZS TABLE                  STUDENT_SUBMISSIONS TABLE
┌──────────────┐            ┌──────────────────────────────┐
│ id (PK)      │            │ id (PK)                     │
│ number (int) │            │ student_id (FK)             │
│ name (...)   │            │ circle_id (FK)              │
└──────────────┘            │                              │
         ↑                  │ surah_id (FK) [NEW]          │
         │                  │ juz_id (FK) [NEW]            │
         └──────────────────│ ayah_from (int) [NEW]        │
                            │ ayah_to (int) [NEW]          │
                            │                              │
                            │ file_path (varchar)         │
                            │ image_path (varchar)        │
                            │ self_rating (int) [NEW]     │
                            │ self_notes (text) [NEW]     │
                            │                              │
                            │ status (enum)               │
                            │ reviewed_by (FK)            │
                            │ review_notes (text)         │
                            │ rating (int)                │
                            │                              │
                            │ created_at (timestamp)      │
                            │ updated_at (timestamp)      │
                            └──────────────────────────────┘
                                    ↑
                                    │ Foreign Key
                                    │
                            ┌──────────────────┐
                            │ USERS/PROFILES   │
                            │ student_id → id  │
                            │ reviewed_by → id │
                            └──────────────────┘
```

---

## 🌐 خريطة الـ Routes والـ APIs

```
┌─────────────────────────────────────────────────────────────┐
│                    APPLICATION ROUTES                       │
└─────────────────────────────────────────────────────────────┘

WEB ROUTES (HTML Pages)
│
├─ GET  /recordings/dashboard
│       → Show recording statistics and list
│
├─ GET  /recordings/upload
│       → Show 5-step upload form
│
├─ POST /recordings/store
│       → Save new recording
│
├─ GET  /recordings/{id}
│       → Show recording details
│
├─ POST /recordings/{id}/rate
│       → Update rating/notes
│
├─ DELETE /recordings/{id}
│       → Delete recording
│
├─ GET  /recordings/bulk-import
│       → Show bulk import form
│
├─ POST /recordings/bulk-import
│       → Process CSV upload
│
└─ GET  /recordings/bulk-import/template
        → Download CSV template


API ROUTES (JSON Data)
│
├─ GET /api/recordings/surahs
│     Response: [{ id, name, ayahs, juzs }, ...]
│
├─ GET /api/recordings/surahs/search?q=البقرة
│     Response: [{ id, name, ... }, ...]
│
├─ GET /api/recordings/surah/{id}/juz
│     Response: [1, 2, 3, 4, 5]
│
└─ GET /api/recordings/surah/{id}/juz/{id}/ayahs
      Response: { from: 1, to: 143 }
```

---

## 📱 تصميم الواجهة (UI Flow)

```
┌─────────────────────────────────────────────────────────────┐
│                                                             │
│              MAIN DASHBOARD LAYOUT                         │
│                                                             │
│  ┌────────────────── Header ──────────────────────┐       │
│  │  Logo    |    Menu    |    User Profile    |   │       │
│  └────────────────────────────────────────────────┘       │
│                                                             │
│  ┌────────────────── Main Content ───────────────┐        │
│  │                                               │        │
│  │  📊 STATISTICS CARDS                          │        │
│  │  ┌──────┬──────┬──────┬──────┐               │        │
│  │  │Total │Pending│Accept│Needs │               │        │
│  │  │ 10   │ 3    │ 5   │ 2   │               │        │
│  │  └──────┴──────┴──────┴──────┘               │        │
│  │  ⭐ Average Rating: 4.2                       │        │
│  │                                               │        │
│  │  🎯 ACTION BUTTONS                            │        │
│  │  [📤 Upload New] [📥 Bulk Import] [📊 Stats]│        │
│  │                                               │        │
│  │  📋 RECORDINGS LIST                           │        │
│  │  ─────────────────────────────────────────  │        │
│  │                                               │        │
│  │  Card 1:                                      │        │
│  │  ┌───────────────────────────────────────┐  │        │
│  │  │ 🕌 سورة البقرة - الآية 1-5          │  │        │
│  │  │ 〽️ الجزء الأول | 📅 2026-03-11      │  │        │
│  │  │ 🟢 Accepted | ⭐⭐⭐⭐ (4/5)          │  │        │
│  │  │                                        │  │        │
│  │  │ 👁️ View | 🗑️ Delete | 📥 Download   │  │        │
│  │  └───────────────────────────────────────┘  │        │
│  │                                               │        │
│  │  Card 2: [Similar...]                        │        │
│  │  Card 3: [Similar...]                        │        │
│  │                                               │        │
│  └───────────────────────────────────────────┘  │        │
│                                                   │        │
│  ┌────────────────── Footer ──────────────────┐ │        │
│  │  © 2026 Alquds Academy | Privacy | Help    │ │        │
│  └────────────────────────────────────────────┘ │        │
│                                                   │        │
└─────────────────────────────────────────────────────────────┘


UPLOAD FORM FLOW
┌──────────────────────────────────────────────────┐
│  Upload Wizard - Step X of 5                      │
├──────────────────────────────────────────────────┤
│                                                   │
│  Step Indicator:                                 │
│  [✓ Surah] → [• Juz] → [○ Ayahs] → [○ File]   │
│                                                   │
│  Current Step:                                   │
│  [Form Input/Selection Area]                     │
│                                                   │
│  [← Back] [Continue →]                          │
│                                                   │
└──────────────────────────────────────────────────┘


RECORDING VIEW PAGE
┌──────────────────────────────────────────────────┐
│  Recording Details                                │
├──────────────────────────────────────────────────┤
│                                                   │
│  🔊 Audio Player                                │
│  [▶️ ║ ─────●────────] 2:35                     │
│  Volume: ◄──●──→                                 │
│                                                   │
│  🖼️ Image [if exists]                            │
│                                                   │
│  📝 Information                                  │
│  Surah: سورة البقرة                              │
│  Ayahs: 1-5                                      │
│  Date: 2026-03-11                                │
│  Status: 🟢 Accepted                             │
│                                                   │
│  👨‍💼 Teacher Feedback:                            │
│  ┌──────────────────────────────┐               │
│  │ [Gold Box]                   │               │
│  │ تحسن جيد في النطق           │               │
│  │ استمر في الممارسة            │               │
│  └──────────────────────────────┘               │
│                                                   │
│  ⭐ My Rating:                                   │
│  ☆☆☆☆☆ (Click to rate)                          │
│                                                   │
│  My Notes:                                       │
│  ┌──────────────────────────────┐               │
│  │ صعوبة في الآية الثالثة     │               │
│  └──────────────────────────────┘               │
│                                                   │
│  [Save Notes]                                    │
│                                                   │
└──────────────────────────────────────────────────┘
```

---

## 🔐 الأمان والحماية

```
REQUEST → SYSTEM
    │
    ├─ Authentication Check ✓
    │  └─ وجود تسجيل دخول؟
    │
    ├─ Authorization Check ✓
    │  └─ الصلاحيات كافية؟
    │
    ├─ CSRF Validation ✓
    │  └─ الطلب من المصدر الموثوق؟
    │
    ├─ Input Validation ✓
    │  └─ البيانات صحيحة؟
    │
    ├─ File Validation ✓
    │  └─ نوع وحجم الملف صحيح؟
    │
    └─ Rate Limiting ✓
       └─ عدد الطلبات ضمن الحد؟

If all checks PASS → Process Request
If any check FAILS → Return Error 403
```

---

## 📊 إحصائيات الأداء

```
PERFORMANCE METRICS

Page Load Time
├─ Dashboard:        < 2 seconds
├─ Upload Form:      < 1.5 seconds
├─ Recording View:   < 1.2 seconds
└─ Bulk Import:      < 1 second

File Upload
├─ 5 MB file:        ~2 seconds
├─ 25 MB file:       ~10 seconds
└─ 50 MB file:       ~20 seconds

CSV Processing
├─ 50 rows:          ~2 seconds
├─ 100 rows:         ~4 seconds
├─ 500 rows:         ~20 seconds
└─ 1000 rows:        ~40 seconds

Database Queries
├─ Single submission:   ~50ms
├─ Dashboard stats:     ~100ms
├─ Surah list:          ~30ms
└─ Bulk search:         ~150ms
```

---

## 🎯 مصفوفة المسؤوليات

```
┌────────────────┬─────────┬────────┬──────┐
│ Feature        │ Student │Teacher │Admin │
├────────────────┼─────────┼────────┼──────┤
│ Upload         │   ✓     │   ✗    │  ✓   │
│ View Own       │   ✓     │   ✗    │  ✓   │
│ Edit Own       │   ✓     │   ✗    │  ✓   │
│ Delete Own     │   ✓     │   ✗    │  ✓   │
│ View All       │   ✗     │   ✓    │  ✓   │
│ Rate Others    │   ✗     │   ✓    │  ✓   │
│ Delete Others  │   ✗     │   ✓    │  ✓   │
│ Manage Users   │   ✗     │   ✗    │  ✓   │
│ System Config  │   ✗     │   ✗    │  ✓   │
└────────────────┴─────────┴────────┴──────┘
```

---

## 🎓 الخلاصة البصرية

```
INPUT              PROCESS            OUTPUT

[ Recording ]   
    ↓           [ Validate ]        
[ Audio File ]  [ Store File ]    → ✅ Stored in DB
[ Notes ]       [ Create Record ]    
[ Images ]      [ Generate Stats ] → 📊 Updated Dashboard
    
                                   
[ Student ]                        → 👤 Can rate
    ↓                                
[ Rating ]  →  [ Update Record ]  → ⭐ Rating saved
[ Notes ]       [ Notify Teacher ]  → 📧 Alert sent


[ CSV File ]                       
    ↓           [ Parse CSV ]      → 📋 Parsed rows
[ Multiple ]    [ Validate Each ]  → ✅ Validated
[ Recordings ]  [ Create Records ] → 💾 DB Updated
    ↓           [ Error Track ]    → ⚠️ Errors listed
[ Results ]     [ Generate Report ]→ 📊 Summary shown
```

---

**تاريخ الإنشاء**: 11/3/2026  
**الإصدار**: 2.0
