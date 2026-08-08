# Sidebar ديناميكي - نظام التنقل

## 📌 الوصف
نظام Sidebar ديناميكي متطور يعرض جميع الـ Routes والـ Navigation Items تلقائياً من ApplicationHelper.

---

## 🎯 المميزات

✅ **ديناميكي تماماً** - أي route جديد يُضاف يظهر تلقائياً  
✅ **منظم بأقسام** - تجميع Routes حسب الفئات (القرآن، الحلقات، الإدارة، إلخ)  
✅ **ذكي** - يظهر الـ Route النشط بلون مميز  
✅ **قابل للطي** - Sidebar يمكن ضيقه وتوسيعه  
✅ **Responsive** - يعمل على جميع الأجهزة  
✅ **أيقونات** - كل قسم له أيقونة تمثله  

---

## 📂 الملفات الرئيسية

### 1. **`app/Helpers/NavigationHelper.php`**
مسؤول عن جلب وتنظيم الـ Navigation Items

```php
// مثال على الاستخدام في أي Blade view:
@php
    $navigation = \App\Helpers\NavigationHelper::getNavigation();
@endphp
```

### 2. **`resources/views/components/sidebar-nav.blade.php`**
Sidebar Component الديناميكي الذي يعرض الـ Navigation

---

## 🔧 كيفية الإضافة

### إضافة قسم جديد

```php
// في app/Helpers/NavigationHelper.php

[
    'title' => 'اسم القسم',
    'icon' => '📌',
    'items' => [
        ['name' => 'اسم الصفحة', 'route' => 'route.name'],
    ]
]
```

### إضافة Route جديد

```php
// في routes/web.php
Route::get('/your-path', [YourController::class, 'index'])->name('your.route.name');

// ثم أضفه في NavigationHelper
['name' => 'اسم الصفحة', 'route' => 'your.route.name']
```

---

## 🎨 التصميم

الـ Sidebar يتميز بـ:
- **ألوان متطابقة** مع نظام التصميم (ذهبي وأخضر)
- **Animations سلسة** عند التفاعل
- **Hover Effects** جميلة
- **Active State** واضح للـ Route الحالي

---

## 📊 قائمة الأقسام الحالية

| الأيقونة | العنوان | الـ Routes |
|---------|--------|-----------|
| 📖 | القرآن الكريم | السور، الأجزاء، البحث |
| 🎓 | إدارة الحلقات | الحلقات (Circles) |
| ⚙️ | الإدارة | المعلمين، الطلاب، المستخدمين، الأدوار، المؤسسات |
| 👤 | حسابي | الملف الشخصي |

---

## 🔐 الصلاحيات

الـ Sidebar يفحص الصلاحيات تلقائياً:
```php
if(\App\Helpers\NavigationHelper::canAccess($item['route'])) {
    // عرض الـ Link للمستخدمين المصرح لهم فقط
}
```

---

## 💡 أمثلة الاستخدام

### في أي Blade View:
```blade
<!-- الـ Sidebar جاهز بالفعل في app.blade.php -->
<!-- لا تحتاج لفعل أي شيء، سيظهر تلقائياً -->
```

### إضافة Routes جديدة:
```php
// 1. أولاً: أضف Route في web.php
Route::get('/audio', [AudioController::class, 'index'])->name('audio.index');

// 2. ثانياً: أضفه في NavigationHelper
[
    'title' => 'المقاطع الصوتية',
    'icon' => '🎵',
    'items' => [
        ['name' => 'جميع المقاطع', 'route' => 'audio.index'],
    ]
]

// ✅ وانتهى! الـ Link سيظهر تلقائياً في الـ Sidebar
```

---

## 🚀 كيفية الاستخدام الفوري

1. **الـ NavigationHelper** جاهز ومُفعّل
2. **الـ Component** مرتبط بالـ Layout
3. **Simple as that!** 🎉

---

## ⚙️ التخصيص الإضافي

إذا أردت تغيير:
- **الألوان**: عدّل `--gold` و `--deep-green` في app.blade.php
- **الأيقونات**: استخدم أي Font Awesome icon
- **الأقسام**: عدّل الـ array في NavigationHelper
- **الأسماء**: غيّر strings العربية مباشرة

---

**تم الإنشاء:** 11 مارس 2026  
**التحديث الأخير:** الآن ✨
